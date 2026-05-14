<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    /**
     * Display a listing of open competitions for participants.
     */
    public function index(): View
    {
        // Show only open or ongoing competitions
        $competitions = Competition::whereIn('status', ['open', 'ongoing'])
            ->latest()
            ->get();

        return view('participant.competitions.index', compact('competitions'));
    }
}
