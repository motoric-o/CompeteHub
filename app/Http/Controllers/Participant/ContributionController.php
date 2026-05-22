<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ContributionStat;
use App\Models\Submission;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * ContributionController — Dashboard statistik kontribusi anggota tim.
 *
 * Menampilkan metrik individual tiap anggota: jumlah submisi,
 * rata-rata skor, persentase kontribusi terhadap skor tim, dan waktu aktif.
 * Mendukung real-time polling via JSON API endpoint.
 */
class ContributionController extends Controller
{
    /**
     * Helper: Menghitung waktu aktif dalam string (misal: "2 hari, 3 jam").
     */
    private function getActiveTime(Carbon $joinedAt): string
    {
        $diff = $joinedAt->diffAsCarbonInterval(now());
        if ($diff->days > 0) {
            return $diff->days . ' hari, ' . $diff->hours . ' jam';
        }
        if ($diff->hours > 0) {
            return $diff->hours . ' jam, ' . $diff->minutes . ' mnt';
        }
        return $diff->minutes . ' menit';
    }

    /**
     * Tampilkan halaman dashboard kontribusi tim.
     */
    public function show(Request $request, Team $team): View
    {
        $user = $request->user();

        // Guard: hanya anggota tim yang boleh melihat
        if (! $team->hasMember($user)) {
            abort(403, 'Anda bukan anggota tim ini.');
        }

        // Guard: Hanya aktif jika kompetisi adalah tipe "all_members"
        if (!$team->competition->isAllMembersSubmission()) {
            abort(404, 'Fitur statistik kontribusi tidak tersedia untuk format kompetisi ini.');
        }

        $team->load(['competition', 'captain', 'members']);

        // Data statistik kontribusi (db)
        $stats = ContributionStat::where('team_id', $team->id)
            ->where('competition_id', $team->competition_id)
            ->with('user')
            ->get();

        // Gabungkan data waktu aktif dari tabel pivot (team_members)
        $memberData = [];
        foreach ($team->members as $member) {
            $memberStat = $stats->firstWhere('user_id', $member->id);
            $pivot = $member->pivot; // joined_at
            
            $memberData[] = (object) [
                'user_id'          => $member->id,
                'user'             => $member,
                'submission_count' => $memberStat->submission_count ?? 0,
                'avg_score'        => $memberStat->avg_score ?? null,
                'contribution_pct' => $memberStat->contribution_pct ?? 0,
                'last_updated'     => $memberStat->last_updated ?? null,
                'active_time'      => $this->getActiveTime(Carbon::parse($pivot->joined_at)),
            ];
        }

        $stats = collect($memberData);

        // Summary metrics
        $totalSubmissions = $stats->sum('submission_count');
        $teamAvgScore = $stats->whereNotNull('avg_score')->avg('avg_score');
        $memberCount = $team->members->count();

        return view('teams.contribution', compact(
            'team',
            'stats',
            'totalSubmissions',
            'teamAvgScore',
            'memberCount',
        ));
    }

    /**
     * JSON API endpoint untuk real-time polling kontribusi.
     */
    public function apiData(Request $request, Team $team): JsonResponse
    {
        $user = $request->user();

        if (! $team->hasMember($user)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if (!$team->competition->isAllMembersSubmission()) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        $statsRaw = ContributionStat::where('team_id', $team->id)
            ->where('competition_id', $team->competition_id)
            ->with('user:id,name,avatar_url')
            ->get();

        $stats = $team->members->map(function ($member) use ($statsRaw, $team) {
            $stat = $statsRaw->firstWhere('user_id', $member->id);
            return [
                'user_id'          => $member->id,
                'name'             => $member->name ?? 'Unknown',
                'avatar'           => $member->avatar_url ?? null,
                'is_captain'       => $member->id === $team->user_id,
                'submission_count' => (int) ($stat->submission_count ?? 0),
                'avg_score'        => isset($stat->avg_score) ? (float) $stat->avg_score : null,
                'contribution_pct' => isset($stat->contribution_pct) ? (float) $stat->contribution_pct : 0,
                'last_updated'     => $stat->last_updated?->diffForHumans() ?? 'Belum ada',
                'active_time'      => $this->getActiveTime(Carbon::parse($member->pivot->joined_at)),
            ];
        });

        $totalSubmissions = $stats->sum('submission_count');
        $teamAvgScoreRaw = $stats->filter(fn($s) => $s['avg_score'] !== null);
        $teamAvgScore = $teamAvgScoreRaw->count() > 0 ? $teamAvgScoreRaw->avg('avg_score') : null;

        return response()->json([
            'stats'             => $stats->values(),
            'total_submissions' => $totalSubmissions,
            'team_avg_score'    => $teamAvgScore ? round($teamAvgScore, 2) : null,
            'member_count'      => $team->members()->count(),
            'timestamp'         => now()->toIso8601String(),
        ]);
    }
}
