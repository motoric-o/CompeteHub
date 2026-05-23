<?php

namespace App\Http\Controllers\Committee\Concerns;

use App\Models\Competition;

/**
 * CommitteeAuthorization — shared authorization logic for all committee controllers.
 *
 * Previously copy-pasted as `authorizeCommittee()` in:
 *   - FormTemplateController
 *   - RegistrationVerificationController
 *   - (implicit) CompetitionController
 *
 * Extracted to eliminate duplication and provide a single place to modify
 * authorization logic (e.g., when moving to Policies or multi-committee support).
 */
trait CommitteeAuthorization
{
    /**
     * Abort with 403 if the authenticated user is not the committee owner
     * of the given competition.
     */
    protected function authorizeCommittee(Competition $competition): void
    {
        abort_unless(
            $competition->user_id === auth()->id(),
            403,
            'You are not authorized to manage this competition.'
        );
    }

    /**
     * Return true if the authenticated user owns this competition.
     * Use this for conditional logic (not abort-on-fail).
     */
    protected function isCommitteeOwner(Competition $competition): bool
    {
        return $competition->user_id === auth()->id();
    }
}
