<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use Illuminate\Http\Request;

class MyCompetitionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $teamIds = collect($user->teams)->pluck('id')->toArray();
        $captainedTeamIds = $user->captainedTeams()->pluck('id')->toArray();
        $allTeamIds = array_unique(array_merge($teamIds, $captainedTeamIds));

        // Get verified registrations
        $registrations = Registration::where(function($query) use ($user, $allTeamIds) {
                $query->where('user_id', $user->id);
                if (!empty($allTeamIds)) {
                    $query->orWhereIn('team_id', $allTeamIds);
                }
            })
            ->whereIn('status', ['verified', 'payment_ok'])
            ->with('competition')
            ->get();

        return view('participant.my_competitions.index', compact('registrations'));
    }

    public function show(Competition $competition)
    {
        $user = auth()->user();
        
        $teamIds = collect($user->teams)->pluck('id')->toArray();
        $captainedTeamIds = $user->captainedTeams()->pluck('id')->toArray();
        $allTeamIds = array_unique(array_merge($teamIds, $captainedTeamIds));

        $registration = Registration::where('competition_id', $competition->id)
            ->where(function($query) use ($user, $allTeamIds) {
                $query->where('user_id', $user->id);
                if (!empty($allTeamIds)) {
                    $query->orWhereIn('team_id', $allTeamIds);
                }
            })
            ->whereIn('status', ['verified', 'payment_ok'])
            ->first();

        if (!$registration) {
            abort(403, 'You are not actively participating in this competition.');
        }

        $rounds = $competition->rounds()->orderBy('round_order')->get();
        
        // Find active round based on status and dates
        $activeRound = $rounds->firstWhere('status', 'active');
        
        if (!$activeRound) {
            foreach ($rounds as $round) {
                if ($round->status === 'finished') continue;

                $started = !$round->start_date || $round->start_date <= now();
                $ended = $round->end_date && $round->end_date < now();
                
                if ($started && !$ended) {
                    $activeRound = $round;
                    break;
                }
            }
        }

        if (!$activeRound) {
            // Fallback: first round that is not finished
            $activeRound = $rounds->firstWhere('status', '!=', 'finished');
        }

        if (!$activeRound && $rounds->isNotEmpty()) {
            // Fallback: if all are finished, show the last round
            $activeRound = $rounds->last();
        }

        // Fetch submission for active round
        $submission = null;
        if ($activeRound) {
            $submissionQuery = Submission::where('competition_id', $competition->id)
                ->where('round_id', $activeRound->id);
            if ($registration->team_id) {
                $submissionQuery->where('team_id', $registration->team_id);
            } else {
                $submissionQuery->where('user_id', $registration->user_id ?? $user->id);
            }
            $submission = $submissionQuery->first();
        }

        // Fetch bracket info for active round if any
        $bracket = null;
        if ($activeRound && $activeRound->is_bracket) {
            $participantId = $registration->team_id ?? ($registration->user_id ?? $user->id);
            $bracket = \App\Models\Bracket::where('round_id', $activeRound->id)
                ->where(function($query) use ($participantId) {
                    $query->where('participant_a', $participantId)
                          ->orWhere('participant_b', $participantId);
                })->first();
        }

        return view('participant.my_competitions.show', compact('competition', 'registration', 'rounds', 'activeRound', 'submission', 'bracket'));
    }
}
