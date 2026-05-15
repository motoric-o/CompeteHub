<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Round;
use App\Models\Submission;
use App\Http\Requests\Participant\StoreSubmissionRequest;
use App\Services\Scoring\SubmissionScoringService;
use App\Patterns\Observer\ScoringSubject;
use App\Patterns\Observer\LeaderboardObserver;
use App\Patterns\Observer\EmailNotifierObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    private SubmissionScoringService $scoringService;

    public function __construct(SubmissionScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    
    private function verifyRegistration(Competition $competition)
    {
        $user = auth()->user();

        $query = Registration::where('competition_id', $competition->id);

        if ($competition->isTeamBased()) {
            $team = $user->teams()->where('competition_id', $competition->id)->first();
            if (!$team) {
                abort(403, 'You are not in a team for this competition.');
            }
            $query->where('team_id', $team->id);
        } else {
            $query->where('user_id', $user->id);
        }

        $registration = $query->first();

        if (!$registration || !in_array($registration->status, ['verified', 'payment_ok'])) {
            abort(403, 'Your registration is not verified yet.');
        }

        if ($competition->isTeamBased()) {
            $team = $registration->team;
            if (!$team->isCaptain($user)) {
                abort(403, 'Only the team captain can upload submissions.');
            }
            return ['registration' => $registration, 'team_id' => $team->id, 'user_id' => null];
        }

        return ['registration' => $registration, 'team_id' => null, 'user_id' => $user->id];
    }

    private function findExistingSubmission(Competition $competition, Round $round, array $data): ?Submission
    {
        return Submission::where('competition_id', $competition->id)
            ->where('round_id', $round->id)
            ->where(function ($q) use ($data) {
                if ($data['team_id']) {
                    $q->where('team_id', $data['team_id']);
                } else {
                    $q->where('user_id', $data['user_id']);
                }
            })->first();
    }

    public function index(Competition $competition)
    {
        $data = $this->verifyRegistration($competition);

        $rounds = $competition->rounds()->orderBy('round_order')->get();

        $submissions = Submission::where('competition_id', $competition->id);
        if ($data['team_id']) {
            $submissions->where('team_id', $data['team_id']);
        } else {
            $submissions->where('user_id', $data['user_id']);
        }
        $submissions = $submissions->get()->keyBy('round_id');

        // Pass max revisions to view
        $maxRevisions = SubmissionScoringService::MAX_REVISIONS;

        return view('participant.submissions.index', compact('competition', 'rounds', 'submissions', 'maxRevisions'));
    }

    public function create(Competition $competition, Round $round)
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $data = $this->verifyRegistration($competition);

        if ($round->start_date && $round->start_date > now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'This round has not started yet.');
        }
        if ($round->end_date && $round->end_date < now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'The deadline for this round has passed.');
        }

        $submission = $this->findExistingSubmission($competition, $round, $data);

        // Block if revision limit reached
        if ($submission && $submission->revision_count >= SubmissionScoringService::MAX_REVISIONS) {
            return redirect()->route('participant.submissions.index', $competition)
                ->with('error', 'Batas revisi telah tercapai (maksimal ' . SubmissionScoringService::MAX_REVISIONS . ' kali revisi).');
        }

        $bonusPreview = $this->scoringService->previewNextTimeBonus($competition, $round, $submission);

        return view('participant.submissions.create', compact('competition', 'round', 'submission', 'bonusPreview'));
    }

    public function store(StoreSubmissionRequest $request, Competition $competition, Round $round)
    {
        if ($round->competition_id !== $competition->id) {
            abort(404);
        }

        $data = $this->verifyRegistration($competition);

        if ($round->end_date && $round->end_date < now()) {
            return redirect()->route('participant.submissions.index', $competition)->with('error', 'The deadline for this round has passed.');
        }

        $file = $request->file('submission_file');
        $filePath = $file->store('submissions/' . $competition->id . '/' . $round->id, 'public');

        $submission = $this->findExistingSubmission($competition, $round, $data);

        // Block if revision limit reached
        if ($submission && $submission->revision_count >= SubmissionScoringService::MAX_REVISIONS) {
            return redirect()->route('participant.submissions.index', $competition)
                ->with('error', 'Batas revisi telah tercapai (maksimal ' . SubmissionScoringService::MAX_REVISIONS . ' kali revisi).');
        }

        // Set up Observer pattern for leaderboard + email notification
        $subject = new ScoringSubject();
        $subject->attach(new LeaderboardObserver());
        $subject->attach(new EmailNotifierObserver());

        if ($submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $newRevisionCount = $submission->revision_count + 1;

            $submission->update([
                'file_path'      => $filePath,
                'file_type'      => $file->getClientOriginalExtension(),
                'file_size'      => $file->getSize(),
                'status'         => 'submitted',
                'revision_count' => $newRevisionCount,
            ]);

            $this->scoringService->recalculateAllTimeBonuses($competition, $round);
            $submission->refresh();

            $subject->notify('submission_revised', [
                'submission_id'  => $submission->id,
                'user_id'        => $submission->user_id ?? $submission->team?->user_id,
                'revision_count' => $newRevisionCount,
            ]);

            $revisionsLeft = SubmissionScoringService::MAX_REVISIONS - $newRevisionCount;
            $message = "Revisi #{$newRevisionCount} berhasil! Sisa revisi: {$revisionsLeft} kali.";

        } else {
            $submission = Submission::create([
                'competition_id' => $competition->id,
                'round_id'       => $round->id,
                'user_id'        => $data['user_id'],
                'team_id'        => $data['team_id'],
                'file_path'      => $filePath,
                'file_type'      => $file->getClientOriginalExtension(),
                'file_size'      => $file->getSize(),
                'status'         => 'submitted',
                'revision_count' => 0,
                'time_bonus'     => 0,
            ]);

            $this->scoringService->recalculateAllTimeBonuses($competition, $round);
            $submission->refresh();
            $timeBonus = $submission->time_bonus ?? 0;

            $subject->notify('submission_created', [
                'submission_id' => $submission->id,
                'user_id'       => $submission->user_id ?? $submission->team?->user_id,
                'time_bonus'    => $timeBonus,
            ]);

            $message = "Submission berhasil diupload! Time bonus: {$timeBonus} pts. Anda masih bisa revisi " . SubmissionScoringService::MAX_REVISIONS . " kali.";
        }

        return redirect()->route('participant.submissions.index', $competition)->with('success', $message);
    }
}
