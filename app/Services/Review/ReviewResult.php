<?php

namespace App\Services\Review;

/**
 * ReviewResult — DTO untuk hasil eksekusi ReviewCommand.
 */
readonly class ReviewResult
{
    public function __construct(
        public bool   $success,
        public string $message,
        public int    $affectedCount,
        public array  $details,        // per-item results for bulk operations
        public ?string $batchId,       // UUID for grouping audit log entries
    ) {}

    public static function success(string $message, int $affectedCount = 1, array $details = [], ?string $batchId = null): self
    {
        return new self(true, $message, $affectedCount, $details, $batchId);
    }

    public static function failure(string $message, array $details = []): self
    {
        return new self(false, $message, 0, $details, null);
    }
}
