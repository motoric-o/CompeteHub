<?php

namespace App\Core\Competition;

class IndividualCompetition extends AbstractCompetition
{
    public function getRules(): string
    {
        return $this->competition->rules ?? 'Standard individual competition rules apply.';
    }

    public function validateSubmission(array $data): bool
    {
        if (isset($data['team_id']) && !empty($data['team_id'])) {
            return false;
        }

        return true;
    }
}
