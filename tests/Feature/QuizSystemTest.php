<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\Round;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\Submission;
use App\Models\ScoringType;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $committee;
    private User $participant;
    private User $judge;
    private ScoringType $quizAutoScoring;
    private ScoringType $judgeScoring;
    private ScoringType $timeBasedScoring;

    protected function setUp(): void
    {
        parent::setUp();

        $this->committee = User::factory()->create(['role' => 'committee']);
        $this->participant = User::factory()->create(['role' => 'participant']);
        $this->judge = User::factory()->create(['role' => 'judge']);

        $this->quizAutoScoring = ScoringType::create(['name' => 'Quiz Automatic']);
        $this->judgeScoring = ScoringType::create(['name' => 'Judge Score']);
        $this->timeBasedScoring = ScoringType::create(['name' => 'Time Based']);
    }

    /**
     * Test creating a quiz competition and adding questions.
     */
    public function test_committee_can_create_quiz_competition_and_manage_questions(): void
    {
        $response = $this->actingAs($this->committee)
            ->post(route('committee.management.competitions.store'), [
                'name' => 'Tech Quiz 2026',
                'description' => 'Test quiz',
                'type' => 'individual',
                'competition_system' => 'quiz',
                'category' => 'Competitive Programming',
                'scoring_type_id' => $this->quizAutoScoring->id,
                'registration_fee' => 0.00,
                'quota' => 100,
                'status' => 'open',
            ]);

        $response->assertRedirect();
        
        $competition = Competition::where('name', 'Tech Quiz 2026')->first();
        $this->assertNotNull($competition);
        $this->assertEquals('quiz', $competition->competition_system);

        // Create a round
        $round = Round::create([
            'competition_id' => $competition->id,
            'name' => 'Penyisihan',
            'round_order' => 1,
            'status' => 'active',
        ]);

        // Add quiz questions
        $response2 = $this->actingAs($this->committee)
            ->post(route('committee.rounds.questions.store', [$competition, $round]), [
                'question_text' => 'What is the capital of Indonesia?',
                'question_type' => 'multiple_choice',
                'options' => ['Jakarta', 'Nusantara', 'Bandung'],
                'correct_answer' => 'Nusantara',
                'points' => 10,
            ]);

        $response2->assertRedirect();
        $this->assertDatabaseHas('quiz_questions', [
            'round_id' => $round->id,
            'question_text' => 'What is the capital of Indonesia?',
            'correct_answer' => 'Nusantara',
        ]);
    }

    /**
     * Test participant taking the quiz and automatic MCQ scoring.
     */
    public function test_participant_can_submit_quiz_answers_and_get_auto_graded(): void
    {
        $competition = Competition::create([
            'user_id' => $this->committee->id,
            'scoring_type_id' => $this->quizAutoScoring->id,
            'name' => 'Auto Graded Quiz',
            'type' => 'individual',
            'competition_system' => 'quiz',
            'status' => 'ongoing',
        ]);

        $round = Round::create([
            'competition_id' => $competition->id,
            'name' => 'Ronde 1',
            'round_order' => 1,
            'status' => 'active',
            'start_date' => now()->subHour(),
            'end_date' => now()->addHours(2),
        ]);

        // Add 2 questions
        $mcq = QuizQuestion::create([
            'round_id' => $round->id,
            'question_text' => '2 + 2 = ?',
            'question_type' => 'multiple_choice',
            'options' => ['3', '4', '5'],
            'correct_answer' => '4',
            'points' => 15,
        ]);

        $essay = QuizQuestion::create([
            'round_id' => $round->id,
            'question_text' => 'Write a short bio',
            'question_type' => 'essay',
            'points' => 20,
        ]);

        // Register participant
        Registration::create([
            'competition_id' => $competition->id,
            'user_id' => $this->participant->id,
            'status' => 'verified',
        ]);

        // Submit quiz answers (one correct MC, one essay)
        $response = $this->actingAs($this->participant)
            ->post(route('participant.submissions.store', [$competition, $round]), [
                'answers' => [
                    $mcq->id => '4', // correct answer
                    $essay->id => 'I am a software engineer',
                ]
            ]);

        $response->assertRedirect();
        
        // Verify submission was created
        $submission = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->where('user_id', $this->participant->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertNull($submission->file_path); // Null file path for quiz
        
        // Verify quiz answers in DB
        $this->assertDatabaseHas('quiz_answers', [
            'submission_id' => $submission->id,
            'question_id' => $mcq->id,
            'answer_text' => '4',
            'is_correct' => true,
            'score' => 15.00,
        ]);

        $this->assertDatabaseHas('quiz_answers', [
            'submission_id' => $submission->id,
            'question_id' => $essay->id,
            'answer_text' => 'I am a software engineer',
            'is_correct' => null, // Essay starts with null correctness
            'score' => 0.00, // Essay score starts at 0 before judge review
        ]);

        // Verify automatic score calculation
        // For Quiz Automatic, final score is the sum of correct MCQ points
        $this->assertEquals(15.00, $submission->fresh()->final_score);
        $this->assertEquals('scored', $submission->fresh()->status);
    }

    /**
     * Test judge grading quiz essay answers.
     */
    public function test_judge_can_grade_quiz_essay_answers(): void
    {
        $competition = Competition::create([
            'user_id' => $this->committee->id,
            'scoring_type_id' => $this->judgeScoring->id, // Judge Score
            'name' => 'Judge Graded Quiz',
            'type' => 'individual',
            'competition_system' => 'quiz',
            'status' => 'ongoing',
        ]);

        $round = Round::create([
            'competition_id' => $competition->id,
            'name' => 'Ronde 1',
            'round_order' => 1,
            'status' => 'active',
            'start_date' => now()->subHour(),
            'end_date' => now()->addHours(2),
        ]);

        // MCQ (10 points)
        $mcq = QuizQuestion::create([
            'round_id' => $round->id,
            'question_text' => '2 + 2 = ?',
            'question_type' => 'multiple_choice',
            'options' => ['3', '4', '5'],
            'correct_answer' => '4',
            'points' => 10,
        ]);

        // Essay (10 points)
        $essay = QuizQuestion::create([
            'round_id' => $round->id,
            'question_text' => 'Write a short bio',
            'question_type' => 'essay',
            'points' => 10,
        ]);

        // Register participant
        Registration::create([
            'competition_id' => $competition->id,
            'user_id' => $this->participant->id,
            'status' => 'verified',
        ]);

        // Submit quiz answers (one correct MC, one essay)
        $submission = Submission::create([
            'competition_id' => $competition->id,
            'round_id'       => $round->id,
            'user_id'        => $this->participant->id,
            'status'         => 'submitted',
            'revision_count' => 0,
        ]);

        // Create answers
        $mcqAnswer = QuizAnswer::create([
            'submission_id' => $submission->id,
            'question_id' => $mcq->id,
            'answer_text' => '4', // correct MCQ answer
            'is_correct' => true,
            'score' => 10.00,
        ]);

        $essayAnswer = QuizAnswer::create([
            'submission_id' => $submission->id,
            'question_id' => $essay->id,
            'answer_text' => 'My answer',
            'is_correct' => null,
            'score' => 0.00,
        ]);

        // Register Judge Assignment
        \App\Models\JuryAssignment::create([
            'user_id' => $this->judge->id,
            'competition_id' => $competition->id,
        ]);

        // Judge grading essay answer with score 10
        $response = $this->actingAs($this->judge)
            ->post(route('judge.submissions.score', [$competition, $submission]), [
                'criteria' => [
                    $essayAnswer->id => 10, // give 10 points for essay
                ],
                'notes' => 'Excellent answer',
            ]);

        $response->assertRedirect();

        // Verify total score of quiz (MCQ (10) + Essay (10) = 20)
        $submission->refresh();
        $this->assertEquals(20.00, floatval($submission->final_score));
        $this->assertEquals('scored', $submission->status);
        $this->assertEquals(10.00, floatval($essayAnswer->fresh()->score));
    }
}
