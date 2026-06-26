<?php

namespace App\Services\Registration;

/**
 * PreCheckIssue — DTO untuk hasil validasi pre-submission.
 *
 * Immutable value object. Dibuat via named constructors untuk
 * keterbacaan yang lebih baik di RegistrationPreCheckService.
 */
readonly class PreCheckIssue
{
    public function __construct(
        public string $field,
        public string $message,
        public string $severity, // 'missing' | 'invalid' | 'warning'
    ) {}

    public static function missing(string $field, string $message): self
    {
        return new self($field, $message, 'missing');
    }

    public static function invalid(string $field, string $message): self
    {
        return new self($field, $message, 'invalid');
    }

    public static function warning(string $field, string $message): self
    {
        return new self($field, $message, 'warning');
    }

    public function toArray(): array
    {
        return [
            'field'    => $this->field,
            'message'  => $this->message,
            'severity' => $this->severity,
        ];
    }
}
