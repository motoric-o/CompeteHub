<?php

namespace App\Patterns\Observer;

interface ObserverInterface
{
    public function update(SubjectInterface $subject, string $event, mixed $data = null): void;
}
