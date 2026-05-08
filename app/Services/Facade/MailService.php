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
        // Implementasi nyata akan menggunakan Laravel Mail / Mailgun / SendGrid.
        // Saat ini kita log saja untuk development.
        Log::info("MailService: Sending email to {$to}", [
            'subject' => $subject,
            'body'    => $body,
        ]);

        return true;
    }
}
