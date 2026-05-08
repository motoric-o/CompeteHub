<?php

namespace App\Services\Facade;

use Illuminate\Support\Facades\Log;

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
}
