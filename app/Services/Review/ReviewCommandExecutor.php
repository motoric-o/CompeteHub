<?php

namespace App\Services\Review;

use App\Models\ActionAuditLog;
use App\Models\Registration;
use App\Services\Validation\RegistrationValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ReviewCommandExecutor — executes committee review actions with audit trail.
 *
 * Implements Command Pattern logic for committee bulk/single actions.
 * Setiap action:
 * 1. Divalidasi sebelum eksekusi (safeguard)
 * 2. Dijalankan dalam DB transaction
 * 3. Di-log ke action_audit_logs (dengan before/after snapshot)
 *
 * Actions yang didukung:
 * - approveRegistration(Registration)   — set status = verified
 * - rejectRegistration(Registration)    — set status = rejected + reason
 * - bulkValidate(Competition, ids[])    — run CoR validation chain on multiple registrations
 * - sendReminder(Registration)          — trigger notification (via NotificationFacade)
 */
class ReviewCommandExecutor
{
    public function __construct(
        private RegistrationValidator $validator,
    ) {}

    // ── Single Actions ────────────────────────────────────────────────

    /**
     * Approve a single registration (set status = verified).
     *
     * Safeguard: Registration harus sudah melewati payment_ok sebelum bisa verified.
     */
    public function approveRegistration(
        Registration $registration,
        int $actorId,
    ): ReviewResult {
        // Safeguard: hanya registration dengan status payment_ok yang bisa di-approve
        if ($registration->status !== 'payment_ok') {
            return ReviewResult::failure(
                "Registrasi tidak bisa disetujui. Status saat ini: '{$registration->status}'. " .
                "Registrasi harus sudah melalui verifikasi dokumen dan pembayaran terlebih dahulu."
            );
        }

        $before = $registration->only(['status', 'rejection_reason']);

        try {
            DB::transaction(function () use ($registration, $actorId, $before) {
                $registration->update([
                    'status'           => 'verified',
                    'rejection_reason' => null,
                ]);

                ActionAuditLog::record(
                    actorId:       $actorId,
                    actionType:    'approve_registration',
                    targetType:    'registration',
                    targetId:      $registration->id,
                    competitionId: $registration->competition_id,
                    before:        $before,
                    after:         $registration->fresh()->only(['status', 'rejection_reason']),
                    description:   "Registration #{$registration->id} approved manually by committee.",
                );
            });
        } catch (\Throwable $e) {
            return ReviewResult::failure("Gagal menyetujui registrasi: " . $e->getMessage());
        }

        return ReviewResult::success(
            "Registrasi #{$registration->id} berhasil disetujui.",
            affectedCount: 1,
        );
    }

    /**
     * Reject a single registration with a reason.
     */
    public function rejectRegistration(
        Registration $registration,
        int $actorId,
        string $reason,
    ): ReviewResult {
        if ($registration->status === 'verified') {
            return ReviewResult::failure(
                "Registrasi yang sudah disetujui tidak bisa langsung ditolak. " .
                "Hubungi administrator jika perlu reversal."
            );
        }

        $before = $registration->only(['status', 'rejection_reason']);

        try {
            DB::transaction(function () use ($registration, $actorId, $reason, $before) {
                $registration->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $reason,
                ]);

                ActionAuditLog::record(
                    actorId:       $actorId,
                    actionType:    'reject_registration',
                    targetType:    'registration',
                    targetId:      $registration->id,
                    competitionId: $registration->competition_id,
                    before:        $before,
                    after:         ['status' => 'rejected', 'rejection_reason' => $reason],
                    description:   "Registration #{$registration->id} rejected. Reason: {$reason}",
                );
            });
        } catch (\Throwable $e) {
            return ReviewResult::failure("Gagal menolak registrasi: " . $e->getMessage());
        }

        return ReviewResult::success(
            "Registrasi #{$registration->id} berhasil ditolak.",
            affectedCount: 1,
        );
    }

    // ── Bulk Actions ──────────────────────────────────────────────────

    /**
     * Run CoR validation chain on multiple registrations.
     * Per-competition scope only. Each registration is validated independently.
     *
     * Safeguard: Only operates on registrations belonging to the given competition.
     *
     * @param  int[]  $registrationIds
     */
    public function bulkValidate(
        int $competitionId,
        array $registrationIds,
        int $actorId,
    ): ReviewResult {
        if (empty($registrationIds)) {
            return ReviewResult::failure("Tidak ada registrasi yang dipilih untuk divalidasi.");
        }

        // Safeguard: ensure all IDs belong to this competition
        $registrations = Registration::where('competition_id', $competitionId)
            ->whereIn('id', $registrationIds)
            ->with(['user', 'team.captain', 'documents', 'payment', 'competition.formTemplates'])
            ->get();

        if ($registrations->isEmpty()) {
            return ReviewResult::failure("Tidak ada registrasi valid yang ditemukan untuk kompetisi ini.");
        }

        $batchId = (string) Str::uuid();
        $details = [];
        $passCount = 0;
        $failCount = 0;

        foreach ($registrations as $registration) {
            $before = $registration->only(['status', 'rejection_reason']);

            $result = $this->validator->validate($registration);

            $details[] = [
                'registration_id' => $registration->id,
                'user'            => $registration->user?->name ?? $registration->team?->name ?? 'Unknown',
                'passed'          => $result->passed,
                'new_status'      => $registration->fresh()->status,
                'message'         => $result->message,
            ];

            ActionAuditLog::record(
                actorId:       $actorId,
                actionType:    'bulk_validate',
                targetType:    'registration',
                targetId:      $registration->id,
                competitionId: $competitionId,
                before:        $before,
                after:         $registration->fresh()->only(['status', 'rejection_reason']),
                batchId:       $batchId,
                description:   "Bulk validation: " . ($result->passed ? 'passed' : 'failed') . " — {$result->message}",
            );

            $result->passed ? $passCount++ : $failCount++;
        }

        return ReviewResult::success(
            "{$passCount} registrasi lolos validasi, {$failCount} ditolak.",
            affectedCount: count($registrations),
            details: $details,
            batchId: $batchId,
        );
    }

    /**
     * Send a reminder notification to a participant.
     */
    public function sendReminder(
        Registration $registration,
        int $actorId,
        string $message,
    ): ReviewResult {
        // Only send reminders for non-rejected registrations
        if ($registration->status === 'rejected') {
            return ReviewResult::failure("Tidak bisa mengirim reminder ke registrasi yang sudah ditolak.");
        }

        try {
            $facade = app(\App\Services\Facade\NotificationFacade::class);

            $recipient = $registration->user ?? $registration->team?->captain;
            if (! $recipient) {
                return ReviewResult::failure("Tidak bisa menemukan penerima notifikasi.");
            }

            $facade->sendReminderNotification(
                $registration->id,
                $message,
                $actorId
            );

            ActionAuditLog::record(
                actorId:       $actorId,
                actionType:    'send_reminder',
                targetType:    'registration',
                targetId:      $registration->id,
                competitionId: $registration->competition_id,
                before:        [],
                after:         ['reminder_sent_to' => $recipient->email],
                description:   "Reminder sent to {$recipient->email}: {$message}",
            );
        } catch (\Throwable $e) {
            return ReviewResult::failure("Gagal mengirim reminder: " . $e->getMessage());
        }

        return ReviewResult::success(
            "Reminder berhasil dikirim.",
            affectedCount: 1,
        );
    }
}
