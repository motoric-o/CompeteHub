<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Services\Competition\CompetitionFactory;
use App\Services\Facade\NotificationFacade;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

/**
 * TeamService — Business logic untuk fitur Manajemen Tim (F-07).
 *
 * Mengelola pembuatan tim, join via invite code, kick anggota,
 * dan leave tim. Menerapkan prinsip SRP: controller hanya meneruskan
 * request ke service ini, bukan mengandung business logic sendiri.
 */
class TeamService
{
    public function __construct(
        private NotificationFacade $notificationFacade
    ) {}

    /**
     * Buat tim baru untuk kompetisi tertentu.
     *
     * Menggunakan CompetitionFactory untuk memastikan kompetisi
     * bertipe 'team' sebelum tim bisa dibuat.
     *
     * @throws InvalidArgumentException Jika kompetisi bukan tipe tim
     * @throws RuntimeException         Jika pendaftaran tidak valid
     */
    public function createTeam(User $captain, Competition $competition, string $teamName): Team
    {
        // Guard clause: pastikan user aktif
        if (! $captain->isActive()) {
            throw new RuntimeException('Akun Anda sedang dalam status suspended.');
        }

        // Guard clause: pastikan user adalah participant
        if (! $captain->isParticipant()) {
            throw new RuntimeException('Hanya peserta yang bisa membuat tim.');
        }

        // Factory Pattern: buat domain object sesuai tipe kompetisi
        $competitionObj = CompetitionFactory::fromModel($competition);

        // Guard clause: pastikan kompetisi bertipe tim
        if ($competitionObj->getType() !== 'team') {
            throw new InvalidArgumentException(
                'Kompetisi ini bertipe individual, tidak bisa membuat tim.'
            );
        }

        // Validasi pendaftaran (kuota, periode, dll.)
        $validation = $competitionObj->validateRegistration($captain->id);
        if (! $validation['valid']) {
            throw new RuntimeException($validation['message']);
        }

        // Guard clause: cek user belum punya tim di kompetisi ini
        $existingTeam = Team::where('competition_id', $competition->id)
            ->where('user_id', $captain->id)
            ->first();

        if ($existingTeam) {
            throw new RuntimeException('Anda sudah memiliki tim di kompetisi ini.');
        }

        // Buat tim dalam transaction untuk konsistensi data
        return DB::transaction(function () use ($captain, $competition, $teamName) {
            $team = Team::create([
                'competition_id' => $competition->id,
                'user_id'        => $captain->id,
                'name'           => $teamName,
            ]);

            // Kapten otomatis jadi anggota pertama
            TeamMember::create([
                'team_id'   => $team->id,
                'user_id'   => $captain->id,
                'joined_at' => now(),
            ]);

            return $team;
        });
    }

    /**
     * Join tim via invite code.
     *
     * @throws RuntimeException Jika kode tidak valid, user sudah tergabung, dll.
     */
    public function joinByInviteCode(User $user, string $inviteCode): Team
    {
        // Guard clause: pastikan user aktif
        if (! $user->isActive()) {
            throw new RuntimeException('Akun Anda sedang dalam status suspended.');
        }

        // Guard clause: pastikan user adalah participant
        if (! $user->isParticipant()) {
            throw new RuntimeException('Hanya peserta yang bisa bergabung ke tim.');
        }

        // Cari tim berdasarkan invite code
        $team = Team::where('invite_code', $inviteCode)->first();

        if (! $team) {
            throw new RuntimeException('Kode undangan tidak valid atau tidak ditemukan.');
        }

        // Guard clause: cek user belum tergabung di tim ini
        if ($team->hasMember($user)) {
            throw new RuntimeException('Anda sudah menjadi anggota tim ini.');
        }

        // Guard clause: cek user belum punya tim lain di kompetisi yang sama
        $alreadyInCompetition = TeamMember::whereHas('team', function ($q) use ($team) {
            $q->where('competition_id', $team->competition_id);
        })->where('user_id', $user->id)->exists();

        if ($alreadyInCompetition) {
            throw new RuntimeException('Anda sudah tergabung di tim lain pada kompetisi ini.');
        }

        // Tambahkan sebagai anggota
        TeamMember::create([
            'team_id'   => $team->id,
            'user_id'   => $user->id,
            'joined_at' => now(),
        ]);

        // Facade Pattern: kirim notifikasi ke kapten
        $this->notificationFacade->sendTeamJoinNotification(
            $team->captain,
            $user->name,
            $team->name,
        );

        return $team->fresh(['members']);
    }

    /**
     * Keluarkan anggota dari tim (hanya kapten yang berhak).
     *
     * @throws RuntimeException Jika bukan kapten, atau member tidak ditemukan
     */
    public function kickMember(User $captain, Team $team, User $member): void
    {
        // Guard clause: pastikan yang kick adalah kapten
        if (! $team->isCaptain($captain)) {
            throw new RuntimeException('Hanya kapten yang bisa mengeluarkan anggota.');
        }

        // Guard clause: kapten tidak bisa kick diri sendiri
        if ($captain->id === $member->id) {
            throw new RuntimeException('Kapten tidak bisa mengeluarkan diri sendiri dari tim.');
        }

        // Guard clause: pastikan member memang ada di tim
        if (! $team->hasMember($member)) {
            throw new RuntimeException('User ini bukan anggota tim.');
        }

        // Hapus dari tim
        TeamMember::where('team_id', $team->id)
            ->where('user_id', $member->id)
            ->delete();

        // Facade Pattern: kirim notifikasi ke anggota yang dikeluarkan
        $this->notificationFacade->sendTeamKickNotification($member, $team->name);
    }

    /**
     * Anggota keluar dari tim secara sukarela.
     *
     * @throws RuntimeException Jika kapten mencoba leave (harus transfer dulu)
     */
    public function leaveTeam(User $user, Team $team): void
    {
        // Guard clause: pastikan user memang anggota tim
        if (! $team->hasMember($user)) {
            throw new RuntimeException('Anda bukan anggota tim ini.');
        }

        // Guard clause: kapten tidak bisa leave
        if ($team->isCaptain($user)) {
            throw new RuntimeException(
                'Kapten tidak bisa keluar dari tim. Hapus tim atau transfer kepemimpinan terlebih dahulu.'
            );
        }

        TeamMember::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->delete();

        // Facade Pattern: kirim notifikasi ke kapten
        $this->notificationFacade->sendTeamLeaveNotification(
            $team->captain,
            $user->name,
            $team->name,
        );
    }

    /**
     * Regenerate invite code untuk tim (hanya kapten).
     */
    public function regenerateInviteCode(User $captain, Team $team): string
    {
        if (! $team->isCaptain($captain)) {
            throw new RuntimeException('Hanya kapten yang bisa membuat ulang kode undangan.');
        }

        $newCode = Team::generateUniqueInviteCode();
        $team->update(['invite_code' => $newCode]);

        return $newCode;
    }
}
