<?php

namespace App\Factories;

use App\Models\Competition;
use App\Core\Competition\IndividualCompetition;
use App\Core\Competition\TeamCompetition;
use App\Core\Competition\AbstractCompetition;
use Illuminate\Support\Str;

class CompetitionFactory
{
    public static function create(array $data): AbstractCompetition
    {
        $data['uuid'] = (string) Str::uuid();
        $data['user_id'] = auth()->id();

        $competition = Competition::create($data);

        return self::make($competition);
    }

    public static function make(Competition $competition): AbstractCompetition
    {
        return match ($competition->type) {
            'individual' => new IndividualCompetition($competition),
            'team' => new TeamCompetition($competition),
            default => throw new \InvalidArgumentException("Unknown competition type: {$competition->type}"),
        };
    }
}
