<?php

namespace App\Core\Competition;

use App\Models\Competition;
use App\Models\ScoringType;
use App\Models\Submission;
use App\Models\User;
use App\Core\Scoring\TimeBasedScoringStrategy;
use App\Core\Scoring\JudgeBasedScoringStrategy;
use App\Core\Scoring\ScoringStrategy;
use Illuminate\Support\Carbon;

abstract class AbstractCompetition
{
    protected Competition $competition;
    protected ScoringStrategy $scoring;

    public function __construct(Competition $competition)
    {
        $competition->load('scoringType');
        $this->competition = $competition;
        $this->scoring = $this->resolveScoringStrategy($competition);
    }

    protected function resolveScoringStrategy(Competition $competition): ScoringStrategy
    {
        if ($competition->scoringType && $competition->scoringType->name === 'Time Based') {
            return new TimeBasedScoringStrategy($competition->time_scoring_threshold ?? 0);
        } else if ($competition->scoringType && $competition->scoringType->name === 'Judge Score') {
            return new JudgeBasedScoringStrategy();
        }

        return throw new \InvalidArgumentException('Invalid scoring type');
    }

    public function isRegistrationOpen(): bool
    {
        $now = Carbon::now();
        
        $inWindow = $now->between(
            $this->competition->registration_start,
            $this->competition->registration_end
        );

        $hasQuota = is_null($this->competition->quota) || 
                    $this->competition->submissions()->count() < $this->competition->quota;

        return $inWindow && $hasQuota && $this->competition->status === 'open';
    }

    public function isActive(): bool
    {
        $now = Carbon::now();
        
        return $now->between(
            $this->competition->start_date,
            $this->competition->end_date
        ) && $this->competition->status === 'ongoing';
    }

    public function canUserRegister(User $user): bool
    {
        if (!$this->isRegistrationOpen()) {
            return false;
        }

        return !$this->competition->submissions()->where('user_id', $user->id)->exists();
    }

    public function calculateScore(Submission $submission): float
    {
        $scoreData = $this->getScoringData($submission);
        $finalScore = $this->scoring->calculate($scoreData);
        
        $submission->update(['final_score' => $finalScore]);
        
        return (float) $finalScore;
    }

    protected function getScoringData(Submission $submission)
    {
        if ($this->scoring instanceof TimeBasedScoringStrategy) {
            return $submission;
        }

        return $submission->scores;
    }


    public function getLeaderboard()
    {
        return $this->competition->submissions()
            ->with('user')
            ->orderByDesc('final_score')
            ->get();
    }

    abstract public function getRules(): string;

    abstract public function validateSubmission(array $data): bool;
}

