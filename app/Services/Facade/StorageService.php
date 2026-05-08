<?php

namespace App\Services\Facade;

use Illuminate\Support\Facades\Log;

/**
 * StorageService — Subsistem penyimpanan file.
 *
 * Abstraksi di atas Laravel Storage / AWS S3 / cloud storage lainnya.
 * Dibungkus oleh NotificationFacade.
 */
class StorageService
{
    /**
     * Simpan file ke storage.
     *
     * @param string $path     Path tujuan
     * @param string $contents Konten file
     * @param string $disk     Disk yang digunakan (local, s3, dll.)
     *
     * @return string URL publik file yang disimpan
     */
    public function store(string $path, string $contents, string $disk = 'local'): string
    {
        Log::info("StorageService: Stored file at {$path}", [
            'disk' => $disk,
            'size' => strlen($contents),
        ]);

        return $path;
    }

    /**
     * Hapus file dari storage.
     */
    public function delete(string $path, string $disk = 'local'): bool
    {
        Log::info("StorageService: Deleted file at {$path}");

        return true;
    }
}
