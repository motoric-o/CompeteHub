<?php

namespace App\Services\Facade;

use App\Models\User;
use App\Models\Competition;

/**
 * NotificationFacade — Facade Pattern (Structural).
 *
 * Menyederhanakan akses ke tiga subsistem eksternal:
 * - MailService     → pengiriman email
 * - PDFGenerator    → pembuatan sertifikat/report PDF
 * - StorageService  → penyimpanan file ke cloud storage
 *
 * Controller cukup memanggil satu metode di Facade ini tanpa perlu
 * tahu detail implementasi di baliknya. Jika provider email diganti
 * dari Mailgun ke SendGrid, hanya implementasi MailService yang diubah.
 *
 * Contoh penggunaan:
 *   $facade = app(NotificationFacade::class);
 *   $facade->sendTeamJoinNotification($user, $team->name);
 */
class NotificationFacade
{
    public function __construct(
        private MailService    $mailService,
        private PDFGenerator   $pdfGenerator,
        private StorageService $storageService,
    ) {}

    // ── Email Notifications ────────────────────────────────

    /**
     * Kirim notifikasi email saat pendaftaran diterima.
     */
    public function sendEmailNotification(int $userId, int $competitionId): void
    {
        $user = User::findOrFail($userId);
        $competition = Competition::findOrFail($competitionId);

        $this->mailService->send(
            $user->email,
            "Pendaftaran {$competition->name} Diterima",
            "Hai {$user->name}, pendaftaran Anda untuk {$competition->name} telah diterima."
        );
    }

    /**
     * Kirim notifikasi email hasil penilaian.
     */
    public function sendResultEmail(int $userId, float $score): void
    {
        $user = User::findOrFail($userId);

        $this->mailService->send(
            $user->email,
            'Hasil Penilaian Tersedia',
            "Hai {$user->name}, nilai terbaru Anda: {$score}."
        );
    }

    /**
     * Kirim notifikasi saat anggota bergabung ke tim.
     */
    public function sendTeamJoinNotification(User $captain, string $memberName, string $teamName): void
    {
        $this->mailService->send(
            $captain->email,
            "Anggota Baru Bergabung ke Tim {$teamName}",
            "Hai {$captain->name}, {$memberName} telah bergabung ke tim {$teamName}."
        );
    }

    /**
     * Kirim notifikasi saat anggota dikeluarkan dari tim.
     */
    public function sendTeamKickNotification(User $member, string $teamName): void
    {
        $this->mailService->send(
            $member->email,
            "Anda Dikeluarkan dari Tim {$teamName}",
            "Hai {$member->name}, Anda telah dikeluarkan dari tim {$teamName} oleh kapten."
        );
    }

    /**
     * Kirim notifikasi saat anggota meninggalkan tim secara sukarela.
     */
    public function sendTeamLeaveNotification(User $captain, string $memberName, string $teamName): void
    {
        $this->mailService->send(
            $captain->email,
            "Anggota Keluar dari Tim {$teamName}",
            "Hai {$captain->name}, {$memberName} telah keluar dari tim {$teamName}."
        );
    }

    /**
     * Broadcast email manual ke semua peserta dalam kompetisi tertentu (Dadakan).
     */
    public function broadcastToParticipants(int $competitionId, string $subject, string $body): void
    {
        $competition = Competition::with('teams.members')->findOrFail($competitionId);
        
        // Ambil semua member dari semua tim yang terdaftar di kompetisi ini
        // (Atau daftar peserta jika individu)
        $participants = collect();
        if ($competition->type === 'team') {
            foreach ($competition->teams as $team) {
                foreach ($team->members as $member) {
                    $participants->push($member);
                }
            }
        } else {
            // Asumsi relasi participants ada untuk individu
            // $participants = $competition->participants; 
        }

        $participants = $participants->unique('id');

        foreach ($participants as $user) {
            $this->mailService->send($user->email, $subject, "Halo {$user->name},<br><br>{$body}");
        }
    }

    // ── PDF Generation ─────────────────────────────────────

    /**
     * Generate laporan PDF untuk kompetisi.
     */
    public function generatePDFReport(int $competitionId): string
    {
        $competition = Competition::findOrFail($competitionId);

        return $this->pdfGenerator->generate(
            $competitionId,
            "Laporan {$competition->name}",
            ['competition' => $competition->toArray()]
        );
    }

    // ── Storage ────────────────────────────────────────────

    /**
     * Simpan file submisi ke cloud storage.
     */
    public function storeSubmissionFile(string $path, string $contents): string
    {
        return $this->storageService->store("submissions/{$path}", $contents);
    }
}
