<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\ContributionStat;
use App\Models\Submission;
use App\Models\Team;

class ContributionStatService
{
    /**
     * Menghitung ulang dan menyimpan statistik kontribusi untuk seluruh anggota tim.
     * Hanya berlaku untuk kompetisi team-based dengan mode 'all_members'.
     */
    public function recalculateForTeam(Team $team, Competition $competition): void
    {
        if (!$competition->isTeamBased() || !$competition->isAllMembersSubmit()) {
            return;
        }

        $members = $team->members;
        
        // Dapatkan total skor final dari tim ini untuk kompetisi tersebut
        // Kita hitung SUM(final_score) dari semua submission valid tim ini
        $teamSubmissions = Submission::where('competition_id', $competition->id)
            ->where('team_id', $team->id)
            ->get();
            
        $teamTotalScore = $teamSubmissions->sum('final_score');

        foreach ($members as $member) {
            $memberSubmissions = $teamSubmissions->where('user_id', $member->id);
            
            $submissionCount = $memberSubmissions->count();
            $avgScore = $submissionCount > 0 ? $memberSubmissions->avg('final_score') : null;
            
            $memberTotalScore = $memberSubmissions->sum('final_score');
            
            $contributionPct = null;
            if ($teamTotalScore > 0) {
                $contributionPct = ($memberTotalScore / $teamTotalScore) * 100;
            } elseif ($teamTotalScore == 0 && $submissionCount > 0) {
                // Kasus jika skor masih 0 semua tapi ada submission, bagi rata atau set ke nilai tertentu
                // Untuk kesederhanaan, kita bisa set null atau biarkan logika di atas (hasilnya null/0)
                $contributionPct = 0;
            }

            ContributionStat::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'user_id' => $member->id,
                    'competition_id' => $competition->id,
                ],
                [
                    'submission_count' => $submissionCount,
                    'avg_score' => $avgScore,
                    'contribution_pct' => $contributionPct,
                    'last_updated' => now(),
                ]
            );
        }
    }

    /**
     * Mengambil statistik kontribusi tim untuk ditampilkan di UI.
     */
    public function getStatsForTeam(Team $team, Competition $competition)
    {
        // Pastikan recalculate dulu agar data up to date
        $this->recalculateForTeam($team, $competition);
        
        return ContributionStat::where('team_id', $team->id)
            ->where('competition_id', $competition->id)
            ->with('user')
            ->get();
    }
}
