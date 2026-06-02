<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class WarningState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'warning',
            label: 'Warning',
            message: 'Deadline kurang dari 24 jam. Submission sebaiknya segera dikirim.',
            severity: 'warning',
            badgeClass: 'bg-yellow-50 border-yellow-200 text-yellow-700',
            panelClass: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            isActionable: true,
        );
    }
}