<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

interface SubmissionDeadlineState
{
    public function card(Round $round, ?Submission $submission = null): DeadlineRiskCard;
}