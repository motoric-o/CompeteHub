<?php

namespace App\Services\Facade;

use App\Models\User;
use App\Models\Competition;
use App\Services\Notification\NotificationLogService;

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
        private MailService           $mailService,
        private PDFGenerator          $pdfGenerator,
        private StorageService        $storageService,
        private NotificationLogService $logService,
    ) {}

    /**
     * Expose MailService for direct use in ReviewCommandExecutor.
     * (Avoids having to inject MailService separately in command classes)
     */
    public function getMailService(): MailService
    {
        return $this->mailService;
    }

    // ── Email Notifications ────────────────────────────────

    /**
     * Kirim notifikasi email saat pendaftaran diterima.
     */
    public function sendEmailNotification(int $userId, int $competitionId, ?int $triggeredBy = null): void
    {
        $user = User::findOrFail($userId);
        $competition = Competition::findOrFail($competitionId);
        $subject = "Pendaftaran {$competition->name} Diterima";
        $body = "Hai {$user->name}, pendaftaran Anda untuk {$competition->name} telah diterima.";

        try {
            $this->mailService->send($user->email, $subject, $body);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $registration = \App\Models\Registration::where('competition_id', $competitionId)
                ->where(function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhereHas('team', function($t) use ($userId) {
                          $t->where('user_id', $userId);
                      });
                })->first();

            $this->logService->record(
                eventType: 'registration_accepted',
                recipientEmail: $user->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body],
                notifiableType: 'registration',
                notifiableId: $registration?->id,
                competitionId: $competitionId,
                triggeredBy: $triggeredBy ?? auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException("Gagal mengirim email: " . $error);
        }
    }

    /**
     * Kirim notifikasi email hasil penilaian.
     */
    public function sendResultEmail(int $userId, float $score, ?int $submissionId = null, ?int $triggeredBy = null): void
    {
        $user = User::findOrFail($userId);
        $subject = 'Hasil Penilaian Tersedia';
        $body = "Hai {$user->name}, nilai terbaru Anda: {$score}.";

        try {
            $this->mailService->send($user->email, $subject, $body);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $competitionId = null;
            if ($submissionId) {
                $submission = \App\Models\Submission::find($submissionId);
                $competitionId = $submission?->competition_id;
            }
            $this->logService->record(
                eventType: 'score_published',
                recipientEmail: $user->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body, 'score' => $score],
                notifiableType: 'submission',
                notifiableId: $submissionId,
                competitionId: $competitionId,
                triggeredBy: $triggeredBy ?? auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException("Gagal mengirim email: " . $error);
        }
    }

    /**
     * Kirim notifikasi saat anggota bergabung ke tim.
     */
    public function sendTeamJoinNotification(User $captain, string $memberName, string $teamName, ?int $teamId = null): void
    {
        $subject = "Anggota Baru Bergabung ke Tim {$teamName}";
        $body = "Hai {$captain->name}, {$memberName} telah bergabung ke tim {$teamName}.";

        try {
            $this->mailService->send($captain->email, $subject, $body);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $competitionId = null;
            $registrationId = null;
            if ($teamId) {
                $team = \App\Models\Team::find($teamId);
                if ($team) {
                    $competitionId = $team->competition_id;
                    $registration = \App\Models\Registration::where('team_id', $teamId)->first();
                    $registrationId = $registration?->id;
                }
            }
            $this->logService->record(
                eventType: 'team_join',
                recipientEmail: $captain->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body, 'memberName' => $memberName, 'teamName' => $teamName, 'teamId' => $teamId],
                notifiableType: 'registration',
                notifiableId: $registrationId,
                competitionId: $competitionId,
                triggeredBy: auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException("Gagal mengirim email: " . $error);
        }
    }

    /**
     * Kirim notifikasi saat anggota dikeluarkan dari tim.
     */
    public function sendTeamKickNotification(User $member, string $teamName, ?int $teamId = null): void
    {
        $subject = "Anda Dikeluarkan dari Tim {$teamName}";
        $body = "Hai {$member->name}, Anda telah dikeluarkan dari tim {$teamName} oleh kapten.";

        try {
            $this->mailService->send($member->email, $subject, $body);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $competitionId = null;
            $registrationId = null;
            if ($teamId) {
                $team = \App\Models\Team::find($teamId);
                if ($team) {
                    $competitionId = $team->competition_id;
                    $registration = \App\Models\Registration::where('team_id', $teamId)->first();
                    $registrationId = $registration?->id;
                }
            }
            $this->logService->record(
                eventType: 'team_kick',
                recipientEmail: $member->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body, 'teamName' => $teamName, 'teamId' => $teamId],
                notifiableType: 'registration',
                notifiableId: $registrationId,
                competitionId: $competitionId,
                triggeredBy: auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException("Gagal mengirim email: " . $error);
        }
    }

    /**
     * Kirim notifikasi saat anggota meninggalkan tim secara sukarela.
     */
    public function sendTeamLeaveNotification(User $captain, string $memberName, string $teamName, ?int $teamId = null): void
    {
        $subject = "Anggota Keluar dari Tim {$teamName}";
        $body = "Hai {$captain->name}, {$memberName} telah keluar dari tim {$teamName}.";

        try {
            $this->mailService->send($captain->email, $subject, $body);
            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $competitionId = null;
            $registrationId = null;
            if ($teamId) {
                $team = \App\Models\Team::find($teamId);
                if ($team) {
                    $competitionId = $team->competition_id;
                    $registration = \App\Models\Registration::where('team_id', $teamId)->first();
                    $registrationId = $registration?->id;
                }
            }
            $this->logService->record(
                eventType: 'team_leave',
                recipientEmail: $captain->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body, 'memberName' => $memberName, 'teamName' => $teamName, 'teamId' => $teamId],
                notifiableType: 'registration',
                notifiableId: $registrationId,
                competitionId: $competitionId,
                triggeredBy: auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException("Gagal mengirim email: " . $error);
        }
    }

    /**
     * Broadcast email manual ke semua peserta dalam kompetisi tertentu (Dadakan).
     */
    public function broadcastToParticipants(int $competitionId, string $subject, string $body, ?int $triggeredBy = null): array
    {
        $competition = Competition::with([
            'teams.members',
            'registrations.user',
            'registrations.team.captain',
            'registrations.team.members',
        ])->findOrFail($competitionId);

        $participants = collect();

        if ($competition->type === 'team') {
            foreach ($competition->teams as $team) {
                if ($team->captain) {
                    $participants->push($team->captain);
                }

                foreach ($team->members as $member) {
                    $participants->push($member);
                }
            }
        } else {
            $participants = $competition->registrations
                ->whereNotIn('status', ['rejected'])
                ->pluck('user')
                ->filter();
        }

        $participants = $participants
            ->filter(fn ($user) => filled($user?->email))
            ->unique('id')
            ->values();

        if ($participants->isEmpty()) {
            throw new \RuntimeException('Tidak ada peserta aktif yang memiliki email pada kompetisi ini.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($participants as $user) {
            $emailBody = "Halo {$user->name},<br><br>{$body}<br><br>Salam,<br>Panitia {$competition->name}";

            try {
                $sent = $this->mailService->send($user->email, $subject, $emailBody);

                if (! $sent) {
                    throw new \RuntimeException('Mail service gagal mengirim email. Cek konfigurasi MAIL di file .env.');
                }

                $status = 'sent';
                $error = null;
                $sentCount++;
            } catch (\Throwable $e) {
                $status = 'failed';
                $error = $e->getMessage();
                $failedCount++;
            }

            if ($this->logService) {
                $registration = $competition->registrations
                    ->first(function ($registration) use ($user) {
                        if ((int) $registration->user_id === (int) $user->id) {
                            return true;
                        }

                        if ($registration->team && (int) $registration->team->user_id === (int) $user->id) {
                            return true;
                        }

                        return $registration->team?->members
                            ? $registration->team->members->contains('id', $user->id)
                            : false;
                    });

                $this->logService->record(
                    eventType: 'broadcast',
                    recipientEmail: $user->email,
                    subject: $subject,
                    status: $status,
                    payload: ['body' => $emailBody],
                    notifiableType: 'registration',
                    notifiableId: $registration?->id,
                    competitionId: $competitionId,
                    triggeredBy: $triggeredBy ?? auth()->id(),
                    failureReason: $error,
                );
            }
        }

        if ($sentCount === 0) {
            throw new \RuntimeException('Semua email gagal dikirim. Cek konfigurasi MAIL di file .env atau lihat Log Notifikasi.');
        }

        return [
            'sent' => $sentCount,
            'failed' => $failedCount,
            'total' => $participants->count(),
        ];
    }

    /**
     * Kirim email reminder ke peserta.
     */
    public function sendReminderNotification(int $registrationId, string $message, ?int $triggeredBy = null): void
    {
        $registration = \App\Models\Registration::with(['user', 'team.captain', 'competition'])->findOrFail($registrationId);
        $recipient = $registration->user ?? $registration->team?->captain;

        if (! $recipient) {
            throw new \RuntimeException('Tidak bisa menemukan penerima notifikasi.');
        }

        if (blank($recipient->email)) {
            throw new \RuntimeException('Peserta tidak memiliki alamat email.');
        }

        $subject = 'Reminder Registrasi — ' . $registration->competition->name;
        $body = "Halo {$recipient->name},<br><br>{$message}<br><br>Salam,<br>Panitia {$registration->competition->name}";

        try {
            $sent = $this->mailService->send($recipient->email, $subject, $body);

            if (! $sent) {
                throw new \RuntimeException('Mail service gagal mengirim email. Cek konfigurasi MAIL di file .env.');
            }

            $status = 'sent';
            $error = null;
        } catch (\Throwable $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        if ($this->logService) {
            $this->logService->record(
                eventType: 'reminder_sent',
                recipientEmail: $recipient->email,
                subject: $subject,
                status: $status,
                payload: ['body' => $body],
                notifiableType: 'registration',
                notifiableId: $registration->id,
                competitionId: $registration->competition_id,
                triggeredBy: $triggeredBy ?? auth()->id(),
                failureReason: $error,
            );
        }

        if ($status === 'failed') {
            throw new \RuntimeException($error);
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

    /**
     * Generate sertifikat PDF untuk peserta kompetisi.
     */
    public function generatePDFCertificate(int $userId, int $competitionId, array $data): string
    {
        return $this->pdfGenerator->generateCertificate($userId, $competitionId, $data);
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
