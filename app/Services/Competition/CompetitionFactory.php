<?php

namespace App\Services\Competition;

use App\Models\Competition as CompetitionModel;
use InvalidArgumentException;

/**
 * CompetitionFactory — Factory Pattern (Creational).
 *
 * Menerima tipe ('individual' / 'team') dan mengembalikan instance
 * IndividualCompetition atau TeamCompetition yang sesuai. Controller
 * tidak perlu tahu kelas konkret mana yang dibuat.
 *
 * Contoh penggunaan:
 *   $comp = CompetitionFactory::create('team', $competitionModel);
 */
class CompetitionFactory
{
    /**
     * Membuat instance Competition berdasarkan tipe.
     *
     * @param string           $type  'individual' atau 'team'
     * @param CompetitionModel $model Eloquent model kompetisi
     *
     * @throws InvalidArgumentException Jika tipe tidak dikenali
     */
    public static function create(string $type, CompetitionModel $model): Competition
    {
        return match ($type) {
            'individual' => new IndividualCompetition($model),
            'team'       => new TeamCompetition($model),
            default      => throw new InvalidArgumentException(
                "Tipe kompetisi '{$type}' tidak dikenali. Gunakan 'individual' atau 'team'."
            ),
        };
    }

    /**
     * Shortcut: buat Competition langsung dari Eloquent model
     * (tipe dibaca dari kolom `type` di model).
     */
    public static function fromModel(CompetitionModel $model): Competition
    {
        return self::create($model->type, $model);
    }
}
