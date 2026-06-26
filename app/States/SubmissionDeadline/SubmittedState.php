<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class SubmittedState implements SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        return new DeadlineRiskCard(
            state: 'submitted',
            label: 'Submitted',
            message: 'Submission sudah masuk. Revisi masih bisa dilakukan selama ronde masih dibuka dan limit revisi belum tercapai.',
            severity: 'success',
            badgeClass: 'bg-green-50 border-green-200 text-green-700',
            panelClass: 'bg-green-50 border-green-200 text-green-800',
            isActionable: false,
        );
    }
}