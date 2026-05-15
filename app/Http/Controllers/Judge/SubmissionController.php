<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use App\Services\Scoring\ScoringServiceInterface;
use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedJudgeException;

class SubmissionController extends Controller
{
    public function index(Competition $competition)
    {
        $submissions = Submission::where('competition_id', $competition->id)
            ->with(['user', 'team', 'scores' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->get();
            
        return view('judge.submissions.index', compact('competition', 'submissions'));
    }
    
    public function score(Request $request, Competition $competition, Submission $submission, ScoringServiceInterface $scoringService)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);
        
        try {
            $scoringService->submitScore($submission->id, auth()->id(), $request->score, $request->notes);
            return back()->with('success', 'Berhasil memberikan penilaian.');
        } catch (UnauthorizedJudgeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
