<?php

namespace App\Services\Facade;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * PDFGenerator — Subsistem pembuatan dokumen PDF.
 *
 * Dibungkus oleh NotificationFacade. Bisa diganti implementasinya
 * (DomPDF, Snappy, dll.) tanpa menyentuh controller.
 */
class PDFGenerator
{
    /**
     * Generate PDF report untuk kompetisi tertentu.
     *
     * @param int    $competitionId ID kompetisi
     * @param string $title         Judul dokumen
     * @param array  $data          Data yang akan dirender
     *
     * @return string Path file PDF yang dihasilkan
     */
    public function generate(int $competitionId, string $title, array $data = []): string
    {
        // Implementasi nyata akan menggunakan DomPDF atau Snappy.
        $path = "reports/competition_{$competitionId}_report.pdf";

        Log::info("PDFGenerator: Generated PDF at {$path}", [
            'title' => $title,
            'data'  => $data,
        ]);

        return $path;
    }

    /**
     * Generate PDF certificate.
     *
     * @param int $userId ID User
     * @param int $competitionId ID Kompetisi
     * @param array $data Data untuk sertifikat (nama user, nama kompetisi, dsb)
     *
     * @return string Path file PDF yang dihasilkan di storage
     */
    public function generateCertificate(int $userId, int $competitionId, array $data): string
    {
        $pdf = Pdf::loadView('pdf.certificate', $data);
        $pdf->setPaper('a4', 'landscape');

        $path = "certificates/competition_{$competitionId}_user_{$userId}.pdf";
        
        Storage::disk('public')->put($path, $pdf->output());

        Log::info("PDFGenerator: Generated Certificate at {$path}", [
            'user_id' => $userId,
            'competition_id' => $competitionId,
        ]);

        return $path;
    }
}
