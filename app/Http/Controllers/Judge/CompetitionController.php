<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\JuryAssignment;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $assignments = JuryAssignment::where('user_id', auth()->id())
            ->with('competition')
            ->get();
            
        return view('judge.competitions.index', compact('assignments'));
    }
}
