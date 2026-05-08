<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the committee's competitions.
     */
    public function index(): View
    {
        $competitions = Competition::where('user_id', auth()->id())->latest()->get();

        return view('committee.competitions.index', compact('competitions'));
    }
}
