<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Team;
use App\Services\ContributionStatService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    private ContributionStatService $contributionService;

    public function __construct(ContributionStatService $contributionService)
    {
        $this->contributionService = $contributionService;
    }

    public function show(Request $request, Competition $competition, Team $team)
    {
        $user = $request->user();

        // Validasi: Kompetisi harus milik tim ini
        if ($team->competition_id !== $competition->id) {
            abort(404);
        }

        // Validasi: Harus all_members submit
        if (!$competition->isAllMembersSubmit()) {
            abort(403, 'Kompetisi ini tidak mendukung statistik kontribusi.');
        }

        // Validasi akses: User harus panitia kompetisi atau anggota tim tersebut
        $isCommittee = $user->isCommittee() && $competition->user_id === $user->id;
        $isMember = $team->hasMember($user);

        if (!$isCommittee && !$isMember) {
            abort(403, 'Anda tidak memiliki akses untuk melihat statistik tim ini.');
        }

        $stats = $this->contributionService->getStatsForTeam($team, $competition);

        // Load relasi user untuk view
        $team->load('captain');

        // Untuk view kita kumpulkan summary
        $totalSubmissions = $stats->sum('submission_count');
        
        // Total skor tim dihitung dari akumulasi seluruh submission valid
        $totalTeamScore = \App\Models\Submission::where('competition_id', $competition->id)
            ->where('team_id', $team->id)
            ->sum('final_score');
            
        $activeMembers = $stats->filter(fn($stat) => $stat->submission_count > 0);
        $activeMembersCount = $activeMembers->count();

        return view('participant.contributions.show', compact(
            'competition',
            'team',
            'stats',
            'totalSubmissions',
            'totalTeamScore',
            'activeMembersCount',
            'isCommittee'
        ));
    }
}
