<?php

namespace App\Core\Scoring;

class TimeBasedScoringStrategy implements ScoringStrategy
{
    protected float $threshold;
    /**
     * Create a new class instance.
     */
    public function __construct(float $threshold)
    {
        $this->threshold = $threshold;
    }

    public function calculate($submission)
    {
        $score = 100;
        if ($submission->time_taken > $this->threshold) {
            return 0;
        }

        $score -= ($submission->time_taken / $this->threshold) * 100;
        return $score;
    }
}
