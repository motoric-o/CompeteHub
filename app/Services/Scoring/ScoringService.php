<?php

namespace App\Services\Scoring;

use App\Models\Score;
use App\Models\Submission;
use App\Models\ScoringCriterion;
use App\Models\CriterionScore;
use App\Patterns\Observer\ScoringSubject;
use App\Patterns\Observer\LeaderboardObserver;
use App\Patterns\Observer\EmailNotifierObserver;
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
            $competition = $submission->competition;
            $round = $submission->round;
            
            if ($competition->isQuiz()) {
                $totalScore = 0;
                $answers = $submission->quizAnswers()->with('question')->get();
                
                foreach ($answers as $answer) {
                    if ($answer->question->question_type === 'essay') {
                        $essayScore = (float) ($criteriaScores[$answer->id] ?? 0.0);
                        $answer->update([
                            'score' => $essayScore,
                            'is_correct' => $essayScore > 0,
                        ]);
                        $totalScore += $essayScore;
                    } else {
                        $totalScore += (float) ($answer->score ?? 0.0);
                    }
                }

                $scoreRecord = Score::updateOrCreate(
                    [
                        'submission_id' => $submissionId,
                        'user_id'       => $judgeUserId,
                    ],
                    [
                        'score'     => $totalScore,
                        'notes'     => $notes,
                        'scored_at' => now(),
                    ]
                );

                $avgScore = $submission->fresh()->scores()->avg('score') ?? $totalScore;

                $submission->update([
                    'final_score' => $avgScore,
                    'status'      => 'scored',
                ]);

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
            }

            // For Judge Score, criteria belong to the round
            $criteria = ScoringCriterion::where('competition_id', $competition->id)->get()->keyBy('id');

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
        });
    }
}
