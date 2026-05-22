<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class PublicCompetitionController extends Controller
{
    public function show(Competition $competition)
    {
        $competition->load(['rounds.scoringType', 'creator']);
        
        return view('competitions.show', compact('competition'));
    }
}
