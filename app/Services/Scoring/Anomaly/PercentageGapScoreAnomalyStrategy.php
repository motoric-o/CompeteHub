<?php

namespace App\Services\Scoring\Anomaly;

use Illuminate\Support\Collection;

class PercentageGapScoreAnomalyStrategy implements ScoreAnomalyStrategy
{
    public function __construct(
        private float $warningPercent = 25.0,
        private float $criticalPercent = 45.0,
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

            if ($peerAverage <= 0) {
                continue;
            }

            $gap = abs($judgeScore - $peerAverage);
            $percentageGap = ($gap / $peerAverage) * 100;

            if ($percentageGap < $this->warningPercent) {
                continue;
            }

            $anomalies[] = [
                'score_model' => $score,
                'judge_score' => round($judgeScore, 2),
                'peer_average' => round($peerAverage, 2),
                'gap' => round($gap, 2),
                'percentage_gap' => round($percentageGap, 2),
                'severity' => $percentageGap >= $this->criticalPercent ? 'critical' : 'warning',
                'strategy' => $this->name(),
            ];
        }

        return $anomalies;
    }

    public function name(): string
    {
        return 'Percentage Gap Strategy';
    }
}