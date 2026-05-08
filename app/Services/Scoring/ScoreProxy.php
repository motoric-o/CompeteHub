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

    public function submitScore(int $submissionId, int $judgeUserId, float $score, ?string $notes = null): Score
    {
        $submission = Submission::findOrFail($submissionId);

        $isAssigned = JuryAssignment::where('user_id', $judgeUserId)
            ->where('competition_id', $submission->competition_id)
            ->exists();

        return $this->realService->submitScore($submissionId, $judgeUserId, $score, $notes);
    }
}
