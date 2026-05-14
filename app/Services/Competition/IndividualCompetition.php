<?php

namespace App\Services\Competition;

use App\Models\Competition as CompetitionModel;

/**
 * IndividualCompetition — Kompetisi perorangan.
 *
 * Peserta mendaftar secara individu tanpa perlu membentuk tim.
 */
class IndividualCompetition extends Competition
{
    public function getType(): string
    {
        return 'individual';
    }

    public function getTypeLabel(): string
    {
        return 'Kompetisi Individual';
    }

    /**
     * Validasi pendaftaran individu.
     * Cek: pendaftaran terbuka, kuota tersedia.
     */
    public function validateRegistration(int $userId): array
    {
        if (! $this->model->isRegistrationOpen()) {
            return ['valid' => false, 'message' => 'Pendaftaran sudah ditutup.'];
        }

        return ['valid' => true, 'message' => 'OK'];
    }
}
