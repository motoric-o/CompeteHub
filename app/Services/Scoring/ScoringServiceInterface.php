<?php

namespace App\Services\Scoring;

interface ScoringServiceInterface
{
    /**
     * Submit a score for a submission.
     *
     * @param  int    $submissionId
     * @param  int    $judgeUserId
     * @param  float  $score
     * @param  string|null $notes
     * @return \App\Models\Score
     *
     * @throws \App\Exceptions\UnauthorizedJudgeException
     */
    public function submitScore(int $submissionId, int $judgeUserId, float $score, ?string $notes = null): \App\Models\Score;
}
