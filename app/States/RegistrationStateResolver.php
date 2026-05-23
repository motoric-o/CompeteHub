<?php

namespace App\States;

use App\Models\Registration;

/**
 * RegistrationStateResolver — resolves registration state into actionable guidance.
 *
 * Implements State Pattern logic WITHOUT a class-per-state explosion.
 * Justifikasi: behavior per-state hanya berupa read-only data (strings, URLs).
 * Full State Pattern (context + 6 state classes) adalah overengineering untuk kasus ini.
 *
 * Tanggung jawab:
 * - Membaca status registrasi
 * - Menghasilkan NextActionCard yang sesuai untuk participant
 * - Menentukan progress timeline (step mana yang sudah selesai)
 *
 * Tidak boleh:
 * - Mutate state registrasi
 * - Melakukan query database (data sudah di-load sebelumnya)
 * - Mengandung business logic validasi
 */
class RegistrationStateResolver
{
    /**
     * Resolve the current registration state into a NextActionCard.
     * Registration harus sudah di-load dengan relasi: competition, documents, payment.
     */
    public function resolve(Registration $registration): NextActionCard
    {
        $status = $registration->status;

        return match ($status) {
            'pending'      => $this->pendingCard($registration),
            'account_ok'   => $this->documentsRequiredCard($registration),
            'documents_ok' => $this->paymentRequiredCard($registration),
            'payment_ok'   => $this->awaitingFinalApprovalCard($registration),
            'verified'     => $this->fullyApprovedCard($registration),
            'rejected'     => $this->rejectedCard($registration),
            default        => $this->unknownCard($registration),
        };
    }

    // ── State Cards ─────────────────────────────────────────────────

    private function pendingCard(Registration $registration): NextActionCard
    {
        return new NextActionCard(
            state:        'pending',
            title:        'Menunggu Validasi Awal',
            description:  'Registrasi Anda sudah diterima dan sedang dalam antrian validasi panitia. ' .
                          'Tidak ada tindakan yang diperlukan saat ini.',
            actionLabel:  'Lihat Detail Registrasi',
            actionUrl:    null,
            severity:     'info',
            icon:         '⏳',
            isActionable: false,
            deadlineNote: $this->formatRegistrationDeadline($registration),
            progressSteps: $this->buildProgressSteps('pending'),
        );
    }

    private function documentsRequiredCard(Registration $registration): NextActionCard
    {
        $pendingDocs = $registration->documents->where('status', 'pending');
        $rejectedDocs = $registration->documents->where('status', 'rejected');

        if ($rejectedDocs->count() > 0) {
            $names = $rejectedDocs->pluck('document_type')->join(', ');
            return new NextActionCard(
                state:        'account_ok',
                title:        'Dokumen Ditolak — Upload Ulang Diperlukan',
                description:  "Dokumen berikut ditolak oleh panitia dan perlu diupload ulang: {$names}. " .
                              'Periksa alasan penolakan dan upload file yang sesuai.',
                actionLabel:  'Upload Ulang Dokumen',
                actionUrl:    null, // Akan diisi oleh controller
                severity:     'error',
                icon:         '❌',
                isActionable: true,
                deadlineNote: null,
                progressSteps: $this->buildProgressSteps('account_ok'),
            );
        }

        if ($pendingDocs->count() > 0) {
            return new NextActionCard(
                state:        'account_ok',
                title:        'Dokumen Sedang Diperiksa',
                description:  'Panitia sedang memverifikasi dokumen Anda. Tidak ada tindakan diperlukan. ' .
                              'Anda akan dihubungi jika ada dokumen yang bermasalah.',
                actionLabel:  'Lihat Status Dokumen',
                actionUrl:    null,
                severity:     'info',
                icon:         '📋',
                isActionable: false,
                deadlineNote: null,
                progressSteps: $this->buildProgressSteps('account_ok'),
            );
        }

        return new NextActionCard(
            state:        'account_ok',
            title:        'Akun Terverifikasi — Menunggu Review Dokumen',
            description:  'Akun Anda sudah diverifikasi. Panitia akan segera memeriksa kelengkapan dokumen.',
            actionLabel:  'Lihat Registrasi',
            actionUrl:    null,
            severity:     'info',
            icon:         '✅',
            isActionable: false,
            deadlineNote: null,
            progressSteps: $this->buildProgressSteps('account_ok'),
        );
    }

