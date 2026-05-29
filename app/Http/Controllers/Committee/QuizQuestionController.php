<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Round;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizQuestionController extends Controller
{
    public function index(Competition $competition, Round $round): View
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $questions = $round->quizQuestions()->orderBy('id')->get();

        return view('committee.rounds.questions.index', compact('competition', 'round', 'questions'));
    }

    public function store(Request $request, Competition $competition, Round $round): RedirectResponse
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,essay',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'points' => 'required|integer|min:1',
        ]);

        $validated['round_id'] = $round->id;

        QuizQuestion::create($validated);

        return redirect()->route('committee.rounds.questions.index', [$competition, $round])
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function update(Request $request, Competition $competition, Round $round, QuizQuestion $question): RedirectResponse
    {
        if ($round->competition_id !== $competition->id || $question->round_id !== $round->id) {
            abort(404);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,essay',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'points' => 'required|integer|min:1',
        ]);

        $question->update($validated);

        return redirect()->route('committee.rounds.questions.index', [$competition, $round])
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Competition $competition, Round $round, QuizQuestion $question): RedirectResponse
    {
        if ($round->competition_id !== $competition->id || $question->round_id !== $round->id) {
            abort(404);
        }

        $question->delete();

        return redirect()->route('committee.rounds.questions.index', [$competition, $round])
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
