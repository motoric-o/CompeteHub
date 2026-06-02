<?php

namespace App\States\SubmissionDeadline;

readonly class DeadlineRiskCard
{
    public function __construct(
        public string $state,
        public string $label,
        public string $message,
        public string $severity,
        public string $badgeClass,
        public string $panelClass,
        public bool $isActionable,
    ) {}

    public function toArray(): array
    {
        return [
            'state' => $this->state,
            'label' => $this->label,
            'message' => $this->message,
            'severity' => $this->severity,
            'badge_class' => $this->badgeClass,
            'panel_class' => $this->panelClass,
            'is_actionable' => $this->isActionable,
        ];
    }
}