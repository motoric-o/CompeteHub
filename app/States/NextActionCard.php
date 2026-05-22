<?php

namespace App\States;

/**
 * NextActionCard — DTO yang mewakili "apa yang harus dilakukan user sekarang".
 *
 * Immutable value object. Diproduksi oleh RegistrationStateResolver.
 * Digunakan langsung di Blade view participant.
 *
 * Design: readonly class (PHP 8.2) untuk immutability yang terjamin.
 */
readonly class NextActionCard
{
    public function __construct(
        /** State saat ini dalam readable form */
        public string $state,

        /** Judul singkat untuk card */
        public string $title,

        /** Penjelasan lebih detail untuk user */
        public string $description,

        /** Label tombol CTA */
        public string $actionLabel,

        /** URL tombol CTA (null = tidak ada action yang bisa dilakukan user) */
        public ?string $actionUrl,

        /** Untuk styling card: 'info' | 'warning' | 'error' | 'success' | 'neutral' */
        public string $severity,

        /** Ikon Heroicon atau emoji untuk card */
        public string $icon,

        /** Apakah participant bisa melakukan sesuatu sekarang */
        public bool $isActionable,

        /** Pesan deadline jika relevan (nullable) */
        public ?string $deadlineNote,

        /** Langkah-langkah progress untuk timeline (array of step arrays) */
        public array $progressSteps,
    ) {}

    /**
     * Convert ke array untuk Blade atau JSON response.
     */
    public function toArray(): array
    {
        return [
            'state'         => $this->state,
            'title'         => $this->title,
            'description'   => $this->description,
            'action_label'  => $this->actionLabel,
            'action_url'    => $this->actionUrl,
            'severity'      => $this->severity,
            'icon'          => $this->icon,
            'is_actionable' => $this->isActionable,
            'deadline_note' => $this->deadlineNote,
            'progress_steps'=> $this->progressSteps,
        ];
    }
}
