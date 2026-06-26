<?php

namespace App\Services\Registration;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * RegistrationService — business logic untuk pendaftaran peserta.
 *
 * Extracted dari Participant\RegistrationController::store() untuk:
 * 1. Testability — logic bisa ditest tanpa HTTP layer
 * 2. Reusability — endpoint AJAX pre-check & re-upload bisa pakai ulang logic ini
 * 3. SRP — controller hanya orchestrate HTTP, bukan jalankan business logic
 *
 * Tanggung jawab class ini:
 * - Cek eligibility pendaftaran (duplikat, kuota, periode)
 * - Create registration record
 * - Handle document upload
 * - Handle payment record creation
 */
class RegistrationService
{
    /**
     * Cek apakah user boleh mendaftar ke competition ini.
     * Return array ['eligible' => bool, 'reason' => string|null]
     */
    public function checkEligibility(Competition $competition, int $userId): array
    {
        $existing = null;

        if ($competition->type === 'team') {
            $team = \App\Models\Team::where('competition_id', $competition->id)
                ->whereHas('members', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                })->first();

            if (!$team) {
                return ['eligible' => false, 'reason' => 'no_team'];
            }

            if ($team->user_id !== $userId) {
                return ['eligible' => false, 'reason' => 'not_captain'];
            }

            $existing = Registration::where('competition_id', $competition->id)
                ->where('team_id', $team->id)
                ->first();
        } else {
            // Cek duplikat registrasi individual
            $existing = Registration::where('competition_id', $competition->id)
                ->where('user_id', $userId)
                ->first();
        }

        if ($existing) {
            return [
                'eligible' => false,
                'reason'   => 'already_registered',
                'registration' => $existing,
            ];
        }

        // Cek periode pendaftaran
        if ($competition->registration_end && now()->isAfter($competition->registration_end)) {
            return ['eligible' => false, 'reason' => 'registration_closed'];
        }

        if ($competition->registration_start && now()->isBefore($competition->registration_start)) {
            return ['eligible' => false, 'reason' => 'registration_not_open_yet'];
        }

        // Cek kuota — gunakan registrations (bukan teams) sebagai sumber kebenaran
        if ($competition->quota !== null) {
            $activeCount = Registration::where('competition_id', $competition->id)
                ->whereNotIn('status', ['rejected'])
                ->count();

            if ($activeCount >= $competition->quota) {
                return ['eligible' => false, 'reason' => 'quota_full'];
            }
        }

        return ['eligible' => true, 'reason' => null];
    }

    /**
     * Buat registrasi baru beserta dokumen dan payment record.
     * Semua operasi dibungkus dalam DB transaction.
     *
     * @param  Competition  $competition
     * @param  int          $userId
     * @param  array        $formData   — data dari form fields (non-file)
     * @param  array        $documents  — array of UploadedFile, key = document_type
     * @param  mixed|null   $paymentProof — UploadedFile|null
     * @return Registration
     */
    public function createRegistration(
        Competition $competition,
        int $userId,
        array $formData,
        array $documents,
        mixed $paymentProof
    ): Registration {
        return DB::transaction(function () use ($competition, $userId, $formData, $documents, $paymentProof) {
            $teamId = null;
            if ($competition->type === 'team') {
                $team = \App\Models\Team::where('competition_id', $competition->id)
                    ->whereHas('members', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->first();
                if ($team) {
                    $teamId = $team->id;
                }
            }

            // 1. Create registration record
            $registration = Registration::create([
                'competition_id' => $competition->id,
                'user_id'        => $userId,
                'team_id'        => $teamId,
                'form_data'      => $formData,
                'status'         => 'pending',
            ]);

            // 2. Handle document uploads
            foreach ($documents as $type => $file) {
                $path = $file->store('registration-documents/' . $registration->id, 'public');

                RegistrationDocument::create([
                    'registration_id' => $registration->id,
                    'document_type'   => $type,
                    'file_path'       => $path,
                ]);
            }

            // 3. Handle payment record
            $this->createPaymentRecord($registration, $competition, $paymentProof);

            return $registration;
        });
    }

    /**
     * Create payment record berdasarkan apakah competition punya fee atau tidak.
     */
    private function createPaymentRecord(
        Registration $registration,
        Competition $competition,
        mixed $paymentProof
    ): void {
        if ($competition->registration_fee > 0) {
            $proofPath = null;

            if ($paymentProof) {
                $proofPath = $paymentProof->store(
                    'payment-proofs/' . $registration->id,
                    'public'
                );
            }

            $registration->payment()->create([
                'amount'   => $competition->registration_fee,
                'status'   => 'pending_verification',
                'proof_path' => $proofPath,
            ]);
        } else {
            // Free competition — auto-mark as verified
            $registration->payment()->create([
                'amount'      => 0,
                'status'      => 'free',
                'verified_at' => now(),
            ]);
        }
    }

    /**
     * Build dynamic validation rules berdasarkan form template fields.
     * Extracted dari controller agar bisa di-reuse oleh RegistrationPreCheckService.
     *
     * @param  Competition $competition
     * @param  bool        $forPreCheck — jika true, file rules menjadi nullable (pre-submit check)
     * @return array       Laravel validation rules array
     */
    public function buildValidationRules(Competition $competition, bool $forPreCheck = false): array
    {
        $fileRule = $forPreCheck ? 'nullable' : 'required';

        $rules = [
            'documents.*' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
            'payment_proof' => [
                $competition->registration_fee > 0 ? 'required' : 'nullable',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,pdf',
            ],
        ];

        $formTemplate = $competition->formTemplates()->first();

        if ($formTemplate && is_array($formTemplate->fields)) {
            foreach ($formTemplate->fields as $field) {
                $label    = $field['label'] ?? null;
                $type     = $field['type'] ?? 'text';
                $required = $field['required'] ?? false;

                if (! $label) {
                    continue;
                }

                if ($type === 'file') {
                    $rules["documents.{$label}"] = $required
                        ? [$fileRule, 'file', 'max:5120']
                        : ['nullable', 'file', 'max:5120'];
                } elseif ($type === 'checkbox') {
                    $rules["form_data.{$label}"] = $required ? ['accepted'] : ['nullable'];
                } else {
                    $rules["form_data.{$label}"] = $required ? ['required', 'string'] : ['nullable', 'string'];
                }
            }
        }

        return $rules;
    }
}
