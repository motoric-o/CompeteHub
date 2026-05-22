<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\ContributionStat;
use App\Models\Submission;
use App\Models\Team;

/**
 * ContributionService — Menghitung statistik kontribusi tiap anggota tim.
 *
 * Mengkalkulasi submission_count, avg_score, dan contribution_pct.
 * Hanya relevan untuk kompetisi dengan mode submission "all_members".
 */
class ContributionService
{
    /**
     * Recalculate contribution stats untuk seluruh anggota sebuah tim
     * dalam konteks kompetisi tertentu.
     */
    public function recalculate(Team $team, Competition $competition): void
    {
        // Fitur ini hanya berlaku jika kompetisi mengizinkan semua anggota submit
        if (!$competition->isAllMembersSubmission()) {
            return;
        }

        $members = $team->members;

        // Hitung total skor tim dari semua submisi yang sudah dinilai
        $teamTotalScore = Submission::where('team_id', $team->id)
            ->where('competition_id', $competition->id)
            ->whereNotNull('final_score')
            ->sum('final_score');

        foreach ($members as $member) {
            // Submisi yang dikirim oleh anggota ini untuk tim ini
            $memberSubmissions = Submission::where('team_id', $team->id)
                ->where('competition_id', $competition->id)
                ->where('user_id', $member->id)
                ->get();

            $submissionCount = $memberSubmissions->count();

            // Rata-rata skor hanya dari submisi yang sudah dinilai
            $scoredSubmissions = $memberSubmissions->whereNotNull('final_score');
            $avgScore = $scoredSubmissions->count() > 0
                ? round($scoredSubmissions->avg('final_score'), 2)
                : null;

            // Persentase kontribusi = total skor anggota / total skor tim × 100
            $memberTotalScore = $scoredSubmissions->sum('final_score');
            $contributionPct = $teamTotalScore > 0
                ? round(($memberTotalScore / $teamTotalScore) * 100, 2)
                : null;

            ContributionStat::updateOrCreate(
                [
                    'team_id'        => $team->id,
                    'user_id'        => $member->id,
                    'competition_id' => $competition->id,
                ],
                [
                    'submission_count' => $submissionCount,
                    'avg_score'        => $avgScore,
                    'contribution_pct' => $contributionPct,
                    'last_updated'     => now(),
                ]
            );
        }
    }

    /**
     * Recalculate untuk tim berdasarkan submission yang baru saja dinilai/dikirim.
     */
    public function recalculateFromSubmission(Submission $submission): void
    {
        if (! $submission->team_id) {
            return;
        }

        $team = $submission->team;
        $competition = $submission->competition;

        if ($team && $competition) {
            $this->recalculate($team, $competition);
        }
    }
}
