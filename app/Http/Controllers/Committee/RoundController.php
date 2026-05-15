<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Round;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoundController extends Controller
{
    public function index(Competition $competition): View
    {
        $rounds = $competition->rounds()->orderBy('round_order')->get();
        return view('committee.rounds.index', compact('competition', 'rounds'));
    }

    public function create(Competition $competition): View
    {
        return view('committee.rounds.create', compact('competition'));
    }

    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'round_order' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,active,finished',
        ]);

        $competition->rounds()->create($validated);

        return redirect()->route('committee.rounds.index', $competition)
                         ->with('success', 'Round created successfully.');
    }

    public function show(Competition $competition, Round $round): View
    {
        $round->load('brackets');
        
        $participants = [];
        $registrations = \App\Models\Registration::where('competition_id', $competition->id)
            ->where('status', 'payment_ok')
            ->get();
            
        if ($competition->type === 'team') {
            $participants = \App\Models\Team::whereIn('id', $registrations->pluck('team_id'))->get();
        } else {
            $participants = \App\Models\User::whereIn('id', $registrations->pluck('user_id'))->get();
        }

        return view('committee.rounds.show', compact('competition', 'round', 'participants'));
    }

    public function edit(Competition $competition, Round $round): View
    {
        return view('committee.rounds.edit', compact('competition', 'round'));
    }

    public function update(Request $request, Competition $competition, Round $round): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'round_order' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,active,finished',
        ]);

        $round->update($validated);

        return redirect()->route('committee.rounds.index', $competition)
                         ->with('success', 'Round updated successfully.');
    }

    public function destroy(Competition $competition, Round $round): RedirectResponse
    {
        $round->delete();

        return redirect()->route('committee.rounds.index', $competition)
                         ->with('success', 'Round deleted successfully.');
    }
}
