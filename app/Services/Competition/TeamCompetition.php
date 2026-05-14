<?php

namespace App\Services\Competition;

use App\Models\Competition as CompetitionModel;
use App\Models\Team;

/**
 * TeamCompetition — Kompetisi berbasis tim.
 *
 * Peserta harus membentuk tim terlebih dahulu sebelum bisa mendaftar.
 * Hanya kapten tim yang berhak melakukan submit atas nama tim.
 */
class TeamCompetition extends Competition
{
    public function getType(): string
    {
        return 'team';
    }

    public function getTypeLabel(): string
    {
        return 'Kompetisi Tim';
    }

    /**
     * Validasi pendaftaran tim.
     * Cek: pendaftaran terbuka, kuota tim tersedia.
     */
    public function validateRegistration(int $userId): array
    {
        if (! $this->model->isRegistrationOpen()) {
            return ['valid' => false, 'message' => 'Pendaftaran sudah ditutup.'];
        }

        if (! $this->model->hasAvailableQuota()) {
            return ['valid' => false, 'message' => 'Kuota tim sudah penuh.'];
        }

        return ['valid' => true, 'message' => 'OK'];
    }

    /**
     * Mendapatkan semua tim dalam kompetisi ini.
     */
    public function getTeams()
    {
        return $this->model->teams;
    }
}
