<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Committee\Concerns\CommitteeAuthorization;
use App\Models\Competition;
use App\Models\Registration;
use App\Services\Review\ReviewCommandExecutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * ReviewActionController — Features 7 & 8: One-Click Review Actions.
 *
 * Handles all committee review actions:
 * - Single approve/reject
 * - Bulk validation
 * - Send reminder
 *
 * Each action:
 * 1. Runs through CommitteeAuthorization
 * 2. Validates input
 * 3. Delegates to ReviewCommandExecutor (which handles audit logging)
 * 4. Returns redirect with appropriate feedback
 *
 * Bulk actions require confirmation at the frontend level (modal).
 * Backend safeguards are in ReviewCommandExecutor.
 */
class ReviewActionController extends Controller
{
    use CommitteeAuthorization;

    public function __construct(
        private ReviewCommandExecutor $executor,
    ) {}

    // ── Single Actions ────────────────────────────────────────────────

    /**
     * Approve a single registration.
     * POST /committee/competitions/{competition}/registrations/{registration}/approve
     */
    public function approve(Competition $competition, Registration $registration): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->ensureRegistrationBelongsToCompetition($registration, $competition);

        $result = $this->executor->approveRegistration(
            registration: $registration,
            actorId:      auth()->id(),
        );

        return redirect()
            ->route('committee.registrations.show', [$competition, $registration])
            ->with($result->success ? 'success' : 'error', $result->message);
    }

    /**
     * Reject a single registration with a reason.
     * POST /committee/competitions/{competition}/registrations/{registration}/reject
     */
    public function reject(Request $request, Competition $competition, Registration $registration): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->ensureRegistrationBelongsToCompetition($registration, $competition);

        $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $result = $this->executor->rejectRegistration(
            registration: $registration,
            actorId:      auth()->id(),
            reason:       $request->input('reason'),
        );

        return redirect()
            ->route('committee.registrations.show', [$competition, $registration])
            ->with($result->success ? 'success' : 'error', $result->message);
    }

    /**
     * Send a reminder to a single participant.
     * POST /committee/competitions/{competition}/registrations/{registration}/reminder
     */
    public function sendReminder(Request $request, Competition $competition, Registration $registration): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->ensureRegistrationBelongsToCompetition($registration, $competition);

        $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $result = $this->executor->sendReminder(
            registration: $registration,
            actorId:      auth()->id(),
            message:      $request->input('message'),
        );

        return redirect()
            ->route('committee.registrations.show', [$competition, $registration])
            ->with($result->success ? 'success' : 'error', $result->message);
    }

    // ── Bulk Actions ──────────────────────────────────────────────────

    /**
     * Bulk validate multiple registrations via CoR chain.
     * POST /committee/competitions/{competition}/registrations/bulk-validate
     *
     * Frontend MUST show confirmation modal before submitting.
     */
    public function bulkValidate(Request $request, Competition $competition): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $request->validate([
            'registration_ids'   => ['required', 'array', 'min:1', 'max:50'],
            'registration_ids.*' => ['integer', 'exists:registrations,id'],
        ]);

        $result = $this->executor->bulkValidate(
            competitionId:   $competition->id,
            registrationIds: $request->input('registration_ids'),
            actorId:         auth()->id(),
        );

        $sessionKey = $result->success ? 'success' : 'error';

        return redirect()
            ->route('committee.registrations.index', $competition)
            ->with($sessionKey, $result->message)
            ->with('bulk_result_details', $result->details);
    }

    // ── Guard ─────────────────────────────────────────────────────────

    private function ensureRegistrationBelongsToCompetition(
        Registration $registration,
        Competition $competition
    ): void {
        abort_unless($registration->competition_id === $competition->id, 404);
    }
}
