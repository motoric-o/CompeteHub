<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\ScoringType;
use App\Factories\CompetitionFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompetitionController extends Controller
{
    public function index(): View
    {
        $competitions = Competition::where('user_id', auth()->id())->latest()->get();

        return view('committee.competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        $scoringTypes = ScoringType::all();
        return view('committee.competitions.create', compact('scoringTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'type' => 'required|in:individual,team',
            'scoring_type_id' => 'required|exists:scoring_types,id',
            'time_scoring_threshold' => 'nullable|numeric|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'status' => 'nullable|in:draft,open,ongoing,finished',
            'rules' => 'nullable|string',
        ]);

        CompetitionFactory::create($validated);

        return redirect()->route('committee.management.competitions.index');
    }

    public function edit(Competition $competition): View
    {
        $scoringTypes = ScoringType::all();
        return view('committee.competitions.edit', compact('competition', 'scoringTypes'));
    }

    public function update(Request $request, Competition $competition): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'type' => 'required|in:individual,team',
            'scoring_type_id' => 'required|exists:scoring_types,id',
            'time_scoring_threshold' => 'nullable|numeric|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'status' => 'nullable|in:draft,open,ongoing,finished',
            'rules' => 'nullable|string',
        ]);

        $competition->update($validated);

        return redirect()->route('committee.management.competitions.index');
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        $competition->delete();

        return redirect()->route('committee.management.competitions.index');
    }
}
