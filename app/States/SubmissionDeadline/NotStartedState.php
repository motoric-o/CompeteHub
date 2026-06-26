<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class NotStartedState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'not_started',
            label: 'Not Started',
            message: 'Ronde belum dimulai. Tombol submit akan aktif saat waktu mulai sudah tercapai.',
            severity: 'neutral',
            badgeClass: 'bg-gray-50 border-gray-200 text-gray-600',
            panelClass: 'bg-gray-50 border-gray-200 text-gray-700',
            isActionable: false,
        );
    }
}
