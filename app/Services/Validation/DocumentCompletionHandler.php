<?php

namespace App\Services\Validation;

use App\Models\Registration;

/**
 * CoR Tahap 2 — Validasi Kelengkapan Dokumen.
 *
 * Cek apakah:
 * - Semua dokumen yang di-require sudah diupload
 * - Semua dokumen statusnya 'verified' (bukan 'rejected' atau 'pending')
 */
class DocumentCompletionHandler extends RegistrationHandler
{
    protected function validate(Registration $registration): ValidationResult
    {
        $documents = $registration->documents;

        // Ambil form template untuk tahu field mana yg type 'file' dan required
        $formTemplate = $registration->competition->formTemplates()->first();

        if ($formTemplate) {
            $requiredFileFields = collect($formTemplate->fields)
                ->filter(fn ($f) => ($f['type'] ?? '') === 'file' && ($f['required'] ?? false))
                ->pluck('label')
                ->toArray();

            if (count($requiredFileFields) > 0) {
                $uploadedTypes = $documents->pluck('document_type')->toArray();

                foreach ($requiredFileFields as $required) {
                    if (! in_array($required, $uploadedTypes)) {
                        return ValidationResult::fail("Required document '{$required}' is missing.");
                    }
                }
            }
        }

        // Cek apakah ada dokumen yang di-reject
        $rejectedDocs = $documents->where('status', 'rejected');
        if ($rejectedDocs->count() > 0) {
            $names = $rejectedDocs->pluck('document_type')->join(', ');
            return ValidationResult::fail("Document(s) rejected: {$names}. Please re-upload.");
        }

        // Cek apakah semua dokumen sudah verified
        $pendingDocs = $documents->where('status', 'pending');
        if ($pendingDocs->count() > 0) {
            return ValidationResult::fail('Some documents are still pending review.');
        }

        return ValidationResult::pass('All documents verified.', 'documents_ok');
    }
}
