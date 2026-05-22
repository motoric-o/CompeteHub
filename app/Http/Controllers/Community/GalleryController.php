<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function list()
    {
        $rounds = \App\Models\Round::whereHas('scoringType', function($q) {
                $q->where('name', 'Community Voting');
            })
            ->whereHas('competition', function($q) {
                $q->whereIn('status', ['open', 'ongoing']);
            })
            ->with('competition')
            ->get();
            
        return view('community.gallery_list', compact('rounds'));
    }

    public function index(Competition $competition, \App\Models\Round $round)
    {
        if (!$round->scoringType || $round->scoringType->name !== 'Community Voting') {
            abort(403, 'Community voting is not enabled for this round.');
        }

        $submissions = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->with(['user', 'team'])
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        return view('community.gallery', compact('competition', 'round', 'submissions'));
    }
}
