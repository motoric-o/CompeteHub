<?php

namespace App\Services\Dashboard;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Support\Collection;

/**
 * CommandCenterService — aggregates operational data for the committee dashboard.
 *
 * Tanggung jawab:
 * - Query registrations, payments, documents dengan efisien (tidak N+1)
 * - Kalkulasi readiness score kompetisi
 * - Deteksi operational bottlenecks dan warnings
 * - Produksi CommandCenterDTO untuk dikonsumsi view
 *
 * Tidak menggunakan Mediator/Command Pattern karena:
 * - Tidak ada cross-object communication yang complex
 * - Ini adalah read-only aggregation, bukan command execution
 * - Service + DTO lebih readable dan maintainable untuk kasus ini
 */
class CommandCenterService
{
    /**
     * Build complete operational snapshot for a competition.
     * Semua query di-eager load untuk menghindari N+1.
     */
    public function buildCommandCenter(Competition $competition): CommandCenterDTO
    {
        // Single query dengan semua relasi yang dibutuhkan
        $allRegistrations = Registration::where('competition_id', $competition->id)
            ->with(['user', 'team.captain', 'documents', 'payment'])
            ->latest()
            ->get();

        $active   = $allRegistrations->whereNotIn('status', ['rejected']);
        $pending  = $allRegistrations->where('status', 'pending');
        $rejected = $allRegistrations->where('status', 'rejected');

        $pendingPayments             = $this->getPendingPayments($allRegistrations);
        $pendingDocumentRegistrations = $this->getPendingDocumentRegistrations($allRegistrations);
        $overdueRegistrations        = $this->getOverdueRegistrations($allRegistrations, $competition);

        $readinessScore    = $this->calculateReadinessScore($competition, $allRegistrations);
        $readinessBreakdown = $this->buildReadinessBreakdown($competition, $allRegistrations);
        $warnings          = $this->detectWarnings($competition, $allRegistrations);

        $totalActive      = $active->count();
        $quota            = $competition->quota;
        $quotaFillPercent = $quota ? round(($totalActive / $quota) * 100, 1) : 0;

        return new CommandCenterDTO(
            newRegistrationsCount:          $pending->count(),
            newRegistrations:               $pending->values()->all(),
            pendingPaymentsCount:           $pendingPayments->count(),
            pendingPayments:                $pendingPayments->values()->all(),
            pendingDocumentsCount:          $pendingDocumentRegistrations->count(),
            pendingDocumentRegistrations:   $pendingDocumentRegistrations->values()->all(),
            rejectedCount:                  $rejected->count(),
            rejectedRegistrations:          $rejected->values()->all(),
            overdueCount:                   $overdueRegistrations->count(),
            overdueRegistrations:           $overdueRegistrations->values()->all(),
            readinessScore:                 $readinessScore,
            readinessBreakdown:             $readinessBreakdown,
            warnings:                       $warnings,
            totalActiveRegistrations:       $totalActive,
            quota:                          $quota,
            quotaFillPercent:               $quotaFillPercent,
        );
    }

    // ── Private Aggregations ─────────────────────────────────────────

    /**
     * Registrasi yang payment-nya masih pending_verification.
     */
    private function getPendingPayments(Collection $registrations): Collection
    {
        return $registrations->filter(function ($reg) {
            return $reg->payment && $reg->payment->status === 'pending_verification';
        });
    }

    /**
     * Registrasi yang punya dokumen pending (belum di-review committee).
     */
    private function getPendingDocumentRegistrations(Collection $registrations): Collection
    {
        return $registrations->filter(function ($reg) {
            return $reg->documents->where('status', 'pending')->count() > 0;
        });
    }

    /**
     * Registrasi yang belum verified padahal registration_end sudah lewat.
     */
    private function getOverdueRegistrations(Collection $registrations, Competition $competition): Collection
    {
        if (! $competition->registration_end || now()->isBefore($competition->registration_end)) {
            return collect();
        }

        return $registrations->filter(function ($reg) {
            return ! in_array($reg->status, ['verified', 'rejected']);
        });
    }

    // ── Readiness Score ──────────────────────────────────────────────

