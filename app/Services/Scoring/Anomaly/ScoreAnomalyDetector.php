<?php

namespace App\Services\Scoring\Anomaly;

use App\Models\Competition;
use App\Models\Submission;

class ScoreAnomalyDetector
{
    public function __construct(
        private ?ScoreAnomalyStrategy $strategy = null,
    ) {
        $this->strategy ??= new AbsoluteGapScoreAnomalyStrategy();
    }

    public function detectForCompetition(Competition $competition, int $limit = 8): array
    {
        $submissions = Submission::query()
            ->where('competition_id', $competition->id)
            ->with(['round', 'user', 'team', 'scores.user'])
            ->get();

        return $submissions
            ->flatMap(fn (Submission $submission) => $this->detectForSubmission($submission))
            ->sortByDesc('gap')
            ->take($limit)
            ->values()
            ->all();
    }

    public function detectForSubmission(Submission $submission): array
    {
        return collect($this->strategy->detect($submission->scores))
            ->map(function (array $anomaly) use ($submission) {
                $score = $anomaly['score_model'];

                return [
                    'submission_id' => $submission->id,
                    'round_name' => $submission->round?->name ?? '-',
                    'participant_name' => $submission->team?->name ?? $submission->user?->name ?? 'Unknown Participant',
                    'judge_name' => $score->user?->name ?? 'Unknown Judge',
                    'judge_score' => $anomaly['judge_score'],
                    'peer_average' => $anomaly['peer_average'],
                    'gap' => $anomaly['gap'],
                    'severity' => $anomaly['severity'],
                    'strategy' => $anomaly['strategy'],
                    'message' => $this->buildMessage($anomaly),
                ];
            })
            ->values()
            ->all();
    }

    private function buildMessage(array $anomaly): string
    {
        if ($anomaly['severity'] === 'critical') {
            return 'Nilai juri sangat jauh dari rata-rata juri lain dan perlu direview ulang.';
        }

        return 'Nilai juri berbeda cukup jauh dari rata-rata juri lain.';
    }
}