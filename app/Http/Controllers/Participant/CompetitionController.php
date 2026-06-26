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
    public function index(Request $request): View
    {
        $query = Competition::whereIn('status', ['open', 'ongoing']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $competitions = $query->latest()->get();

        return view('participant.competitions.index', compact('competitions'));
    }
}
