<?php

namespace App\Patterns\Observer;

use App\Models\LeaderboardEntry;
use App\Models\Submission;

class LeaderboardObserver implements ObserverInterface
{
    /**
     * React to events from the ScoringSubject.
     */
    public function update(SubjectInterface $subject, string $event, mixed $data = null): void
    {
        switch ($event) {
            case 'submission_created':
            case 'submission_revised':
            case 'score_published':
                if (isset($data['submission_id'])) {
                    $submission = Submission::find($data['submission_id']);
                    if ($submission) {
                        $this->updateLeaderboard($submission);
                    }
                }
                break;
        }
    }

    /**
     * Update leaderboard entries for a submission's competition+round.
     * total_score = final_score (juri, max 100) + time_bonus (sistem, max 5).
     */
    public function updateLeaderboard(Submission $submission): void
    {
        $competitionId = $submission->competition_id;
        $roundId       = $submission->round_id;

        $submissions = Submission::where('competition_id', $competitionId)
            ->where('round_id', $roundId)->get();

        foreach ($submissions as $sub) {
            $totalScore = ($sub->final_score ?? 0) + ($sub->time_bonus ?? 0);

            LeaderboardEntry::updateOrCreate(
                [
                    'competition_id' => $competitionId,
                    'round_id'       => $roundId,
                    'user_id'        => $sub->user_id,
                    'team_id'        => $sub->team_id,
                ],
                [
                    'total_score'  => $totalScore,
                    'last_updated' => now(),
                ]
            );
        }

        $this->recalculateRanks($competitionId, $roundId);
        $this->updateGlobalLeaderboard($competitionId);
    }

    private function recalculateRanks(int $competitionId, ?int $roundId): void
    {
        $entries = LeaderboardEntry::where('competition_id', $competitionId)
            ->where('round_id', $roundId)
            ->orderByDesc('total_score')->get();

        $rank = 1;
        foreach ($entries as $entry) {
            if ($entry->rank !== $rank) {
                $entry->update([
                    'previous_rank' => $entry->rank,
                    'rank'          => $rank
                ]);
            }
            $rank++;
        }
    }

    private function updateGlobalLeaderboard(int $competitionId): void
    {
        $participants = LeaderboardEntry::where('competition_id', $competitionId)
            ->whereNotNull('round_id')
            ->select('user_id', 'team_id')
            ->distinct()->get();

        foreach ($participants as $p) {
            $total = LeaderboardEntry::where('competition_id', $competitionId)
                ->whereNotNull('round_id')
                ->where('user_id', $p->user_id)
                ->where('team_id', $p->team_id)
                ->sum('total_score');

            LeaderboardEntry::updateOrCreate(
                [
                    'competition_id' => $competitionId,
                    'round_id'       => null,
                    'user_id'        => $p->user_id,
                    'team_id'        => $p->team_id,
                ],
                [
                    'total_score'  => $total,
                    'last_updated' => now(),
                ]
            );
        }

        $this->recalculateRanks($competitionId, null);
    }
}
