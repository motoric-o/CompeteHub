<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class MissedState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'missed',
            label: 'Missed',
            message: 'Deadline sudah lewat dan belum ada submission untuk ronde ini.',
            severity: 'danger',
            badgeClass: 'bg-red-50 border-red-200 text-red-700',
            panelClass: 'bg-red-50 border-red-200 text-red-800',
            isActionable: false,
        );
    }
}