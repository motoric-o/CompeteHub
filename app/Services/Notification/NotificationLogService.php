<?php

namespace App\Services\Notification;

use App\Models\NotificationLog;
use App\Models\Competition;
use Illuminate\Support\Collection;

/**
 * NotificationLogService — manages notification event history.
 *
 * Feature 6: Auto Notification Log UI
 *
 * Tanggung jawab:
 * - Record notification events ke notification_logs table
 * - Query log dengan filter (competition, event_type, date range, status)
 * - Provide summary statistics untuk UI
 *
 * Digunakan oleh:
 * - NotificationFacade::send*() methods (auto-log on send)
 * - Committee Notification Log UI controller
 */
class NotificationLogService
{
    /**
     * Record a notification event.
     *
     * @param  string      $eventType       e.g. 'registration_submitted', 'payment_verified'
     * @param  string      $recipientEmail
     * @param  string      $subject
     * @param  string      $status          'sent' | 'failed' | 'pending'
     * @param  array       $payload         full message body and metadata
     * @param  string|null $notifiableType  e.g. 'registration', 'payment'
     * @param  int|null    $notifiableId
     * @param  int|null    $competitionId
     * @param  int|null    $triggeredBy     user_id of committee member (null = system)
     * @param  string|null $failureReason
     */
    public function record(
        string  $eventType,
        string  $recipientEmail,
        string  $subject,
        string  $status = 'sent',
        array   $payload = [],
        ?string $notifiableType = null,
        ?int    $notifiableId = null,
        ?int    $competitionId = null,
        ?int    $triggeredBy = null,
        ?string $failureReason = null,
    ): NotificationLog {
        return NotificationLog::create([
            'event_type'      => $eventType,
            'channel'         => 'email',
            'recipient_email' => $recipientEmail,
            'subject'         => $subject,
            'status'          => $status,
            'payload'         => $payload,
            'notifiable_type' => $notifiableType,
            'notifiable_id'   => $notifiableId,
            'competition_id'  => $competitionId,
            'triggered_by'    => $triggeredBy,
            'failure_reason'  => $failureReason,
            'sent_at'         => $status === 'sent' ? now() : null,
        ]);
    }

    /**
     * Get notification log for a competition with optional filters.
     *
     * @param  int         $competitionId
     * @param  string|null $eventType     filter by event type
     * @param  string|null $status        filter by delivery status
     * @param  int         $perPage
     */
    public function getForCompetition(
        int     $competitionId,
        ?string $eventType = null,
        ?string $status = null,
        int     $perPage = 30,
    ): \Illuminate\Pagination\LengthAwarePaginator {
        $query = NotificationLog::forCompetition($competitionId)
            ->with('triggeredBy')
            ->latest();

        if ($eventType) {
            $query->byEvent($eventType);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get summary statistics for a competition's notification history.
     *
     * @return array{total: int, sent: int, failed: int, event_types: array}
     */
    public function getSummaryForCompetition(int $competitionId): array
    {
        $logs = NotificationLog::forCompetition($competitionId)->get();

        return [
            'total'       => $logs->count(),
            'sent'        => $logs->where('status', 'sent')->count(),
            'failed'      => $logs->where('status', 'failed')->count(),
            'event_types' => $logs->groupBy('event_type')
                ->map(fn ($group) => $group->count())
                ->toArray(),
        ];
    }

    /**
     * Get recent failed notifications for error monitoring.
     */
    public function getRecentFailures(int $competitionId, int $limit = 10): Collection
    {
        return NotificationLog::forCompetition($competitionId)
            ->failed()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get available event types for filter dropdown.
     */
    public static function getEventTypes(): array
    {
        return [
            'registration_submitted'     => 'Registrasi Dikirim',
            'registration_accepted'      => 'Registrasi Diterima',
            'registration_rejected'      => 'Registrasi Ditolak',
            'payment_verified'           => 'Pembayaran Diverifikasi',
            'document_rejected'          => 'Dokumen Ditolak',
            'reminder_sent'              => 'Reminder Dikirim',
            'broadcast'                  => 'Broadcast ke Semua Peserta',
            'score_published'            => 'Nilai Dipublish',
            'team_join'                  => 'Anggota Bergabung ke Tim',
            'team_kick'                  => 'Anggota Dikeluarkan dari Tim',
            'team_leave'                 => 'Anggota Keluar dari Tim',
        ];
    }
}
