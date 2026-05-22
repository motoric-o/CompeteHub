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
        if ($this->threshold <= 0) {
            return 0.0;
        }

        $timeTaken = $submission->time_taken;

        if ($timeTaken > $this->threshold) {
            return 0.0;
        }

        $score = 100.0 - ($timeTaken / $this->threshold) * 100.0;
        return max(0.0, min(100.0, $score));
    }
}
