<?php

namespace App\Services\Scoring;

use App\Exceptions\UnauthorizedJudgeException;
use App\Models\JuryAssignment;
use App\Models\Score;
use App\Models\Submission;

class ScoreProxy implements ScoringServiceInterface
{
    public function __construct(
        private ScoringServiceInterface $realService,
    ) {
    }

    /**
     * Proxy Pattern — verifies judge is assigned to the competition
     * before delegating to the real ScoringService.
     */
    public function submitScore(int $submissionId, int $judgeUserId, float $score, ?string $notes = null): Score
    {
        $submission = Submission::findOrFail($submissionId);

        $isAssigned = JuryAssignment::where('user_id', $judgeUserId)
            ->where('competition_id', $submission->competition_id)
            ->exists();

        if (!$isAssigned) {
            throw new UnauthorizedJudgeException(
                'You are not assigned as a judge for this competition.'
            );
        }

        $round = $submission->round;
        if ($round->status === 'finished' || ($round->end_date && now()->isAfter($round->end_date))) {
            throw new \Exception('Maaf, periode penilaian untuk babak ini sudah ditutup.');
        }

        // Validate score range: 0-100
        if ($score < 0 || $score > 100) {
            throw new \InvalidArgumentException('Score must be between 0 and 100.');
        }

        return $this->realService->submitScore($submissionId, $judgeUserId, $score, $notes);
    }
}
