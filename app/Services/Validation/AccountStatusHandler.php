<?php

namespace App\Services\Validation;

use App\Models\Registration;

class AccountStatusHandler extends RegistrationHandler
{
    protected function validate(Registration $registration): ValidationResult
    {
        $user = $registration->user;

        if (!$user && $registration->team) {
            $user = $registration->team->captain;
        }

        if (!$user) {
            return ValidationResult::fail('No user associated with this registration.');
        }

        if ($user->status === 'suspended') {
            return ValidationResult::fail('Account is suspended. Please contact admin.');
        }

        if (!$user->email_verified_at) {
            return ValidationResult::fail('Email address is not verified. Please verify your email first.');
        }

        return ValidationResult::pass('Account status verified.', 'account_ok');
    }
}
