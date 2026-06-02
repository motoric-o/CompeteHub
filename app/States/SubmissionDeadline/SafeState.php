<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class SafeState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'safe',
            label: 'Safe',
            message: 'Deadline masih aman. Peserta tetap disarankan submit lebih awal agar masih punya waktu revisi.',
            severity: 'success',
            badgeClass: 'bg-green-50 border-green-200 text-green-700',
            panelClass: 'bg-green-50 border-green-200 text-green-800',
            isActionable: true,
        );
    }
}