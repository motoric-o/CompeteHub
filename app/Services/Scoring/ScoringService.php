<?php

namespace App\Services\Scoring;

use App\Models\Score;
use App\Models\Submission;
use App\Models\ScoringCriterion;
use App\Models\CriterionScore;
use App\Patterns\Observer\ScoringSubject;
use App\Patterns\Observer\LeaderboardObserver;
use App\Patterns\Observer\EmailNotifierObserver;
use App\Core\Scoring\JudgeBasedScoringStrategy;
use App\Core\Scoring\CommunityVotingScoringStrategy;
use Illuminate\Support\Facades\DB;

class ScoringService implements ScoringServiceInterface
{
    /**
     * Submit (or update) a score for a submission.
     * Triggers Observer pattern to update leaderboard + send notifications.
     */
    public function submitScore(int $submissionId, int $judgeUserId, array $criteriaScores, ?string $notes = null): Score
    {
        return DB::transaction(function () use ($submissionId, $judgeUserId, $criteriaScores, $notes) {
            $submission = Submission::findOrFail($submissionId);
            $round = $submission->round;
            
            // For Judge Score, criteria belong to the round
            $criteria = ScoringCriterion::where('round_id', $round->id)->get()->keyBy('id');

            $totalWeightedScore = 0;
            foreach ($criteria as $criterionId => $criterion) {
                $value = $criteriaScores[$criterionId] ?? 0;
                $totalWeightedScore += $value * $criterion->weight;
            }

            // Upsert: 1 juri 1x per submisi
            $scoreRecord = Score::updateOrCreate(
                [
                    'submission_id' => $submissionId,
                    'user_id'       => $judgeUserId,
                ],
                [
                    'score'     => $totalWeightedScore,
                    'notes'     => $notes,
                    'scored_at' => now(),
                ]
            );

            // Save CriterionScores
            foreach ($criteria as $criterionId => $criterion) {
                $value = $criteriaScores[$criterionId] ?? 0;
                CriterionScore::updateOrCreate(
                    [
                        'score_id'     => $scoreRecord->id,
                        'criterion_id' => $criterionId,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }

            // Recalculate final_score using JudgeBasedScoringStrategy
            $strategy = new JudgeBasedScoringStrategy();
            $judgeScores = $submission->scores()->with('criterionScores.criterion')->get();
            $avgScore = $strategy->calculate($judgeScores);

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
                'team_id'       => $submission->team_id,
                'score'         => $avgScore,
            ]);

            return $scoreRecord;
        });
    }

    /**
     * Recalculates score for community voting.
     */
    public function updateCommunityVoteScore(int $submissionId): float
    {
        $submission = Submission::findOrFail($submissionId);
        $votesCount = $submission->votes()->count();

        $strategy = new CommunityVotingScoringStrategy();
        $finalScore = $strategy->calculate($votesCount);

        $submission->update([
            'final_score' => $finalScore,
        ]);

        // Trigger Observer for Leaderboard
        $subject = new ScoringSubject();
        $subject->attach(new LeaderboardObserver());
        $subject->notify('score_published', [
            'submission_id' => $submissionId,
            'user_id'       => $submission->user_id,
            'team_id'       => $submission->team_id,
            'score'         => $finalScore,
        ]);

        return $finalScore;
    }
}
