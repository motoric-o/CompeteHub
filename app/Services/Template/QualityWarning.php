<?php

namespace App\Services\Template;

/**
 * QualityWarning — DTO untuk satu peringatan kualitas form template.
 *
 * Immutable value object, diproduksi oleh TemplateQualityAnalyzer.
 *
 * severity levels:
 * - 'error'   : masalah yang akan menyebabkan registrasi gagal (must fix)
 * - 'warning' : masalah yang mungkin membuat peserta bingung (should fix)
 * - 'info'    : saran untuk meningkatkan kualitas form (nice to have)
 */
readonly class QualityWarning
{
    public function __construct(
        public string  $severity,    // 'error' | 'warning' | 'info'
        public string  $code,        // machine-readable identifier, e.g. 'duplicate_field'
        public string  $message,     // human-readable explanation
        public ?string $field,       // field label yang bermasalah (nullable)
        public ?string $suggestion,  // saran perbaikan (nullable)
    ) {}

    public static function error(string $code, string $message, ?string $field = null, ?string $suggestion = null): self
    {
        return new self('error', $code, $message, $field, $suggestion);
    }

    public static function warning(string $code, string $message, ?string $field = null, ?string $suggestion = null): self
    {
        return new self('warning', $code, $message, $field, $suggestion);
    }

    public static function info(string $code, string $message, ?string $field = null, ?string $suggestion = null): self
    {
        return new self('info', $code, $message, $field, $suggestion);
    }

    public function toArray(): array
    {
        return [
            'severity'   => $this->severity,
            'code'       => $this->code,
            'message'    => $this->message,
            'field'      => $this->field,
            'suggestion' => $this->suggestion,
        ];
    }
}
