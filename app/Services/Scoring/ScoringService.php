<?php

namespace App\Services\Scoring;

use App\Models\Score;
use App\Models\Submission;

class ScoringService implements ScoringServiceInterface
{
    /**
     * Submit (or update) a score for a submission.
     */
    public function submitScore(int $submissionId, int $judgeUserId, float $score, ?string $notes = null): Score
    {
        $submission = Submission::findOrFail($submissionId);

        // Upsert: 1 juri 1x per submisi
        $scoreRecord = Score::updateOrCreate(
            [
                'submission_id' => $submissionId,
                'user_id'       => $judgeUserId,
            ],
            [
                'score'     => $score,
                'notes'     => $notes,
                'scored_at' => now(),
            ]
        );

        // Recalculate final_score as average of all judge scores
        $avgScore = $submission->scores()->avg('score');
        $submission->update([
            'final_score' => $avgScore,
            'status'      => 'scored',
        ]);

        return $scoreRecord;
    }
}
