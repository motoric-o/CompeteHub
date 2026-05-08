<?php

namespace App\Services\Validation;

use App\Models\Registration;

/**
 * RegistrationValidator — menjalankan Chain of Responsibility:
 * AccountStatusHandler → DocumentCompletionHandler → PaymentVerificationHandler
 */
class RegistrationValidator
{
    /**
     * Jalankan validasi penuh terhadap registrasi.
     */
    public function validate(Registration $registration): ValidationResult
    {
        // Build the chain
        $accountHandler  = new AccountStatusHandler();
        $documentHandler = new DocumentCompletionHandler();
        $paymentHandler  = new PaymentVerificationHandler();

        $accountHandler
            ->setNext($documentHandler)
            ->setNext($paymentHandler);

        // Load relationships
        $registration->load(['user', 'team.captain', 'documents', 'payment', 'competition.formTemplates']);

        // Run chain
        return $accountHandler->handle($registration);
    }
}
