<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\LeaderboardEntry;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Show list of all available leaderboards.
     */
    public function list()
    {
        $competitions = Competition::orderBy('created_at', 'desc')->get();
        return view('leaderboard.list', compact('competitions'));
    }

    /**
     * Show the leaderboard page for a competition.
     */
    public function index(Competition $competition)
    {
        $rounds = $competition->rounds()->orderBy('round_order')->get();

        $globalEntries = LeaderboardEntry::where('competition_id', $competition->id)
            ->whereNull('round_id')
            ->orderBy('rank')
            ->with(['user', 'team'])
            ->get();

        return view('leaderboard.index', compact('competition', 'rounds', 'globalEntries'));
    }

    /**
     * API endpoint: JSON data for real-time polling.
     */
    public function apiData(Request $request, Competition $competition)
    {
        $roundId = $request->query('round_id');

        $query = LeaderboardEntry::where('competition_id', $competition->id)
            ->with(['user:id,name,avatar_url', 'team:id,name']);

        if ($roundId) {
            $query->where('round_id', $roundId);
        } else {
            $query->whereNull('round_id');
        }

        $entries = $query->orderBy('rank')->get()->map(function ($entry) {
            // Get the submission to show score breakdown
            $submission = \App\Models\Submission::where('competition_id', $entry->competition_id)
                ->where('round_id', $entry->round_id ?? \App\Models\Round::where('competition_id', $entry->competition_id)->value('id'))
                ->where(function ($q) use ($entry) {
                    if ($entry->team_id) {
                        $q->where('team_id', $entry->team_id);
                    } else {
                        $q->where('user_id', $entry->user_id);
                    }
                })->first();

            return [
                'id'           => $entry->id,
                'rank'         => $entry->rank,
                'previous_rank'=> $entry->previous_rank,
                'name'         => $entry->team ? $entry->team->name : ($entry->user ? $entry->user->name : 'Unknown'),
                'avatar'       => $entry->user?->avatar_url,
                'total_score'  => (float) $entry->total_score,
                'judge_score'  => $submission ? (float) ($submission->final_score ?? 0) : 0,
                'time_bonus'   => $submission ? (float) ($submission->time_bonus ?? 0) : 0,
                'type'         => $entry->team_id ? 'team' : 'individual',
                'last_updated' => $entry->last_updated?->diffForHumans(),
            ];
        });

        return response()->json([
            'entries'   => $entries,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
