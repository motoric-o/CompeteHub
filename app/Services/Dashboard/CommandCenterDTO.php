<?php

namespace App\Services\Dashboard;

use App\Models\Competition;
use App\Models\Registration;

/**
 * CommandCenterDTO — snapshot data operasional satu competition.
 *
 * Diproduksi oleh CommandCenterService dan dikonsumsi langsung oleh Blade view.
 * Immutable — semua kalkulasi dilakukan di service, bukan di view.
 */
readonly class CommandCenterDTO
{
    public function __construct(
        /** Registrasi baru (masih pending) yang belum divalidasi */
        public int    $newRegistrationsCount,
        public array  $newRegistrations,         // Collection of Registration

        /** Payment yang menunggu verifikasi committee */
        public int    $pendingPaymentsCount,
        public array  $pendingPayments,

        /** Dokumen yang masih pending review */
        public int    $pendingDocumentsCount,
        public array  $pendingDocumentRegistrations,

        /** Registrasi yang sudah ditolak */
        public int    $rejectedCount,
        public array  $rejectedRegistrations,

        /** Registrasi yang sudah lewat deadline tapi belum verified */
        public int    $overdueCount,
        public array  $overdueRegistrations,

        /** Skor kesiapan kompetisi (0-100) */
        public int    $readinessScore,

        /** Breakdown readiness */
        public array  $readinessBreakdown,

        /** Warnings operasional */
        public array  $warnings,

        /** Hasil deteksi nilai juri yang tidak wajar */
        public int    $scoringAnomalyCount,
        public array  $scoringAnomalies,

        /** Total registrasi aktif (excludes rejected) */
        public int    $totalActiveRegistrations,

        /** Quota info */
        public ?int   $quota,
        public float  $quotaFillPercent,
    ) {}
}