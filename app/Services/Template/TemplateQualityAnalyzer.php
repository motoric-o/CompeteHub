<?php

namespace App\Services\Template;

use App\Models\Competition;

/**
 * TemplateQualityAnalyzer — analyzes form template fields for quality issues.
 *
 * Implements Chain of Responsibility logic internally.
 * Setiap check adalah private method terpisah, dipanggil secara berurutan.
 *
 * Justifikasi: Tidak menggunakan separate handler classes karena:
 * 1. Semua checks berada dalam satu domain (template quality)
 * 2. Checks tidak perlu di-reuse secara independen
 * 3. Internal CoR structure cukup untuk maintainability
 *
 * Dipanggil dari FormTemplateController saat save/preview template.
 * Tidak mutate database — hanya analyze dan return warnings.
 */
class TemplateQualityAnalyzer
{
    private const MAX_RECOMMENDED_FIELDS  = 12;
    private const MAX_REQUIRED_RATIO      = 0.70; // 70% — jika lebih, warning
    private const ALLOWED_FILE_FORMATS    = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'zip'];

    /**
     * Analyze the given fields array and return quality warnings.
     *
     * @param  array       $fields       — dari FormTemplate::fields
     * @param  Competition $competition  — untuk context-aware checks (fee, type)
     * @return QualityWarning[]
     */
    public function analyze(array $fields, Competition $competition): array
    {
        if (empty($fields)) {
            return [
                QualityWarning::error(
                    'empty_template',
                    'Template tidak memiliki field apapun.',
                    suggestion: 'Tambahkan minimal satu field agar peserta bisa mengisi data.'
                ),
            ];
        }

        $warnings = [];

        $warnings = array_merge($warnings, $this->checkDuplicateLabels($fields));
        $warnings = array_merge($warnings, $this->checkRequiredFieldRatio($fields));
        $warnings = array_merge($warnings, $this->checkPaymentFieldConsistency($fields, $competition));
        $warnings = array_merge($warnings, $this->checkFileUploadConsistency($fields));
        $warnings = array_merge($warnings, $this->checkFormLength($fields));
        $warnings = array_merge($warnings, $this->checkEmptyLabels($fields));
        $warnings = array_merge($warnings, $this->checkFieldTypeConsistency($fields));

        return $warnings;
    }

    // ── Checks ──────────────────────────────────────────────────────

    /**
     * Cek label duplikat — akan menyebabkan data loss saat registrasi.
     */
    private function checkDuplicateLabels(array $fields): array
    {
        $warnings = [];
        $labels   = [];

        foreach ($fields as $field) {
            $label = trim($field['label'] ?? '');
            if (! $label) {
                continue;
            }

            $normalizedLabel = strtolower($label);
            if (isset($labels[$normalizedLabel])) {
                $warnings[] = QualityWarning::error(
                    'duplicate_field',
                    "Label duplikat ditemukan: \"{$label}\". Dua field dengan label sama akan menimpa data satu sama lain.",
                    field: $label,
                    suggestion: 'Ganti salah satu label menjadi lebih spesifik.'
                );
            }

            $labels[$normalizedLabel] = true;
        }

        return $warnings;
    }

    /**
     * Cek rasio required fields — terlalu banyak required field membuat peserta frustasi.
     */
    private function checkRequiredFieldRatio(array $fields): array
    {
        $totalFields    = count($fields);
        $requiredFields = count(array_filter($fields, fn ($f) => ($f['required'] ?? false) === true));

        if ($totalFields === 0) {
            return [];
        }

        $ratio = $requiredFields / $totalFields;

        if ($ratio >= 1.0 && $totalFields > 3) {
            return [
                QualityWarning::warning(
                    'all_fields_required',
                    "Semua {$totalFields} field ditandai sebagai required. Form ini mungkin terlalu ketat.",
                    suggestion: 'Pertimbangkan membuat beberapa field menjadi optional untuk meningkatkan completion rate.'
                ),
            ];
        }

        if ($ratio > self::MAX_REQUIRED_RATIO && $totalFields > 5) {
            $requiredPercent = round($ratio * 100);
            return [
                QualityWarning::info(
                    'high_required_ratio',
                    "{$requiredPercent}% field ditandai required ({$requiredFields} dari {$totalFields}). Ini bisa menyulitkan peserta.",
                    suggestion: 'Pertimbangkan mengurangi jumlah field wajib untuk meningkatkan pengalaman pendaftaran.'
                ),
            ];
        }

        return [];
    }

    /**
     * Cek konsistensi payment field dengan competition fee.
     */
    private function checkPaymentFieldConsistency(array $fields, Competition $competition): array
    {
        $warnings          = [];
        $hasPaymentField   = false;
        $hasPaymentInLabel = false;

        foreach ($fields as $field) {
            $label = strtolower($field['label'] ?? '');
            $type  = $field['type'] ?? '';

            if ($type === 'file' && (str_contains($label, 'payment') || str_contains($label, 'bukti') || str_contains($label, 'transfer'))) {
                $hasPaymentField = true;
            }

            if (str_contains($label, 'payment') || str_contains($label, 'pembayaran') || str_contains($label, 'biaya')) {
                $hasPaymentInLabel = true;
            }
        }

        // Competition berbayar tapi tidak ada payment field di template
        if ($competition->registration_fee > 0 && ! $hasPaymentField) {
            $warnings[] = QualityWarning::info(
                'missing_payment_field_hint',
                'Kompetisi ini berbayar (Rp ' . number_format($competition->registration_fee, 0, ',', '.') . ') ' .
                'tapi template tidak memiliki field upload bukti pembayaran.',
                suggestion: 'Sistem otomatis menyediakan field payment proof saat registrasi, tapi Anda bisa menambahkan field custom jika diperlukan.'
            );
        }

        // Competition gratis tapi ada field payment di template
        if ($competition->registration_fee <= 0 && $hasPaymentInLabel) {
            $warnings[] = QualityWarning::warning(
                'payment_field_on_free_competition',
                'Template memiliki field yang berkaitan dengan pembayaran, tetapi kompetisi ini gratis.',
                suggestion: 'Hapus field pembayaran atau update fee kompetisi jika memang berbayar.'
            );
        }

        return $warnings;
    }

    /**
     * Cek file upload fields — pastikan tidak ada yang terlalu ambigu.
     */
    private function checkFileUploadConsistency(array $fields): array
    {
        $warnings     = [];
        $fileFields   = array_filter($fields, fn ($f) => ($f['type'] ?? '') === 'file');
        $fileLabels   = [];

        foreach ($fileFields as $field) {
            $label = $field['label'] ?? '';

            // Cek label terlalu generik
            $genericLabels = ['file', 'document', 'upload', 'attachment', 'dokumen', 'berkas'];
            if (in_array(strtolower($label), $genericLabels)) {
                $warnings[] = QualityWarning::warning(
                    'generic_file_label',
                    "Field upload \"{$label}\" memiliki nama yang terlalu generik.",
                    field: $label,
                    suggestion: "Gunakan nama yang lebih spesifik seperti 'KTP', 'Portfolio', 'Surat Keterangan Mahasiswa'."
                );
            }

            // Cek duplikat file field (handled by checkDuplicateLabels, tapi khusus warning untuk file)
            $normalizedLabel = strtolower($label);
            if (isset($fileLabels[$normalizedLabel])) {
                $warnings[] = QualityWarning::error(
                    'duplicate_file_field',
                    "Field upload duplikat: \"{$label}\". Peserta akan bingung dokumen mana yang harus diupload.",
                    field: $label
                );
            }

            $fileLabels[$normalizedLabel] = true;
        }

        return $warnings;
    }

    /**
     * Cek panjang form — form terlalu panjang menurunkan completion rate.
     */
    private function checkFormLength(array $fields): array
    {
        $count = count($fields);

        if ($count > self::MAX_RECOMMENDED_FIELDS * 1.5) {
            return [
                QualityWarning::warning(
                    'form_too_long',
                    "Form memiliki {$count} field. Form yang terlalu panjang menurunkan tingkat penyelesaian registrasi.",
                    suggestion: 'Pertimbangkan memindahkan field non-esensial ke tahap berikutnya atau membuat field opsional.'
                ),
            ];
        }

        if ($count > self::MAX_RECOMMENDED_FIELDS) {
            return [
                QualityWarning::info(
                    'form_length_warning',
                    "Form memiliki {$count} field (rekomendasi maksimum: " . self::MAX_RECOMMENDED_FIELDS . ").",
                    suggestion: 'Form yang lebih pendek meningkatkan pengalaman pendaftaran peserta.'
                ),
            ];
        }

        return [];
    }

    /**
     * Cek field dengan label kosong atau hanya whitespace.
     */
    private function checkEmptyLabels(array $fields): array
    {
        $warnings    = [];
        $emptyCount  = 0;

        foreach ($fields as $index => $field) {
            $label = trim($field['label'] ?? '');
            if ($label === '') {
                $emptyCount++;
                $warnings[] = QualityWarning::error(
                    'empty_field_label',
                    "Field ke-" . ($index + 1) . " tidak memiliki label. Field tanpa label tidak akan muncul di form registrasi.",
                    suggestion: 'Berikan label yang deskriptif untuk setiap field.'
                );
            }
        }

        return $warnings;
    }

    /**
     * Cek tipe field — pastikan tipe yang digunakan valid dan konsisten.
     */
    private function checkFieldTypeConsistency(array $fields): array
    {
        $warnings    = [];
        $validTypes  = ['text', 'textarea', 'email', 'number', 'date', 'file', 'select', 'checkbox', 'radio'];

        foreach ($fields as $field) {
            $type  = $field['type'] ?? null;
            $label = $field['label'] ?? 'Unknown';

            if (! $type) {
                $warnings[] = QualityWarning::error(
                    'missing_field_type',
                    "Field \"{$label}\" tidak memiliki tipe yang ditentukan.",
                    field: $label,
                    suggestion: 'Tentukan tipe field (text, file, email, dll.).'
                );
                continue;
            }

            if (! in_array($type, $validTypes)) {
                $warnings[] = QualityWarning::warning(
                    'unknown_field_type',
                    "Field \"{$label}\" menggunakan tipe yang tidak dikenal: \"{$type}\".",
                    field: $label,
                    suggestion: 'Gunakan tipe yang valid: ' . implode(', ', $validTypes) . '.'
                );
            }

            // Email field tapi label tidak menyebut email — mungkin salah tipe
            if ($type === 'email' && ! str_contains(strtolower($label), 'email')) {
                $warnings[] = QualityWarning::info(
                    'possible_type_mismatch',
                    "Field \"{$label}\" bertipe email tapi labelnya tidak menyebut 'email'. Pastikan tipe yang dipilih benar.",
                    field: $label
                );
            }
        }

        return $warnings;
    }

    /**
     * Get summary counts by severity.
     *
     * @param  QualityWarning[] $warnings
     * @return array{errors: int, warnings: int, infos: int, total: int}
     */
    public function getSummary(array $warnings): array
    {
        return [
            'errors'   => count(array_filter($warnings, fn ($w) => $w->severity === 'error')),
            'warnings' => count(array_filter($warnings, fn ($w) => $w->severity === 'warning')),
            'infos'    => count(array_filter($warnings, fn ($w) => $w->severity === 'info')),
            'total'    => count($warnings),
        ];
    }

    /**
     * Quick check — apakah template aman untuk digunakan (tidak ada errors).
     *
     * @param  QualityWarning[] $warnings
     */
    public function isTemplateSafe(array $warnings): bool
    {
        return count(array_filter($warnings, fn ($w) => $w->severity === 'error')) === 0;
    }
}