    private function paymentRequiredCard(Registration $registration): NextActionCard
    {
        $payment = $registration->payment;
        $competition = $registration->competition;

        // Free competition — should not normally reach this state, tapi handle gracefully
        if ($competition->registration_fee <= 0) {
            return new NextActionCard(
                state:        'documents_ok',
                title:        'Dokumen Terverifikasi',
                description:  'Semua dokumen sudah diverifikasi. Panitia akan memproses registrasi Anda.',
                actionLabel:  'Lihat Registrasi',
                actionUrl:    null,
                severity:     'success',
                icon:         '📄',
                isActionable: false,
                deadlineNote: null,
                progressSteps: $this->buildProgressSteps('documents_ok'),
            );
        }

        // Paid competition — check payment status
        if (! $payment || $payment->status === 'unpaid') {
            return new NextActionCard(
                state:        'documents_ok',
                title:        'Upload Bukti Pembayaran',
                description:  'Dokumen Anda sudah diverifikasi. Langkah berikutnya adalah melakukan pembayaran ' .
                              'sebesar Rp ' . number_format($competition->registration_fee, 0, ',', '.') .
                              ' dan mengupload bukti pembayaran.',
                actionLabel:  'Upload Bukti Pembayaran',
                actionUrl:    null,
                severity:     'warning',
                icon:         '💳',
                isActionable: true,
                deadlineNote: $this->formatRegistrationDeadline($registration),
                progressSteps: $this->buildProgressSteps('documents_ok'),
            );
        }

        if ($payment->status === 'pending_verification') {
            return new NextActionCard(
                state:        'documents_ok',
                title:        'Bukti Pembayaran Sedang Diverifikasi',
                description:  'Bukti pembayaran Anda sudah diterima dan sedang dalam proses verifikasi oleh panitia. ' .
                              'Proses ini biasanya membutuhkan 1-2 hari kerja.',
                actionLabel:  'Lihat Status Pembayaran',
                actionUrl:    null,
                severity:     'info',
                icon:         '🔍',
                isActionable: false,
                deadlineNote: null,
                progressSteps: $this->buildProgressSteps('documents_ok'),
            );
        }

        return new NextActionCard(
            state:        'documents_ok',
            title:        'Menunggu Konfirmasi Pembayaran',
            description:  'Status pembayaran: ' . ($payment->status ?? 'tidak diketahui') . '. Hubungi panitia jika ada masalah.',
            actionLabel:  'Lihat Detail',
            actionUrl:    null,
            severity:     'warning',
            icon:         '⚠️',
            isActionable: false,
            deadlineNote: null,
            progressSteps: $this->buildProgressSteps('documents_ok'),
        );
    }

    private function awaitingFinalApprovalCard(Registration $registration): NextActionCard
    {
        return new NextActionCard(
            state:        'payment_ok',
            title:        'Pembayaran Terverifikasi — Menunggu Persetujuan Final',
            description:  'Pembayaran Anda sudah dikonfirmasi. Panitia akan melakukan review final ' .
                          'dan memberikan persetujuan resmi untuk partisipasi Anda.',
            actionLabel:  'Lihat Detail Registrasi',
            actionUrl:    null,
            severity:     'info',
            icon:         '🎯',
            isActionable: false,
            deadlineNote: null,
            progressSteps: $this->buildProgressSteps('payment_ok'),
        );
    }

    private function fullyApprovedCard(Registration $registration): NextActionCard
    {
        return new NextActionCard(
            state:        'verified',
            title:        'Registrasi Disetujui! 🎉',
            description:  'Selamat! Registrasi Anda untuk ' .
                          ($registration->competition->name ?? 'kompetisi ini') .
                          ' telah resmi disetujui. Anda bisa mulai mempersiapkan diri untuk kompetisi.',
            actionLabel:  'Unduh Sertifikat',
            actionUrl:    null, // Diisi oleh controller
            severity:     'success',
            icon:         '🏆',
            isActionable: true,
            deadlineNote: null,
            progressSteps: $this->buildProgressSteps('verified'),
        );
    }

