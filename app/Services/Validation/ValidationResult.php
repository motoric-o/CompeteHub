<?php

namespace App\Services\Validation;

use App\Models\Registration;

/**
 * ValidationResult — data class untuk hasil validasi CoR.
 */
class ValidationResult
{
    public function __construct(
        public readonly bool $passed,
        public readonly string $message,
        public readonly ?string $newStatus = null,
    ) {}

    public static function pass(string $message, string $newStatus): self
    {
        return new self(true, $message, $newStatus);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }
}
