<?php

namespace App\Core\Scoring;

class CommunityVotingScoringStrategy implements ScoringStrategy
{
    /**
     * Calculate score based on community votes.
     * 1 vote = 1 score point.
     *
     * @param int $votesCount
     * @return float
     */
    public function calculate($votesCount)
    {
        return (float) $votesCount;
    }
}
