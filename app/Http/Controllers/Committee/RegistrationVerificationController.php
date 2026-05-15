<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\Payment;
use App\Services\Validation\RegistrationValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationVerificationController extends Controller
{
    public function __construct(
        private RegistrationValidator $validator,
    ) {
    }

    /**
     * List registrations for a competition.
     */
    public function index(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $registrations = $competition->registrations()
            ->with(['user', 'team', 'documents', 'payment'])
            ->latest()
            ->get();

        return view('committee.registrations.index', compact('competition', 'registrations'));
    }

    /**
     * Show detail & verify single registration.
     */
    public function show(Competition $competition, Registration $registration): View
    {
        $this->authorizeCommittee($competition);

        $registration->load(['user', 'team.captain', 'documents', 'payment']);

        return view('committee.registrations.show', compact('competition', 'registration'));
    }

    /**
     * Run CoR validation chain on a registration.
     */
    public function validate(Competition $competition, Registration $registration): RedirectResponse
    {
        $this->authorizeCommittee($competition);

        $result = $this->validator->validate($registration);

        return redirect()
            ->route('committee.registrations.show', [$competition, $registration])
            ->with($result->passed ? 'success' : 'error', $result->message);
    }

    /**
     * Verify a document (CoR tahap 2 support).
     */
    public function verifyDocument(Request $request, RegistrationDocument $document): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:verified,rejected'],
        ]);

        $registration = $document->registration()->with('competition')->firstOrFail();

        $this->authorizeCommittee($registration->competition);

        $document->update(['status' => $request->status]);

        return redirect()
            ->route('committee.registrations.show', [$registration->competition_id, $registration])
            ->with('success', "Document '{$document->document_type}' marked as {$request->status}.");
    }

    /**
     * Verify payment (CoR tahap 3 support).
     */
    public function verifyPayment(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:paid,unpaid'],
        ]);

        $registration = $payment->registration()->with('competition')->firstOrFail();

        $this->authorizeCommittee($registration->competition);

        $payment->update([
            'status' => $request->status,
            'verified_at' => $request->status === 'paid' ? now() : null,
        ]);

        return redirect()
            ->route('committee.registrations.show', [$registration->competition_id, $registration])
            ->with('success', "Payment marked as {$request->status}.");
    }

    private function authorizeCommittee(Competition $competition): void
    {
        abort_unless($competition->user_id === auth()->id(), 403);
    }
}
