<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Round;
use App\Models\Bracket;
use App\Core\Competition\BracketManager;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BracketController extends Controller
{
    protected BracketManager $bracketManager;

    public function __construct(BracketManager $bracketManager)
    {
        $this->bracketManager = $bracketManager;
    }

    public function autoGenerate(Competition $competition, Round $round): RedirectResponse
    {
        $this->bracketManager->autoGenerate($round);

        return redirect()->route('committee.rounds.show', [$competition, $round])
                         ->with('success', 'Brackets generated automatically.');
    }

    public function store(Request $request, Competition $competition, Round $round): RedirectResponse
    {
        $validated = $request->validate([
            'participant_a' => 'required|integer',
            'participant_b' => 'nullable|integer',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['participant_type'] = $competition->type === 'team' ? 'team' : 'user';

        $round->brackets()->create($validated);

        return redirect()->route('committee.rounds.show', [$competition, $round])
                         ->with('success', 'Bracket added manually.');
    }

    public function setWinner(Request $request, Competition $competition, Round $round, Bracket $bracket): RedirectResponse
    {
        $validated = $request->validate([
            'winner_id' => 'required|integer',
        ]);

        $bracket->update(['winner_id' => $validated['winner_id']]);

        return redirect()->route('committee.rounds.show', [$competition, $round])
                         ->with('success', 'Winner set successfully.');
    }

    public function destroy(Competition $competition, Round $round, Bracket $bracket): RedirectResponse
    {
        $bracket->delete();

        return redirect()->route('committee.rounds.show', [$competition, $round])
                         ->with('success', 'Bracket deleted.');
    }
}
