<?php

namespace App\Core\Scoring;

class QuizAutomaticScoringStrategy implements ScoringStrategy
{
    /**
     * Calculate score based on correct multiple choice answers in the quiz.
     *
     * @param mixed $quizAnswers
     * @return float
     */
    public function calculate($quizAnswers)
    {
        if (!$quizAnswers || $quizAnswers->isEmpty()) {
            return 0.0;
        }

        $totalScore = 0.0;
        foreach ($quizAnswers as $answer) {
            if ($answer->question && $answer->question->question_type === 'multiple_choice') {
                if ($answer->answer_text === $answer->question->correct_answer) {
                    $totalScore += $answer->question->points;
                }
            } else {
                $totalScore += (float) ($answer->score ?? 0.0);
            }
        }

        return $totalScore;
    }
}
