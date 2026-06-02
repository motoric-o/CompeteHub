<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class NoDeadlineState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'no_deadline',
            label: 'No Deadline',
            message: 'Ronde ini belum memiliki deadline. Peserta tetap bisa submit selama ronde aktif.',
            severity: 'neutral',
            badgeClass: 'bg-gray-50 border-gray-200 text-gray-600',
            panelClass: 'bg-gray-50 border-gray-200 text-gray-700',
            isActionable: true,
        );
    }
}