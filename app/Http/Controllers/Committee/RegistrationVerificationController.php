<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
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

        $bottleneckStats = $this->buildBottleneckStats($competition, $registrations);

        return view('committee.registrations.index', compact('competition', 'registrations', 'bottleneckStats'));
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

    private function buildBottleneckStats(Competition $competition, $registrations): array
    {
        $formTemplate = $competition->formTemplates()->latest()->first();

        $requiredDocumentLabels = collect($formTemplate?->fields ?? [])
            ->filter(fn ($field) => ($field['type'] ?? null) === 'file' && ($field['required'] ?? false))
            ->pluck('label')
            ->filter()
            ->values();

        $total = $registrations->count();

        $stats = [
            'total' => $total,

            'pending' => 0,
            'account_ok' => 0,
            'documents_ok' => 0,
            'payment_ok' => 0,
            'verified' => 0,
            'rejected' => 0,

            'required_document_count' => $requiredDocumentLabels->count(),
            'document_missing' => 0,
            'document_pending' => 0,
            'document_rejected' => 0,
            'document_ready' => 0,
            'total_documents_uploaded' => 0,
            'verified_documents_uploaded' => 0,
            'document_verification_rate' => 0,
            'average_documents_per_registration' => 0,

            'payment_missing' => 0,
            'payment_pending' => 0,
            'payment_unpaid' => 0,
            'payment_paid' => 0,
            'payment_free' => 0,
            'payment_ready' => 0,

            'ready_to_validate' => 0,

            'main_bottleneck' => [
                'label' => 'Belum ada data registrasi',
                'count' => 0,
                'percentage' => 0,
            ],

            'bottleneck_items' => [],
        ];

        foreach ($registrations as $registration) {
            if (array_key_exists($registration->status, $stats)) {
                $stats[$registration->status]++;
            }

            $documents = $registration->documents;
            $documentsByType = $documents->keyBy('document_type');

            $stats['total_documents_uploaded'] += $documents->count();
            $stats['verified_documents_uploaded'] += $documents->where('status', 'verified')->count();

            $hasMissingRequiredDocument = false;
            $hasPendingRequiredDocument = false;
            $hasRejectedRequiredDocument = false;

            foreach ($requiredDocumentLabels as $label) {
                $document = $documentsByType->get($label);

                if (! $document) {
                    $hasMissingRequiredDocument = true;
                    continue;
                }

                if ($document->status === 'pending') {
                    $hasPendingRequiredDocument = true;
                }

                if ($document->status === 'rejected') {
                    $hasRejectedRequiredDocument = true;
                }
            }

            if ($hasMissingRequiredDocument) {
                $stats['document_missing']++;
            }

            if ($hasPendingRequiredDocument) {
                $stats['document_pending']++;
            }

            if ($hasRejectedRequiredDocument) {
                $stats['document_rejected']++;
            }

            $documentReady = ! $hasMissingRequiredDocument
                && ! $hasPendingRequiredDocument
                && ! $hasRejectedRequiredDocument;

            if ($documentReady) {
                $stats['document_ready']++;
            }

            $paymentStatus = $registration->payment?->status;

            if (! $paymentStatus && $competition->registration_fee <= 0) {
                $paymentStatus = 'free';
            }

            if (! $paymentStatus && $competition->registration_fee > 0) {
                $paymentStatus = 'missing';
            }

            if ($paymentStatus === 'missing') {
                $stats['payment_missing']++;
            }

            if ($paymentStatus === 'pending_verification') {
                $stats['payment_pending']++;
            }

            if ($paymentStatus === 'unpaid') {
                $stats['payment_unpaid']++;
            }

            if ($paymentStatus === 'paid') {
                $stats['payment_paid']++;
            }

            if ($paymentStatus === 'free') {
                $stats['payment_free']++;
            }

            $paymentReady = in_array($paymentStatus, ['paid', 'free'], true);

            if ($paymentReady) {
                $stats['payment_ready']++;
            }

            if (
                $documentReady
                && $paymentReady
                && ! in_array($registration->status, ['payment_ok', 'verified', 'rejected'], true)
            ) {
                $stats['ready_to_validate']++;
            }
        }

        $stats['document_verification_rate'] = $stats['total_documents_uploaded'] > 0
            ? round(($stats['verified_documents_uploaded'] / $stats['total_documents_uploaded']) * 100, 1)
            : 0;

        $stats['average_documents_per_registration'] = $total > 0
            ? round($stats['total_documents_uploaded'] / $total, 2)
            : 0;

        $bottleneckItems = [
            [
                'label' => 'Dokumen wajib belum diupload',
                'count' => $stats['document_missing'],
                'type' => 'danger',
            ],
            [
                'label' => 'Dokumen masih pending verification',
                'count' => $stats['document_pending'],
                'type' => 'warning',
            ],
            [
                'label' => 'Dokumen ditolak',
                'count' => $stats['document_rejected'],
                'type' => 'danger',
            ],
            [
                'label' => 'Payment masih pending verification',
                'count' => $stats['payment_pending'],
                'type' => 'warning',
            ],
            [
                'label' => 'Payment ditandai unpaid',
                'count' => $stats['payment_unpaid'],
                'type' => 'danger',
            ],
            [
                'label' => 'Payment record belum ada',
                'count' => $stats['payment_missing'],
                'type' => 'danger',
            ],
            [
                'label' => 'Registrasi rejected',
                'count' => $stats['rejected'],
                'type' => 'danger',
            ],
        ];

        usort($bottleneckItems, fn ($a, $b) => $b['count'] <=> $a['count']);

        $stats['bottleneck_items'] = $bottleneckItems;

        $mainBottleneck = $bottleneckItems[0] ?? null;

        if ($total === 0) {
            $stats['main_bottleneck'] = [
                'label' => 'Belum ada data registrasi',
                'count' => 0,
                'percentage' => 0,
            ];
        } elseif (! $mainBottleneck || $mainBottleneck['count'] === 0) {
            $stats['main_bottleneck'] = [
                'label' => 'Tidak ada bottleneck besar',
                'count' => 0,
                'percentage' => 0,
            ];
        } else {
            $stats['main_bottleneck'] = [
                'label' => $mainBottleneck['label'],
                'count' => $mainBottleneck['count'],
                'percentage' => round(($mainBottleneck['count'] / $total) * 100, 1),
            ];
        }

        return $stats;
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