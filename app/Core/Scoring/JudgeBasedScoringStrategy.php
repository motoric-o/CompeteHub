<?php

namespace App\Core\Scoring;

use App\Models\Competition;

class JudgeBasedScoringStrategy implements ScoringStrategy
{
    public function calculate($judgeScores)
    {
        if ($judgeScores->isEmpty()) {
            return 0;
        }

        $totalScores = $judgeScores->map(function ($scoreModel) {
            if ($scoreModel->criterionScores && $scoreModel->criterionScores->isNotEmpty()) {
                return $scoreModel->criterionScores->sum(function ($detail) {
                    $weight = $detail->criterion ? $detail->criterion->weight : 1.0;
                    return $detail->value * $weight;
                });
            }
            
            return $scoreModel->score;
        });

        return (float) $totalScores->avg();
    }
}
