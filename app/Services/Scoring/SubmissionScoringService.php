<?php

namespace App\Services\Scoring;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Round;
use App\Models\Submission;

class SubmissionScoringService
{

    const MAX_TIME_BONUS = 5.0;
    const MIN_TIME_BONUS = 1.0;
    const MAX_REVISIONS = 2;

    /**
     * Hitung ulang bonus waktu untuk semua submission pada kompetisi dan ronde tertentu.
     *
     * Logic:
     * 1. Hitung jumlah seluruh peserta yang terdaftar sebagai totalRegistrants.
     * 2. Tentukan batas penerima bonus dengan rumus ceil(totalRegistrants / 3), sehingga hanya 1/3 peserta tercepat yang mendapat bonus.
     * 3. Urutkan submission berdasarkan submitted_at dari yang paling awal.
     * 4. Peserta dengan peringkat 1 sampai batas penerima bonus mendapat bonus secara bertahap dari 5 hingga 1.
     * 5. Peserta di luar batas tersebut mendapat bonus 0.
     * 6. Tidak ada pengurangan bonus karena revisi. Bonus tetap sama meskipun submission mengalami revisi.
     */
    public function recalculateAllTimeBonuses(Competition $competition, Round $round): array
    {
        $totalRegistrants = Registration::where('competition_id', $competition->id)
            ->whereIn('status', ['verified', 'payment_ok', 'documents_ok', 'account_ok'])
            ->count();

        if ($totalRegistrants === 0) {
            return [];
        }

        $threshold = max((int) ceil($totalRegistrants / 3), 1);

        $submissions = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->orderBy('submitted_at', 'asc')
            ->get();

        $updated = [];
        $rank = 1;

        foreach ($submissions as $sub) {
            if ($rank <= $threshold) {
                $baseBonus = ($threshold === 1)
                    ? self::MAX_TIME_BONUS
                    : self::MAX_TIME_BONUS - (($rank - 1) / ($threshold - 1)) * (self::MAX_TIME_BONUS - self::MIN_TIME_BONUS);
            } else {
                $baseBonus = 0;
            }

            $effectiveBonus = round($baseBonus, 2);
            $sub->update(['time_bonus' => $effectiveBonus]);
            $updated[] = $sub;
            $rank++;
        }

        return $updated;
    }

    public function previewNextTimeBonus(
        Competition $competition,
        Round $round,
        ?Submission $existingSubmission
    ): array {
        $totalRegistrants = Registration::where('competition_id', $competition->id)
            ->whereIn('status', ['verified', 'payment_ok', 'documents_ok', 'account_ok'])
            ->count();
        $threshold = max((int) ceil($totalRegistrants / 3), 1);
        $currentSubmissions = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)->count();

        if (!$existingSubmission) {
            $nextRank = $currentSubmissions + 1;
            $estimatedBonus = ($nextRank <= $threshold)
                ? (($threshold === 1)
                    ? self::MAX_TIME_BONUS
                    : self::MAX_TIME_BONUS - (($nextRank - 1) / ($threshold - 1)) * (self::MAX_TIME_BONUS - self::MIN_TIME_BONUS))
                : 0;

            return [
                'is_revision'       => false,
                'current_bonus'     => 0,
                'next_bonus'        => round($estimatedBonus, 2),
                'revision_count'    => 0,
                'revisions_left'    => self::MAX_REVISIONS,
                'total_registrants' => $totalRegistrants,
                'threshold'         => $threshold,
                'max_time_bonus'    => self::MAX_TIME_BONUS,
                'max_revisions'     => self::MAX_REVISIONS,
            ];
        }

        $currentBonus = $existingSubmission->time_bonus ?? 0;
        $revisionsUsed = $existingSubmission->revision_count;
        $revisionsLeft = max(self::MAX_REVISIONS - $revisionsUsed, 0);

        return [
            'is_revision'       => true,
            'current_bonus'     => $currentBonus,
            'next_bonus'        => $currentBonus, 
            'revision_count'    => $revisionsUsed + 1,
            'revisions_left'    => $revisionsLeft,
            'total_registrants' => $totalRegistrants,
            'threshold'         => $threshold,
            'max_time_bonus'    => self::MAX_TIME_BONUS,
            'max_revisions'     => self::MAX_REVISIONS,
        ];
    }
}
