<?php

namespace App\Services\Scoring;

use App\Models\Score;
use App\Models\Submission;
use App\Patterns\Observer\ScoringSubject;
use App\Patterns\Observer\LeaderboardObserver;
use App\Patterns\Observer\EmailNotifierObserver;

class ScoringService implements ScoringServiceInterface
{
    /**
     * Submit (or update) a score for a submission.
     * Score max = 100 (enforced by validation, not here).
     * Triggers Observer pattern to update leaderboard + send notifications.
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

        // Recalculate final_score = average of all judge scores
        $avgScore = $submission->scores()->avg('score');
        $submission->update([
            'final_score' => $avgScore,
            'status'      => 'scored',
        ]);

        // Trigger Observer — update leaderboard + notify
        $subject = new ScoringSubject();
        $subject->attach(new LeaderboardObserver());
        $subject->attach(new EmailNotifierObserver());
        $subject->notify('score_published', [
            'submission_id' => $submissionId,
            'user_id'       => $submission->user_id,
            'score'         => $avgScore,
        ]);

        return $scoreRecord;
    }
}
