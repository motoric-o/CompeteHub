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
            'criteria' => 'required|array',
            'criteria.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        try {
            $scoringService->submitScore($submission->id, auth()->id(), $request->input('criteria', []), $request->notes);
            return back()->with('success', 'Berhasil memberikan penilaian.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
