<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\SubmissionVote;
use App\Models\Registration;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    public function toggle(Request $request, Submission $submission)
    {
        $competition = $submission->competition;
        $round = $submission->round;
        
        if (!$round->scoringType || $round->scoringType->name !== 'Community Voting') {
            return response()->json(['message' => 'Voting is not enabled for this round.'], 403);
        }

        $user = auth()->user();

        // Check if user is registered in this competition
        $isRegistered = Registration::where('competition_id', $competition->id)
            ->where(function($q) use ($user, $competition) {
                if ($competition->isTeamBased()) {
                    $teamIds = $user->teams()->where('competition_id', $competition->id)->pluck('teams.id');
                    $q->whereIn('team_id', $teamIds);
                } else {
                    $q->where('user_id', $user->id);
                }
            })
            ->whereIn('status', ['verified', 'payment_ok'])
            ->exists();

        if (!$isRegistered) {
            return response()->json(['message' => 'Only verified participants can vote.'], 403);
        }

        $vote = SubmissionVote::where('submission_id', $submission->id)
            ->where('user_id', $user->id)
            ->first();

        if ($vote) {
            $vote->delete();
            $action = 'unvoted';
        } else {
            SubmissionVote::create([
                'submission_id' => $submission->id,
                'user_id' => $user->id,
            ]);
            $action = 'voted';
        }

        // Recalculate final_score using ScoringService
        $scoringService = new \App\Services\Scoring\ScoringService();
        $scoringService->updateCommunityVoteScore($submission->id);

        return response()->json([
            'message' => 'Success',
            'action' => $action,
            'votes_count' => $submission->votes()->count()
        ]);
    }
}
