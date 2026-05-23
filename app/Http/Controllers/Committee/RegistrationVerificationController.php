<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Committee\Concerns\CommitteeAuthorization;
use App\Models\Competition;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Services\Validation\RegistrationValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationVerificationController extends Controller
{
    use CommitteeAuthorization;

    public function __construct(
        private RegistrationValidator $validator,
    ) {
    }

    public function index(Competition $competition): View
    {
        $this->authorizeCommittee($competition);

        $registrations = $competition->registrations()
            ->with(['user', 'team', 'documents', 'payment'])
            ->latest()
            ->get();

        return view('committee.registrations.index', compact('competition', 'registrations'));
    }

    public function show(Competition $competition, Registration $registration): View
    {
        $this->authorizeCommittee($competition);
        $this->authorizeRegistration($competition, $registration);

        $registration->load(['user', 'team.captain', 'documents', 'payment']);

        return view('committee.registrations.show', compact('competition', 'registration'));
    }

    public function validate(Competition $competition, Registration $registration): RedirectResponse
    {
        $this->authorizeCommittee($competition);
        $this->authorizeRegistration($competition, $registration);

        $result = $this->validator->validate($registration);

        return redirect()
            ->route('committee.registrations.show', [$competition, $registration])
            ->with($result->passed ? 'success' : 'error', $result->message);
    }

    public function verifyDocument(Request $request, RegistrationDocument $document): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:verified,rejected'],
        ]);

        $registration = $document->registration()
            ->with('competition')
            ->firstOrFail();

        $this->authorizeCommittee($registration->competition);

        $document->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('committee.registrations.show', [$registration->competition_id, $registration])
            ->with('success', "Document '{$document->document_type}' marked as {$request->status}.");
    }

    public function verifyPayment(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:paid,unpaid'],
        ]);

        $registration = $payment->registration()
            ->with('competition')
            ->firstOrFail();

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

    private function authorizeRegistration(Competition $competition, Registration $registration): void
    {
        abort_unless($registration->competition_id === $competition->id, 404);
    }
}
