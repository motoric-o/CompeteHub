<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(Competition $competition): View|RedirectResponse
    {
        $existing = Registration::where('competition_id', $competition->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('participant.registrations.show', [$competition, $existing]);
        }

        $formTemplate = $competition->formTemplates()->first();

        return view('participant.registrations.create', compact('competition', 'formTemplate'));
    }


    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $existing = Registration::where('competition_id', $competition->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()
                ->route('participant.registrations.show', [$competition, $existing])
                ->with('error', 'You have already registered for this competition.');
        }
        if ($competition->quota) {
            $count = Registration::where('competition_id', $competition->id)
                ->whereNotIn('status', ['rejected'])
                ->count();
            if ($count >= $competition->quota) {
                return back()->with('error', 'Registration quota is full.');
            }
        }

        if ($competition->registration_end && now()->isAfter($competition->registration_end)) {
            return back()->with('error', 'Registration period has ended.');
        }

        $formTemplate = $competition->formTemplates()->first();

        $rules = [
            'documents.*' => ['nullable', 'file', 'max:5120'],
            'payment_proof' => [
                $competition->registration_fee > 0 ? 'required' : 'nullable',
                'file',
                'max:5120',
            ],
        ];

        if ($formTemplate && is_array($formTemplate->fields)) {
            foreach ($formTemplate->fields as $field) {
                $label = $field['label'] ?? null;
                $type = $field['type'] ?? 'text';
                $required = $field['required'] ?? false;

                if (!$label || !$required) {
                    continue;
                }

                if ($type === 'file') {
                    $rules["documents.$label"] = ['required', 'file', 'max:5120'];
                } elseif ($type === 'checkbox') {
                    $rules["form_data.$label"] = ['accepted'];
                } else {
                    $rules["form_data.$label"] = ['required'];
                }
            }
        }

        $request->validate($rules);

        $registration = Registration::create([
            'competition_id' => $competition->id,
            'user_id' => auth()->id(),
            'form_data' => $request->input('form_data', []),
            'status' => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                $path = $file->store('registration-documents/' . $registration->id, 'public');

                RegistrationDocument::create([
                    'registration_id' => $registration->id,
                    'document_type' => $type,
                    'file_path' => $path,
                ]);
            }
        }

        if ($competition->registration_fee > 0) {
            $proofPath = null;

            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')
                    ->store('payment-proofs/' . $registration->id, 'public');
            }

            $registration->payment()->create([
                'amount' => $competition->registration_fee,
                'status' => 'pending_verification',
                'proof_path' => $proofPath,
            ]);
        } else {
            $registration->payment()->create([
                'amount' => 0,
                'status' => 'free',
                'verified_at' => now(),
            ]);
        }

        return redirect()
            ->route('participant.registrations.show', [$competition, $registration])
            ->with('success', 'Registration submitted! Waiting for verification.');
    }

    public function show(Competition $competition, Registration $registration): View
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        abort_unless($registration->competition_id === $competition->id, 404);

        $registration->load(['documents', 'payment']);

        return view('participant.registrations.show', compact('competition', 'registration'));
    }

    public function index(): View
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with('competition')
            ->latest()
            ->get();

        return view('participant.registrations.index', compact('registrations'));
    }
}