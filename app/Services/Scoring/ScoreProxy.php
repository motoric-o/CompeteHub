<?php

namespace App\Services\Scoring;

use App\Exceptions\UnauthorizedJudgeException;
use App\Models\JuryAssignment;
use App\Models\Score;
use App\Models\Submission;

/**
 * Proxy Pattern (F-12) — verifikasi juri sebelum submit nilai.
 *
 * ScoreProxy membungkus ScoringService dan memastikan:
 * 1. User memiliki role 'judge'
 * 2. User di-assign sebagai juri di kompetisi tersebut (via jury_assignments)
 *
 * Jika tidak lolos, throw UnauthorizedJudgeException.
 */
class ScoreProxy implements ScoringServiceInterface
{
    public function __construct(
        private ScoringServiceInterface $realService,
    ) {}

    public function submitScore(int $submissionId, int $judgeUserId, float $score, ?string $notes = null): Score
    {
        $submission = Submission::findOrFail($submissionId);

        // Cek apakah juri di-assign ke kompetisi ini
        $isAssigned = JuryAssignment::where('user_id', $judgeUserId)
            ->where('competition_id', $submission->competition_id)
            ->exists();

        if (! $isAssigned) {
            throw new UnauthorizedJudgeException(
                "Judge (user_id={$judgeUserId}) is not assigned to competition_id={$submission->competition_id}."
            );
        }

        // Delegasi ke real service
        return $this->realService->submitScore($submissionId, $judgeUserId, $score, $notes);
    }
}
