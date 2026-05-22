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

        // Get competition scoring criteria and existing scores for criteria
        $criterias = $competition->scoringCriteria()->get();
        $myCriterionScores = $myScore ? $myScore->criterionScores->keyBy('criterion_id') : collect();

        return view('judge.submissions.score', compact('competition', 'submission', 'myScore', 'allScores', 'criterias', 'myCriterionScores'));
    }

    /**
     * Store/update a judge's score for a submission.
     */
    public function store(Request $request, Competition $competition, Submission $submission)
    {
        $request->validate([
            'criteria'   => 'required|array',
            'criteria.*' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
        ], [
            'criteria.required'   => 'Nilai kriteria wajib diisi.',
            'criteria.*.required' => 'Nilai kriteria wajib diisi.',
            'criteria.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'criteria.*.min'      => 'Nilai kriteria minimal 0.',
        ]);

        $user = auth()->user();

        // Use ScoreProxy (Proxy Pattern) to enforce authorization + delegate
        $proxy = new ScoreProxy(new ScoringService());

        try {
            $scoreRecord = $proxy->submitScore(
                $submission->id,
                $user->id,
                $request->input('criteria', []),
                $request->notes
            );
        } catch (UnauthorizedJudgeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('judge.submissions.round', [$competition, $submission->round_id])
            ->with('success', "Nilai total {$scoreRecord->score} berhasil disimpan untuk submission #{$submission->id}!");
    }
}
