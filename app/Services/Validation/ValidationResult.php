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
        public readonly bool $isHalt = false,
    ) {}

    public static function pass(string $message, string $newStatus): self
    {
        return new self(true, $message, $newStatus);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }

    public static function halt(string $message): self
    {
        return new self(false, $message, null, true);
    }
}
