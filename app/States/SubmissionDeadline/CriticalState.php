<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class CriticalState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'critical',
            label: 'Critical',
            message: 'Deadline kurang dari 3 jam. Submission perlu segera dikirim agar tidak melewati batas waktu.',
            severity: 'danger',
            badgeClass: 'bg-red-50 border-red-200 text-red-700',
            panelClass: 'bg-red-50 border-red-200 text-red-800',
            isActionable: true,
        );
    }
}