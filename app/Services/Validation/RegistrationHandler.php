<?php

namespace App\Services\Validation;

use App\Models\Registration;

/**
 * Abstract handler untuk Chain of Responsibility (CoR) Pattern.
 * Setiap handler memvalidasi 1 tahap registrasi.
 */
abstract class RegistrationHandler
{
    protected ?RegistrationHandler $nextHandler = null;

    /**
     * Set handler berikutnya dalam chain.
     */
    public function setNext(RegistrationHandler $handler): RegistrationHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * Handle validasi. Jika lolos, lanjut ke handler berikutnya.
     */
    public function handle(Registration $registration): ValidationResult
    {
        $result = $this->validate($registration);

        if (! $result->passed) {
            // Gagal di handler ini — reject
            $registration->update([
                'status'           => 'rejected',
                'rejection_reason' => $result->message,
            ]);
            return $result;
        }

        // Update status registrasi
        if ($result->newStatus) {
            $registration->update(['status' => $result->newStatus]);
        }

        // Lanjut ke handler berikutnya (jika ada)
        if ($this->nextHandler) {
            return $this->nextHandler->handle($registration);
        }

        return $result;
    }

    /**
     * Logic validasi spesifik per handler.
     */
    abstract protected function validate(Registration $registration): ValidationResult;
}