    private function rejectedCard(Registration $registration): NextActionCard
    {
        $reason = $registration->rejection_reason ?? 'Tidak ada alasan yang diberikan.';

        return new NextActionCard(
            state:        'rejected',
            title:        'Registrasi Ditolak',
            description:  "Registrasi Anda ditolak dengan alasan: {$reason}. " .
                          'Silakan perbaiki masalah yang disebutkan dan hubungi panitia jika ada pertanyaan.',
            actionLabel:  'Hubungi Panitia',
            actionUrl:    null,
            severity:     'error',
            icon:         '🚫',
            isActionable: true,
            deadlineNote: null,
            progressSteps: $this->buildProgressSteps('rejected'),
        );
    }

    private function unknownCard(Registration $registration): NextActionCard
    {
        return new NextActionCard(
            state:        $registration->status ?? 'unknown',
            title:        'Status Tidak Dikenali',
            description:  'Terjadi kondisi yang tidak terduga. Silakan hubungi panitia untuk klarifikasi.',
            actionLabel:  'Hubungi Panitia',
            actionUrl:    null,
            severity:     'warning',
            icon:         '❓',
            isActionable: false,
            deadlineNote: null,
            progressSteps: [],
        );
    }

    // ── Helpers ─────────────────────────────────────────────────────

    /**
     * Build progress timeline steps based on current status.
     * Each step has: label, status ('done' | 'current' | 'pending'), icon.
     */
    private function buildProgressSteps(string $currentStatus): array
    {
        $statusOrder = ['pending', 'account_ok', 'documents_ok', 'payment_ok', 'verified'];
        $currentIndex = array_search($currentStatus, $statusOrder);

        // Rejected is a special terminal state
        if ($currentStatus === 'rejected') {
            return [
                ['label' => 'Pendaftaran Dikirim', 'status' => 'done',    'icon' => '✅'],
                ['label' => 'Validasi Akun',       'status' => 'done',    'icon' => '✅'],
                ['label' => 'Verifikasi Dokumen',  'status' => 'current', 'icon' => '❌'],
                ['label' => 'Konfirmasi Pembayaran','status' => 'pending', 'icon' => '⭕'],
                ['label' => 'Disetujui',            'status' => 'pending', 'icon' => '⭕'],
            ];
        }

        $steps = [
            ['label' => 'Pendaftaran Dikirim',    'order' => 0],
            ['label' => 'Validasi Akun',          'order' => 1],
            ['label' => 'Verifikasi Dokumen',     'order' => 2],
            ['label' => 'Konfirmasi Pembayaran',  'order' => 3],
            ['label' => 'Disetujui',              'order' => 4],
        ];

        return array_map(function ($step) use ($currentIndex) {
            if ($currentIndex === false) {
                $stepStatus = 'pending';
                $icon = '⭕';
            } elseif ($step['order'] < $currentIndex) {
                $stepStatus = 'done';
                $icon = '✅';
            } elseif ($step['order'] === $currentIndex) {
                $stepStatus = 'current';
                $icon = '🔵';
            } else {
                $stepStatus = 'pending';
                $icon = '⭕';
            }

            return [
                'label'  => $step['label'],
                'status' => $stepStatus,
                'icon'   => $icon,
            ];
        }, $steps);
    }

    private function formatRegistrationDeadline(Registration $registration): ?string
    {
        $deadline = $registration->competition?->registration_end;
        if (! $deadline) {
            return null;
        }

        $diff = now()->diffInDays($deadline, false);

        if ($diff < 0) {
            return 'Periode pendaftaran sudah berakhir.';
        }

        if ($diff === 0) {
            return '⚠️ Deadline pendaftaran: hari ini!';
        }

        if ($diff <= 3) {
            return "⚠️ Deadline pendaftaran: {$diff} hari lagi ({$deadline->format('d M Y')})";
        }

        return "Deadline pendaftaran: {$deadline->format('d M Y')}";
    }
}
