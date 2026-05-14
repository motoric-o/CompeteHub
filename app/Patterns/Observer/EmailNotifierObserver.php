<?php

namespace App\Patterns\Observer;

use App\Models\User;

class EmailNotifierObserver implements ObserverInterface
{
    public function update(SubjectInterface $subject, string $event, mixed $data = null): void
    {
        $facade = app(\App\Services\Facade\NotificationFacade::class);

        // Routing aksi berdasarkan event yang dipancarkan oleh subject
        switch ($event) {
            case 'score_published':
                if (isset($data['user_id']) && isset($data['score'])) {
                    $facade->sendResultEmail($data['user_id'], $data['score']);
                }
                break;
                
            case 'registration_accepted':
                if (isset($data['user_id']) && isset($data['competition_id'])) {
                    $facade->sendEmailNotification($data['user_id'], $data['competition_id']);
                }
                break;
                
            case 'leaderboard_changed':
                if (isset($data['user_id']) && isset($data['new_rank'])) {
                    // Jika ada kebutuhan kirim notifikasi perubahan leaderboard
                    $user = User::find($data['user_id']);
                    if ($user) {
                        app(\App\Services\Facade\MailService::class)->send(
                            $user->email,
                            'Perubahan Leaderboard',
                            "Posisi Anda di leaderboard berubah. Ranking baru Anda: {$data['new_rank']}."
                        );
                    }
                }
                break;
        }
    }
}
