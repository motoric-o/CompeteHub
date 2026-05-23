<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Services\Registration\RegistrationService;
use App\States\RegistrationStateResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(
        private RegistrationService $registrationService,
        private RegistrationStateResolver $stateResolver,
    ) {}

    public function index(): View
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['competition', 'payment', 'documents'])
            ->latest()
            ->get();

        return view('participant.registrations.index', compact('registrations'));
    }

    public function create(Competition $competition): View|RedirectResponse
    {
        $eligibility = $this->registrationService->checkEligibility($competition, auth()->id());

        if (! $eligibility['eligible'] && $eligibility['reason'] === 'already_registered') {
            return redirect()->route(
                'participant.registrations.show',
                [$competition, $eligibility['registration']]
            );
        }

        $formTemplate = $competition->formTemplates()->first();

        return view('participant.registrations.create', compact('competition', 'formTemplate'));
    }

    public function store(Request $request, Competition $competition): RedirectResponse
    {
        // Eligibility check
        $eligibility = $this->registrationService->checkEligibility($competition, auth()->id());

        if (! $eligibility['eligible']) {
            $messages = [
                'already_registered'        => 'You have already registered for this competition.',
                'registration_closed'       => 'Registration period has ended.',
                'registration_not_open_yet' => 'Registration has not opened yet.',
                'quota_full'                => 'Registration quota is full.',
            ];

            $msg = $messages[$eligibility['reason']] ?? 'Unable to register at this time.';

            if ($eligibility['reason'] === 'already_registered') {
                return redirect()
                    ->route('participant.registrations.show', [$competition, $eligibility['registration']])
                    ->with('error', $msg);
            }

            return back()->with('error', $msg);
        }

        // Validate request using centralized rule builder
        $rules = $this->registrationService->buildValidationRules($competition);
        $request->validate($rules);

        // Create registration via service (handles documents + payment atomically)
        $registration = $this->registrationService->createRegistration(
            competition:  $competition,
            userId:       auth()->id(),
            formData:     $request->input('form_data', []),
            documents:    $request->hasFile('documents') ? $request->file('documents') : [],
            paymentProof: $request->file('payment_proof'),
        );

        return redirect()
            ->route('participant.registrations.show', [$competition, $registration])
            ->with('success', 'Registration submitted! Waiting for verification.');
    }

    public function show(Competition $competition, Registration $registration): View
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        abort_unless($registration->competition_id === $competition->id, 404);

        $registration->load(['documents', 'payment']);

        // Resolve next action card
        $nextAction = $this->stateResolver->resolve($registration);

        // Customize dynamic action URLs if actionable
        if ($nextAction->isActionable) {
            if ($nextAction->state === 'account_ok') {
                $nextAction = new \App\States\NextActionCard(
                    state: $nextAction->state,
                    title: $nextAction->title,
                    description: $nextAction->description,
                    actionLabel: $nextAction->actionLabel,
                    actionUrl: '#documents-section',
                    severity: $nextAction->severity,
                    icon: $nextAction->icon,
                    isActionable: $nextAction->isActionable,
                    deadlineNote: $nextAction->deadlineNote,
                    progressSteps: $nextAction->progressSteps
                );
            } elseif ($nextAction->state === 'documents_ok') {
                $nextAction = new \App\States\NextActionCard(
                    state: $nextAction->state,
                    title: $nextAction->title,
                    description: $nextAction->description,
                    actionLabel: $nextAction->actionLabel,
                    actionUrl: '#payment-section',
                    severity: $nextAction->severity,
                    icon: $nextAction->icon,
                    isActionable: $nextAction->isActionable,
                    deadlineNote: $nextAction->deadlineNote,
                    progressSteps: $nextAction->progressSteps
                );
            } elseif ($nextAction->state === 'verified') {
                $nextAction = new \App\States\NextActionCard(
                    state: $nextAction->state,
                    title: $nextAction->title,
                    description: $nextAction->description,
                    actionLabel: $nextAction->actionLabel,
                    actionUrl: route('participant.registrations.certificate', [$competition, $registration]),
                    severity: $nextAction->severity,
                    icon: $nextAction->icon,
                    isActionable: $nextAction->isActionable,
                    deadlineNote: $nextAction->deadlineNote,
                    progressSteps: $nextAction->progressSteps
                );
            }
        }

        return view('participant.registrations.show', compact('competition', 'registration', 'nextAction'));
    }

    /**
     * AJAX pre-check — validate form completeness before final submission.
     * Returns JSON with list of issues. Empty issues = ready to submit.
     */
    public function preCheck(Request $request, Competition $competition): \Illuminate\Http\JsonResponse
    {
        // Quick eligibility gate
        $eligibility = $this->registrationService->checkEligibility($competition, auth()->id());
        if (! $eligibility['eligible'] && $eligibility['reason'] !== null) {
            return response()->json([
                'eligible' => false,
                'reason'   => $eligibility['reason'],
                'issues'   => [],
            ], 422);
        }

        /** @var \App\Services\Registration\RegistrationPreCheckService $preCheck */
        $preCheck = app(\App\Services\Registration\RegistrationPreCheckService::class);

        $issues = $preCheck->check(
            competition:  $competition,
            formData:     $request->input('form_data', []),
            uploadedFiles: $request->hasFile('documents') ? $request->file('documents') : [],
            paymentProof: $request->file('payment_proof'),
        );

        return response()->json([
            'eligible' => true,
            'issues'   => array_map(fn ($i) => $i->toArray(), $issues),
            'ready'    => count($issues) === 0,
        ]);
    }

    /**
     * Download Certificate.
     */
    public function downloadCertificate(
        Competition $competition,
        Registration $registration,
        \App\Services\Facade\NotificationFacade $facade
    ) {
        abort_unless($registration->user_id === auth()->id(), 403);
        abort_unless($registration->competition_id === $competition->id, 404);
        abort_unless(in_array($registration->status, ['verified', 'payment_ok']), 403, 'Registration not verified.');

        $data = [
            'userName'        => auth()->user()->name,
            'competitionName' => $competition->name,
        ];

        $path = $facade->generatePDFCertificate(auth()->id(), $competition->id, $data);

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Certificate file not found. Please try again.');
        }

        $absolutePath = Storage::disk('public')->path($path);

        return response()->download(
            $absolutePath,
            "Certificate_{$competition->name}_{$registration->id}.pdf"
        );
    }

    /**
     * Re-upload a rejected document.
     */
    public function reuploadDocument(Request $request, Competition $competition, Registration $registration): RedirectResponse
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        abort_unless($registration->competition_id === $competition->id, 404);

        $request->validate([
            'document_type' => ['required', 'string'],
            'file'          => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $docType = $request->input('document_type');
        $document = $registration->documents()->where('document_type', $docType)->first();

        abort_unless($document, 404, 'Dokumen jenis ini tidak ditemukan.');

        // Simpan file baru
        $path = $request->file('file')->store('registration-documents/' . $registration->id, 'public');

        // Update record dokumen
        $document->update([
            'file_path' => $path,
            'status'    => 'pending',
        ]);

        // Reset status registrasi ke pending jika sebelumnya ditolak
        if ($registration->status === 'rejected') {
            $registration->update([
                'status' => 'pending',
                'rejection_reason' => null,
            ]);
        }

        return back()->with('success', "Dokumen {$docType} berhasil diupload ulang.");
    }

    /**
     * Re-upload or upload payment proof.
     */
    public function reuploadPayment(Request $request, Competition $competition, Registration $registration): RedirectResponse
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        abort_unless($registration->competition_id === $competition->id, 404);

        $request->validate([
            'payment_proof' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $payment = $registration->payment;
        abort_unless($payment, 404, 'Data pembayaran tidak ditemukan.');

        // Simpan bukti pembayaran baru
        $path = $request->file('payment_proof')->store('payment-proofs/' . $registration->id, 'public');

        // Update payment record
        $payment->update([
            'proof_path' => $path,
            'status'     => 'pending_verification',
        ]);

        if ($registration->status !== 'verified') {
            $registration->update([
                'status' => 'payment_ok',
                'rejection_reason' => null,
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi panitia.');
    }

    private function checkRegistrationAvailability(Competition $competition): ?RedirectResponse
    {
        if (! in_array($competition->status, ['open', 'ongoing'])) {
            return redirect()
                ->route('participant.competitions.index')
                ->with('error', 'Registration is not open for this competition.');
        }

        if ($competition->registration_start && now()->isBefore($competition->registration_start)) {
            return redirect()
                ->route('participant.competitions.index')
                ->with('error', 'Registration period has not started yet.');
        }

        if ($competition->registration_end && now()->isAfter($competition->registration_end)) {
            return redirect()
                ->route('participant.competitions.index')
                ->with('error', 'Registration period has ended.');
        }

        return null;
    }
}