<?php

namespace App\Services\Scoring;

interface ScoringServiceInterface
{
    /**
     * Submit a score for a submission.
     *
     * @param  int    $submissionId
     * @param  int    $judgeUserId
     * @param  array  $criteriaScores
     * @param  string|null $notes
     * @return \App\Models\Score
     *
     * @throws \App\Exceptions\UnauthorizedJudgeException
     */
    public function submitScore(int $submissionId, int $judgeUserId, array $criteriaScores, ?string $notes = null): \App\Models\Score;
}
