<?php

namespace App\Services\Scoring;

use App\Exceptions\UnauthorizedJudgeException;
use App\Models\JuryAssignment;
use App\Models\Score;
use App\Models\Submission;
use App\Models\ScoringCriterion;

class ScoreProxy implements ScoringServiceInterface
{
    public function __construct(
        private ScoringServiceInterface $realService,
    ) {
    }

    /**
     * Proxy Pattern — verifies judge is assigned to the competition
     * before delegating to the real ScoringService.
     */
    public function submitScore(int $submissionId, int $judgeUserId, array $criteriaScores, ?string $notes = null): Score
    {
        $submission = Submission::findOrFail($submissionId);

        $isAssigned = JuryAssignment::where('user_id', $judgeUserId)
            ->where('competition_id', $submission->competition_id)
            ->exists();

        if (!$isAssigned) {
            throw new UnauthorizedJudgeException(
                'You are not assigned as a judge for this competition.'
            );
        }

        $round = $submission->round;
        if ($round->status === 'finished' || ($round->end_date && now()->isAfter($round->end_date))) {
            throw new \Exception('Maaf, periode penilaian untuk babak ini sudah ditutup.');
        }

        // Validate each criterion score
        $criteria = ScoringCriterion::where('competition_id', $submission->competition_id)->get()->keyBy('id');

        foreach ($criteria as $criterionId => $criterion) {
            if (!isset($criteriaScores[$criterionId])) {
                throw new \InvalidArgumentException("Nilai untuk kriteria '{$criterion->name}' wajib diisi.");
            }
            $val = $criteriaScores[$criterionId];
            if (!is_numeric($val) || $val < 0 || $val > $criterion->max_score) {
                throw new \InvalidArgumentException("Nilai untuk kriteria '{$criterion->name}' harus berupa angka antara 0 dan {$criterion->max_score}.");
            }
        }

        return $this->realService->submitScore($submissionId, $judgeUserId, $criteriaScores, $notes);
    }
}
