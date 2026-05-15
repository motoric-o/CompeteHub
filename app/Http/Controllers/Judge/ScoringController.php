<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\JuryAssignment;
use App\Models\Submission;
use App\Services\Scoring\ScoreProxy;
use App\Services\Scoring\ScoringService;
use App\Exceptions\UnauthorizedJudgeException;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    /**
     * List all competitions assigned to this judge.
     */
    public function index()
    {
        $user = auth()->user();

        $assignments = JuryAssignment::where('user_id', $user->id)
            ->with('competition.rounds')
            ->get();

        return view('judge.submissions.index', compact('assignments'));
    }

    /**
     * Show all submissions for a specific competition round, for the judge to score.
     */
    public function round(Competition $competition, \App\Models\Round $round)
    {
        $user = auth()->user();

        // Check judge is assigned
        $isAssigned = JuryAssignment::where('user_id', $user->id)
            ->where('competition_id', $competition->id)->exists();
        if (!$isAssigned) {
            abort(403, 'You are not assigned to this competition.');
        }

        $submissions = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->with(['user', 'team', 'scores' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->orderBy('submitted_at', 'asc')
            ->get();

        return view('judge.submissions.round', compact('competition', 'round', 'submissions'));
    }

    /**
     * Show the scoring form for a specific submission.
     */
    public function show(Competition $competition, Submission $submission)
    {
        $user = auth()->user();

        $isAssigned = JuryAssignment::where('user_id', $user->id)
            ->where('competition_id', $competition->id)->exists();
        if (!$isAssigned) {
            abort(403, 'You are not assigned to this competition.');
        }

        // Get this judge's existing score for this submission
        $myScore = $submission->scores()->where('user_id', $user->id)->first();

        // Get all judge scores for this submission (for transparency)
        $allScores = $submission->scores()->with('judge')->get();

        return view('judge.submissions.score', compact('competition', 'submission', 'myScore', 'allScores'));
    }

    /**
     * Store/update a judge's score for a submission.
     */
    public function store(Request $request, Competition $competition, Submission $submission)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ], [
            'score.required' => 'Nilai wajib diisi.',
            'score.min'      => 'Nilai minimal 0.',
            'score.max'      => 'Nilai maksimal 100.',
        ]);

        $user = auth()->user();

        // Use ScoreProxy (Proxy Pattern) to enforce authorization + delegate
        $proxy = new ScoreProxy(new ScoringService());

        try {
            $proxy->submitScore(
                $submission->id,
                $user->id,
                (float) $request->score,
                $request->notes
            );
        } catch (UnauthorizedJudgeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('judge.submissions.round', [$competition, $submission->round_id])
            ->with('success', "Nilai {$request->score} berhasil disimpan untuk submission #{$submission->id}!");
    }
}
