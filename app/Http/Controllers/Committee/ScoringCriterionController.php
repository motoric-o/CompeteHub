<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\ScoringCriterion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ScoringCriterionController extends Controller
{
    public function index(Competition $competition): View
    {
        // Ensure competition belongs to this committee
        if ($competition->user_id !== auth()->id()) {
            abort(403);
        }

        $criteria = $competition->scoringCriteria()->get();

        return view('committee.competitions.scoring-criteria.index', compact('competition', 'criteria'));
    }

    public function create(Competition $competition): View
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403);
        }

        $rounds = $competition->rounds()->whereHas('scoringType', function($q) {
            $q->where('name', 'Judge Score');
        })->get();

        return view('committee.competitions.scoring-criteria.create', compact('competition', 'rounds'));
    }

    public function store(Request $request, Competition $competition): RedirectResponse
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'round_id' => 'required|exists:rounds,id',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0.01',
        ]);

        ScoringCriterion::create($validated);

        return redirect()->route('committee.scoring-criteria.index', $competition)
            ->with('success', 'Kriteria penilaian berhasil ditambahkan.');
    }

    public function edit(Competition $competition, ScoringCriterion $scoringCriterion): View
    {
        if ($competition->user_id !== auth()->id() || $scoringCriterion->round->competition_id !== $competition->id) {
            abort(403);
        }

        $rounds = $competition->rounds()->whereHas('scoringType', function($q) {
            $q->where('name', 'Judge Score');
        })->get();

        return view('committee.competitions.scoring-criteria.edit', compact('competition', 'scoringCriterion', 'rounds'));
    }

    public function update(Request $request, Competition $competition, ScoringCriterion $scoringCriterion): RedirectResponse
    {
        if ($competition->user_id !== auth()->id() || $scoringCriterion->round->competition_id !== $competition->id) {
            abort(403);
        }

        $validated = $request->validate([
            'round_id' => 'required|exists:rounds,id',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0.01',
        ]);

        $scoringCriterion->update($validated);

        return redirect()->route('committee.scoring-criteria.index', $competition)
            ->with('success', 'Kriteria penilaian berhasil diperbarui.');
    }

    public function destroy(Competition $competition, ScoringCriterion $scoringCriterion): RedirectResponse
    {
        if ($competition->user_id !== auth()->id() || $scoringCriterion->round->competition_id !== $competition->id) {
            abort(403);
        }

        $scoringCriterion->delete();

        return redirect()->route('committee.scoring-criteria.index', $competition)
            ->with('success', 'Kriteria penilaian berhasil dihapus.');
    }
}
