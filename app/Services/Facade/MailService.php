<?php

namespace App\Services\Facade;

use Illuminate\Support\Facades\Log;

/**
 * MailService — Subsistem pengiriman email.
 *
 * Dibungkus oleh NotificationFacade agar controller tidak perlu
 * berinteraksi langsung dengan API mail yang kompleks.
 */
class MailService
{
    /**
     * Kirim email ke user tertentu.
     *
     * @param string $to      Alamat email tujuan
     * @param string $subject Subject email
     * @param string $body    Isi email (HTML)
     */
    public function send(string $to, string $subject, string $body): bool
    {
        // Implementasi nyata menggunakan Laravel Mail
        try {
            \Illuminate\Support\Facades\Mail::to($to)->send(
                new \App\Mail\EventNotificationMail($subject, $body)
            );
            
            Log::info("MailService: Email sent to {$to}", [
                'subject' => $subject,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("MailService: Failed to send email to {$to}. Error: {$e->getMessage()}");
            return false;
        }

        return true;
    }
}
