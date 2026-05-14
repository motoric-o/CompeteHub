<?php

namespace App\Core\Competition;

use App\Models\Competition;

class CompetitionFactory
{
    public static function make(Competition $competition): AbstractCompetition
    {
        return match ($competition->type) {
            'individual' => new IndividualCompetition($competition),
            'team' => new TeamCompetition($competition),
            default => throw new \InvalidArgumentException("Unknown competition type: {$competition->type}"),
        };
    }
}
