<?php

use App\Models\Submission;
use App\Models\Score;
use App\Models\QuizAnswer;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$submission = Submission::whereHas('competition', function ($q) {
    $q->where('competition_system', 'quiz');
})->latest()->first();

if (!$submission) {
    echo "No submission found\n";
    exit;
}

echo "Submission ID: " . $submission->id . "\n";
echo "Competition: " . $submission->competition->name . " (System: " . $submission->competition->competition_system . ", Scoring Type: " . $submission->competition->scoringType->name . ")\n";
echo "Status: " . $submission->status . "\n";
echo "Final Score: " . $submission->final_score . "\n";
echo "Time Bonus: " . $submission->time_bonus . "\n";
echo "Total Score (Dynamic Attribute): " . $submission->total_score . "\n";

echo "\nQuiz Answers:\n";
foreach ($submission->quizAnswers as $answer) {
    echo "  - ID: " . $answer->id . ", Question: " . $answer->question->question_text . " (" . $answer->question->question_type . ")\n";
    echo "    Answer Text: " . $answer->answer_text . "\n";
    echo "    Is Correct: " . ($answer->is_correct ? 'Yes' : 'No/Null') . "\n";
    echo "    Score: " . $answer->score . "\n";
}

echo "\nScores Table Records:\n";
foreach ($submission->scores as $score) {
    echo "  - Judge: " . $score->judge->name . "\n";
    echo "    Score: " . $score->score . "\n";
}
