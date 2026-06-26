<?php

namespace App\Services\Registration;

use App\Models\Competition;

/**
 * RegistrationPreCheckService — client-side pre-submission validation.
 *
 * Dipanggil via AJAX sebelum participant submit form registrasi.
 * Tujuan: memberi instant feedback tentang kelengkapan form TANPA mutate database.
 *
 * Perbedaan dengan RegistrationValidator (CoR committee-side):
 * - PreCheck: read-only, dipanggil participant, validasi completeness sebelum submit
 * - RegistrationValidator: mutate status, dipanggil committee, validasi setelah submit
 *
 * Output: array of issues. Empty array = form siap disubmit.
 */
class RegistrationPreCheckService
{
    /**
     * @param  Competition  $competition
     * @param  array        $formData        — key-value dari form fields (non-file)
     * @param  array        $uploadedFiles   — key = field label, value = UploadedFile|null
     * @param  mixed|null   $paymentProof    — UploadedFile|null
     * @return PreCheckIssue[]
     */
    public function check(
        Competition $competition,
        array $formData,
        array $uploadedFiles,
        mixed $paymentProof
    ): array {
        $issues = [];

        $formTemplate = $competition->formTemplates()->first();

        if ($formTemplate && is_array($formTemplate->fields)) {
            $issues = array_merge(
                $issues,
                $this->checkRequiredFields($formTemplate->fields, $formData, $uploadedFiles)
            );
            $issues = array_merge(
                $issues,
                $this->checkFileFormats($formTemplate->fields, $uploadedFiles)
            );
        }

        $issues = array_merge(
            $issues,
            $this->checkPaymentRequirement($competition, $paymentProof)
        );

        return $issues;
    }

    /**
     * Cek semua required fields sudah diisi/diupload.
     */
    private function checkRequiredFields(array $fields, array $formData, array $uploadedFiles): array
    {
        $issues = [];

        foreach ($fields as $field) {
            $label    = $field['label'] ?? null;
            $type     = $field['type'] ?? 'text';
            $required = $field['required'] ?? false;

            if (! $label || ! $required) {
                continue;
            }

            if ($type === 'file') {
                if (empty($uploadedFiles[$label])) {
                    $issues[] = PreCheckIssue::missing(
                        field: $label,
                        message: "Dokumen '{$label}' wajib diupload."
                    );
                }
            } elseif ($type === 'checkbox') {
                if (empty($formData[$label])) {
                    $issues[] = PreCheckIssue::missing(
                        field: $label,
                        message: "Checkbox '{$label}' harus dicentang."
                    );
                }
            } else {
                if (! isset($formData[$label]) || trim((string) $formData[$label]) === '') {
                    $issues[] = PreCheckIssue::missing(
                        field: $label,
                        message: "Field '{$label}' wajib diisi."
                    );
                }
            }
        }

        return $issues;
    }

    /**
     * Cek format file yang diupload.
     * Saat ini basic check — bisa di-extend dengan accepted_formats dari field config.
     */
    private function checkFileFormats(array $fields, array $uploadedFiles): array
    {
        $issues        = [];
        $allowedMimes  = ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSizeKb     = 5120; // 5MB

        foreach ($fields as $field) {
            $label = $field['label'] ?? null;
            $type  = $field['type'] ?? 'text';

            if ($type !== 'file' || ! $label) {
                continue;
            }

            $file = $uploadedFiles[$label] ?? null;
            if (! $file) {
                continue; // Missing required files sudah ditangani oleh checkRequiredFields
            }

            $ext = strtolower($file->getClientOriginalExtension());
            if (! in_array($ext, $allowedMimes)) {
                $issues[] = PreCheckIssue::invalid(
                    field: $label,
                    message: "Format file '{$label}' tidak didukung ({$ext}). Gunakan: " . implode(', ', $allowedMimes)
                );
            }

            if ($file->getSize() > $maxSizeKb * 1024) {
                $issues[] = PreCheckIssue::invalid(
                    field: $label,
                    message: "File '{$label}' terlalu besar. Maksimum " . ($maxSizeKb / 1024) . 'MB.'
                );
            }
        }

        return $issues;
    }

    /**
     * Cek bukti pembayaran jika competition berbayar.
     */
    private function checkPaymentRequirement(Competition $competition, mixed $paymentProof): array
    {
        if ($competition->registration_fee <= 0) {
            return []; // Free — tidak perlu payment proof
        }

        if (! $paymentProof) {
            return [
                PreCheckIssue::missing(
                    field: 'payment_proof',
                    message: 'Bukti pembayaran wajib diupload untuk kompetisi berbayar (fee: Rp ' .
                             number_format($competition->registration_fee, 0, ',', '.') . ').'
                ),
            ];
        }

        return [];
    }
}
