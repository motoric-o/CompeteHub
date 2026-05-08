<?php

namespace App\Patterns\Observer;

class ScoringSubject implements SubjectInterface
{
    private array $observers = [];

    public function attach(ObserverInterface $observer): void
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }

    public function detach(ObserverInterface $observer): void
    {
        unset($this->observers[spl_object_hash($observer)]);
    }

    public function notify(string $event, mixed $data = null): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $event, $data);
        }
    }

    // Contoh aksi yang men-trigger notifikasi
    public function publishScore(int $userId, float $score): void
    {
        // ... logika publish score ke database ...
        
        // Notify observers (contoh: EmailNotifierObserver)
        $this->notify('score_published', [
            'user_id' => $userId,
            'score' => $score
        ]);
    }
}
