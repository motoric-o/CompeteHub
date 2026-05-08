<?php

namespace App\Services\Validation;

use App\Models\Registration;

/**
 * CoR Tahap 3 — Verifikasi Pembayaran.
 *
 * Cek apakah:
 * - Jika fee = 0, otomatis 'free'
 * - Jika fee > 0, payment harus exist dan status = 'paid'
 */
class PaymentVerificationHandler extends RegistrationHandler
{
    protected function validate(Registration $registration): ValidationResult
    {
        $competition = $registration->competition;

        // Kompetisi gratis — auto pass
        if ($competition->registration_fee <= 0) {
            // Auto-create payment record if not exist
            $registration->payment()->updateOrCreate(
                ['registration_id' => $registration->id],
                ['amount' => 0, 'status' => 'free', 'verified_at' => now()]
            );

            return ValidationResult::pass('Free registration — no payment required.', 'payment_ok');
        }

        // Cek payment record
        $payment = $registration->payment;

        if (! $payment) {
            return ValidationResult::fail('Payment record not found. Please complete payment.');
        }

        if ($payment->status === 'unpaid') {
            return ValidationResult::fail('Payment has not been made yet.');
        }

        if ($payment->status === 'pending_verification') {
            return ValidationResult::fail('Payment is pending verification by committee.');
        }

        if ($payment->status === 'paid') {
            return ValidationResult::pass('Payment verified.', 'payment_ok');
        }

        return ValidationResult::fail('Unknown payment status.');
    }
}
