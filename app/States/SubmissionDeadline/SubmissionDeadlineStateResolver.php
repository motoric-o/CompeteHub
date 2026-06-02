<?php

namespace App\States\SubmissionDeadline;

use App\Models\Round;
use App\Models\Submission;

class SubmissionDeadlineStateResolver
{
    public function resolve(Round $round, ?Submission $submission = null): DeadlineRiskCard
    {
        if ($submission) {
            return app(SubmittedState::class)->card($round, $submission);
        }

        if ($round->start_date && now()->lt($round->start_date)) {
            return app(NotStartedState::class)->card($round, $submission);
        }

        if (! $round->end_date) {
            return app(NoDeadlineState::class)->card($round, $submission);
        }

        if (now()->gt($round->end_date)) {
            return app(MissedState::class)->card($round, $submission);
        }

        $hoursLeft = now()->diffInMinutes($round->end_date, false) / 60;

        if ($hoursLeft <= 3) {
            return app(CriticalState::class)->card($round, $submission);
        }

        if ($hoursLeft <= 24) {
            return app(WarningState::class)->card($round, $submission);
        }

        return app(SafeState::class)->card($round, $submission);
    }
}
