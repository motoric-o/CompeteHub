<?php

namespace App\Services\Competition;

use App\Models\Competition as CompetitionModel;

/**
 * Abstract Competition — Kontrak dasar kompetisi.
 *
 * Setiap implementasi (IndividualCompetition / TeamCompetition) mengoverride
 * method yang spesifik sesuai tipe lomba. Class ini BUKAN Eloquent model,
 * melainkan domain object yang membungkus model dan menambahkan business logic.
 */
abstract class Competition
{
    public function __construct(
        protected CompetitionModel $model
    ) {}

    /**
     * Mendapatkan Eloquent model yang dibungkus.
     */
    public function getModel(): CompetitionModel
    {
        return $this->model;
    }

    /**
     * Tipe kompetisi: 'individual' atau 'team'.
     */
    abstract public function getType(): string;

    /**
     * Label tipe kompetisi yang ramah pengguna.
     */
    abstract public function getTypeLabel(): string;

    /**
     * Validasi apakah peserta boleh mendaftar ke kompetisi ini.
     *
     * @return array{valid: bool, message: string}
     */
    abstract public function validateRegistration(int $userId): array;
}