    /**
     * Calculate competition readiness score (0-100).
     *
     * Scoring factors:
     * - Has form template: 20 pts
     * - Has at least one registration: 10 pts
     * - No overdue registrations: 20 pts
     * - Verified registrations ratio: up to 30 pts
     * - No pending payments (if paid): 10 pts
     * - No pending documents: 10 pts
     */
    private function calculateReadinessScore(Competition $competition, Collection $registrations): int
    {
        $score = 0;

        // Form template exists
        if ($competition->formTemplates()->exists()) {
            $score += 20;
        }

        // At least one registration
        if ($registrations->isNotEmpty()) {
            $score += 10;
        }

        $active = $registrations->whereNotIn('status', ['rejected']);

        // No overdue unverified registrations
        if ($competition->registration_end && now()->isAfter($competition->registration_end)) {
            $unverified = $active->whereNotIn('status', ['verified'])->count();
            if ($unverified === 0) {
                $score += 20;
            }
        } else {
            $score += 20; // Registration still open, not penalized
        }

        // Verified ratio
        if ($active->count() > 0) {
            $verifiedCount = $active->where('status', 'verified')->count();
            $ratio         = $verifiedCount / $active->count();
            $score         += (int) round($ratio * 30);
        }

        // No pending payments
        $pendingPayments = $registrations->filter(
            fn ($r) => $r->payment && $r->payment->status === 'pending_verification'
        )->count();

        if ($pendingPayments === 0) {
            $score += 10;
        }

        // No pending documents
        $pendingDocs = $registrations->filter(
            fn ($r) => $r->documents->where('status', 'pending')->count() > 0
        )->count();

        if ($pendingDocs === 0) {
            $score += 10;
        }

        return min(100, $score);
    }

    private function buildReadinessBreakdown(Competition $competition, Collection $registrations): array
    {
        $active = $registrations->whereNotIn('status', ['rejected']);

        return [
            'has_form_template'   => $competition->formTemplates()->exists(),
            'has_registrations'   => $registrations->isNotEmpty(),
            'verified_count'      => $active->where('status', 'verified')->count(),
            'total_active'        => $active->count(),
            'pending_payments'    => $registrations->filter(fn ($r) => $r->payment && $r->payment->status === 'pending_verification')->count(),
            'pending_docs'        => $registrations->filter(fn ($r) => $r->documents->where('status', 'pending')->count() > 0)->count(),
        ];
    }

    // ── Warnings ─────────────────────────────────────────────────────

    /**
     * Detect operational warnings — actionable alerts untuk committee.
     * @return array of ['type' => string, 'message' => string, 'severity' => string]
     */
    private function detectWarnings(Competition $competition, Collection $registrations): array
    {
        $warnings = [];

        // No form template
        if (! $competition->formTemplates()->exists()) {
            $warnings[] = [
                'type'     => 'no_form_template',
                'message'  => 'Kompetisi belum memiliki form template. Peserta tidak bisa mendaftar.',
                'severity' => 'error',
            ];
        }

        // Registration ending soon (within 3 days)
        if ($competition->registration_end) {
            $daysLeft = now()->diffInDays($competition->registration_end, false);
            if ($daysLeft > 0 && $daysLeft <= 3) {
                $warnings[] = [
                    'type'     => 'registration_closing_soon',
                    'message'  => "Periode pendaftaran berakhir dalam {$daysLeft} hari. " .
                                  "Masih ada {$registrations->where('status', 'pending')->count()} pendaftaran belum diproses.",
                    'severity' => 'warning',
                ];
            }
        }

        // Quota almost full
        if ($competition->quota) {
            $active = $registrations->whereNotIn('status', ['rejected'])->count();
            $fillPercent = ($active / $competition->quota) * 100;

            if ($fillPercent >= 90) {
                $remaining = $competition->quota - $active;
                $warnings[] = [
                    'type'     => 'quota_almost_full',
                    'message'  => "Kuota hampir penuh! Tersisa {$remaining} slot dari {$competition->quota}.",
                    'severity' => 'warning',
                ];
            }
        }

        // High rejection rate
        $total    = $registrations->count();
        $rejected = $registrations->where('status', 'rejected')->count();
        if ($total > 5 && $rejected / $total > 0.30) {
            $rejectedPercent = round(($rejected / $total) * 100);
            $warnings[] = [
                'type'     => 'high_rejection_rate',
                'message'  => "{$rejectedPercent}% pendaftaran ditolak. Pertimbangkan memeriksa persyaratan kompetisi.",
                'severity' => 'warning',
            ];
        }

        // Many stale pending registrations (>7 days)
        $stalePending = $registrations->filter(function ($reg) {
            return $reg->status === 'pending' && $reg->created_at->diffInDays(now()) > 7;
        })->count();

        if ($stalePending > 0) {
            $warnings[] = [
                'type'     => 'stale_pending_registrations',
                'message'  => "{$stalePending} pendaftaran sudah pending lebih dari 7 hari tanpa proses.",
                'severity' => 'warning',
            ];
        }

        return $warnings;
    }
}
