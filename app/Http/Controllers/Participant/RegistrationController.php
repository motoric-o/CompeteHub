<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\FormTemplate;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Show registration form (dynamic, based on form template).
     */
    public function create(Competition $competition): View
    {
        // Cek sudah pernah daftar belum
        $existing = Registration::where('competition_id', $competition->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return view('participant.registrations.status', compact('competition', 'existing'));
        }

        // Ambil form template pertama dari kompetisi
        $formTemplate = $competition->formTemplates()->first();

        return view('participant.registrations.create', compact('competition', 'formTemplate'));
    }

    /**
     * Submit registration.
     */
    public function store(Request $request, Competition $competition): RedirectResponse
    {
        // Cek quota
        if ($competition->quota) {
            $count = Registration::where('competition_id', $competition->id)
                ->whereNotIn('status', ['rejected'])
                ->count();
            if ($count >= $competition->quota) {
                return back()->with('error', 'Registration quota is full.');
            }
        }

        // Cek registration period
        if ($competition->registration_end && now()->isAfter($competition->registration_end)) {
            return back()->with('error', 'Registration period has ended.');
        }

        // Validasi dokumen yang diupload (dari dynamic form)
        $request->validate([
            'documents.*' => ['nullable', 'file', 'max:5120'], // max 5MB per file
        ]);

        // Create registration
        $registration = Registration::create([
            'competition_id' => $competition->id,
            'user_id'        => auth()->id(),
            'status'         => 'pending',
        ]);

        // Upload documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                $path = $file->store('registration-documents/' . $registration->id, 'public');
                RegistrationDocument::create([
                    'registration_id' => $registration->id,
                    'document_type'   => $type,
                    'file_path'       => $path,
                ]);
            }
        }

        return redirect()
            ->route('participant.registrations.show', [$competition, $registration])
            ->with('success', 'Registration submitted! Waiting for verification.');
    }

    /**
     * Show registration status.
     */
    public function show(Competition $competition, Registration $registration): View
    {
        abort_unless($registration->user_id === auth()->id(), 403);

        $registration->load(['documents', 'payment']);

        return view('participant.registrations.show', compact('competition', 'registration'));
    }

    /**
     * Download Certificate.
     */
    public function downloadCertificate(Competition $competition, Registration $registration, \App\Services\Facade\NotificationFacade $facade)
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        
        // Asumsi sertifikat hanya bisa diunduh jika status registrasi verified
        abort_unless(in_array($registration->status, ['verified', 'payment_ok']), 403, 'Registration not verified.');

        $data = [
            'userName' => auth()->user()->name,
            'competitionName' => $competition->name,
        ];

        $path = $facade->generatePDFCertificate(auth()->id(), $competition->id, $data);

        return response()->download(storage_path('app/public/' . $path));
    }

    /**
     * My registrations list.
     */
    public function index(): View
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with('competition')
            ->latest()
            ->get();

        return view('participant.registrations.index', compact('registrations'));
    }
}
