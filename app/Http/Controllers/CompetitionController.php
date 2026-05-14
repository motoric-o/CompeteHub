<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Core\Competition\CompetitionFactory;

class CompetitionController extends Controller
{
    public function index() {
        $competitions = Competition::all();

        return view('competition.index', compact('competitions'));
    }

    public function create() {
        return view('competition.create');
    }

    public function store(Request $request) {
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
            'user_id' => 'required|exists:users,id',
        ]);

        Competition::create($validated);

        return redirect()->route('competitions.index');
    }

    public function show(Competition $competition) {
        $competitionCore = CompetitionFactory::make($competition);
        
        $isRegistrationOpen = $competitionCore->isRegistrationOpen();
        $isActive = $competitionCore->isActive();
        $leaderboard = $competitionCore->getLeaderboard();
        $rules = $competitionCore->getRules();

        return view('competition.show', compact(
            'competition',
            'isRegistrationOpen',
            'isActive',
            'leaderboard',
            'rules'
        ));
    }

    public function edit(Competition $competition) {
        return view('competition.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition) {
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
            'user_id' => 'required|exists:users,id',
        ]);

        $competition->update($validated);

        return redirect()->route('competitions.index');
    }

    public function destroy(Competition $competition) {
        $competition->delete();

        return redirect()->route('competitions.index');
    }
}
