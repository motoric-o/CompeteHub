<?php

namespace App\Services\Scoring\Anomaly;

use Illuminate\Support\Collection;

class AbsoluteGapScoreAnomalyStrategy implements ScoreAnomalyStrategy
{
    public function __construct(
        private float $warningGap = 20.0,
        private float $criticalGap = 35.0,
        private int $minimumJudgeCount = 3,
    ) {}

    public function detect(Collection $scores): array
    {
        $validScores = $scores
            ->filter(fn ($score) => $score->score !== null)
            ->values();

        if ($validScores->count() < $this->minimumJudgeCount) {
            return [];
        }

        $totalScore = $validScores->sum(fn ($score) => (float) $score->score);
        $judgeCount = $validScores->count();
        $anomalies = [];

        foreach ($validScores as $score) {
            $judgeScore = (float) $score->score;
            $peerAverage = ($totalScore - $judgeScore) / max(1, $judgeCount - 1);
            $gap = abs($judgeScore - $peerAverage);

            if ($gap < $this->warningGap) {
                continue;
            }

            $anomalies[] = [
                'score_model' => $score,
                'judge_score' => round($judgeScore, 2),
                'peer_average' => round($peerAverage, 2),
                'gap' => round($gap, 2),
                'severity' => $gap >= $this->criticalGap ? 'critical' : 'warning',
                'strategy' => $this->name(),
            ];
        }

        return $anomalies;
    }

    public function name(): string
    {
        return 'Absolute Gap Strategy';
    }
}