<?php

namespace Tests\Feature\Scoring;

use App\Models\User;
use App\Models\Competition;
use App\Models\Round;
use App\Models\ScoringType;
use App\Models\Registration;
use App\Models\Submission;
use App\Core\Scoring\TimeBasedScoringStrategy;
use App\Services\Scoring\SubmissionScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TimeBasedScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_time_based_scoring_strategy_calculates_score_correctly()
    {
        $strategy = new TimeBasedScoringStrategy(120.0);

        $subA = new class { public $time_taken = 0.0; };
        $this->assertEquals(100.0, $strategy->calculate($subA));

        $subB = new class { public $time_taken = 30.0; };
        $this->assertEquals(75.0, $strategy->calculate($subB));

        $subC = new class { public $time_taken = 120.0; };
        $this->assertEquals(0.0, $strategy->calculate($subC));

        $subD = new class { public $time_taken = 150.0; };
        $this->assertEquals(0.0, $strategy->calculate($subD));

        $strategyZero = new TimeBasedScoringStrategy(0.0);
        $this->assertEquals(0.0, $strategyZero->calculate($subB));
    }



    public function test_submission_scoring_service_calculates_score_automatically_for_time_based_rounds()
    {
        $startDate = Carbon::create(2026, 5, 22, 9, 0, 0);
        Carbon::setTestNow($startDate);

        $committee = User::factory()->create(['role' => 'committee']);
        $competition = Competition::create([
            'user_id' => $committee->id,
            'name' => 'CP Cup Test',
            'type' => 'individual',
            'time_scoring_threshold' => 120.0,
            'status' => 'open'
        ]);

        $scoringType = ScoringType::create(['name' => 'Time Based']);

        $round = Round::create([
            'competition_id' => $competition->id,
            'scoring_type_id' => $scoringType->id,
            'name' => 'Final Round',
            'round_order' => 1,
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addHours(3),
            'status' => 'active'
        ]);

        $p1 = User::factory()->create(['role' => 'participant']);
        $p2 = User::factory()->create(['role' => 'participant']);
        $p3 = User::factory()->create(['role' => 'participant']);

        Registration::create(['competition_id' => $competition->id, 'user_id' => $p1->id, 'status' => 'payment_ok']);
        Registration::create(['competition_id' => $competition->id, 'user_id' => $p2->id, 'status' => 'payment_ok']);
        Registration::create(['competition_id' => $competition->id, 'user_id' => $p3->id, 'status' => 'payment_ok']);

        Carbon::setTestNow($startDate->copy()->addMinutes(30));
        $sub1 = Submission::create([
            'competition_id' => $competition->id,
            'round_id' => $round->id,
            'user_id' => $p1->id,
            'status' => 'submitted',
            'revision_count' => 0
        ]);

        Carbon::setTestNow($startDate->copy()->addMinutes(60));
        $sub2 = Submission::create([
            'competition_id' => $competition->id,
            'round_id' => $round->id,
            'user_id' => $p2->id,
            'status' => 'submitted',
            'revision_count' => 0
        ]);

        $service = new SubmissionScoringService();
        $service->recalculateAllTimeBonuses($competition, $round);

        $sub1->refresh();
        $sub2->refresh();

        $this->assertEquals(75.0, (float)$sub1->final_score);
        $this->assertEquals('scored', $sub1->status);

        $this->assertEquals(50.0, (float)$sub2->final_score);
        $this->assertEquals('scored', $sub2->status);

        $this->assertEquals(5.0, (float)$sub1->time_bonus);
        $this->assertEquals(0.0, (float)$sub2->time_bonus);

        Carbon::setTestNow(null);
    }

    public function test_participant_submitting_solution_gets_automatically_scored()
    {
        $startDate = Carbon::create(2026, 5, 22, 9, 0, 0);
        Carbon::setTestNow($startDate);

        $committee = User::factory()->create(['role' => 'committee']);
        $competition = Competition::create([
            'user_id' => $committee->id,
            'name' => 'CP Cup Test HTTP',
            'type' => 'individual',
            'time_scoring_threshold' => 60.0,
            'status' => 'open'
        ]);

        $scoringType = ScoringType::create(['name' => 'Time Based']);

        $round = Round::create([
            'competition_id' => $competition->id,
            'scoring_type_id' => $scoringType->id,
            'name' => 'Final Round',
            'round_order' => 1,
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addHours(2),
            'status' => 'active'
        ]);

        $participant = User::factory()->create(['role' => 'participant']);
        Registration::create([
            'competition_id' => $competition->id,
            'user_id' => $participant->id,
            'status' => 'payment_ok'
        ]);

        Carbon::setTestNow($startDate->copy()->addMinutes(15));

        $response = $this->actingAs($participant)
            ->post(route('participant.submissions.store', [$competition, $round]), [
                'submission_url' => 'https://github.com/test/repo',
            ]);

        $response->assertRedirect(route('participant.submissions.index', $competition));

        $submission = Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->where('user_id', $participant->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertEquals(75.0, (float)$submission->final_score);
        $this->assertEquals('scored', $submission->status);
        $this->assertEquals(5.0, (float)$submission->time_bonus);

        Carbon::setTestNow(null);
    }
}
