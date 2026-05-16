<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\Committee\CompetitionController as CommitteeCompetitionController;
use App\Http\Controllers\Committee\FormTemplateController;
use App\Http\Controllers\Committee\RegistrationVerificationController;
use App\Http\Controllers\Participant\CompetitionController as ParticipantCompetitionController;
use App\Http\Controllers\Participant\RegistrationController;
use App\Http\Controllers\Participant\SubmissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\Judge\ScoringController;
use App\Http\Controllers\LeaderboardController;

use App\Models\Competition;

Route::get('/', function () {
    $competitions = Competition::where('status', 'open')->get();
    return view('welcome', compact('competitions'));
})->name('home');



// Broadcast Email (F-06) — Committee only
Route::middleware(['auth', 'verified', 'role:committee'])->group(function () {
    Route::get('/broadcast', [BroadcastController::class, 'create'])->name('broadcast.create');
    Route::post('/broadcast', [BroadcastController::class, 'store'])->name('broadcast.store');
});

// ── F-07: Manajemen Tim
// Semua route memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::post('/join', [TeamController::class, 'join'])->name('join');
        Route::get('/{team}', [TeamController::class, 'show'])->name('show');

        Route::post('/{team}/kick/{member}', [TeamController::class, 'kick'])->name('kick');
        Route::post('/{team}/leave', [TeamController::class, 'leave'])->name('leave');
        Route::post('/{team}/regenerate-code', [TeamController::class, 'regenerateCode'])->name('regenerateCode');
    });
});

// Dashboard redirect berdasarkan role
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'committee' => redirect()->route('committee.dashboard'),
        'judge' => redirect()->route('judge.dashboard'),
        default => redirect()->route('participant.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Committee Routes
Route::middleware(['auth', 'verified', 'role:committee'])
    ->prefix('committee')
    ->name('committee.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('committee.dashboard'))->name('dashboard');

        Route::get('/competitions', [CommitteeCompetitionController::class, 'index'])
            ->name('competitions.index');

        Route::resource('competitions.form-templates', FormTemplateController::class)
            ->parameters(['form-templates' => 'template'])
            ->except(['show'])
            ->names('form-templates');

        Route::get('/form-templates/{template}/fields', [FormTemplateController::class, 'getFields'])
            ->name('form-templates.fields');

        Route::get('/competitions/{competition}/registrations', [RegistrationVerificationController::class, 'index'])
            ->name('registrations.index');

        Route::get('/competitions/{competition}/registrations/{registration}', [RegistrationVerificationController::class, 'show'])
            ->name('registrations.show');

        Route::post('/competitions/{competition}/registrations/{registration}/validate', [RegistrationVerificationController::class, 'validate'])
            ->name('registrations.validate');

        Route::patch('/documents/{document}/verify', [RegistrationVerificationController::class, 'verifyDocument'])
            ->name('documents.verify');

        Route::patch('/payments/{payment}/verify', [RegistrationVerificationController::class, 'verifyPayment'])
            ->name('payments.verify');

        Route::resource('management/competitions', CommitteeCompetitionController::class)
            ->names('management.competitions');

        // Rounds
        Route::resource('competitions.rounds', \App\Http\Controllers\Committee\RoundController::class)
            ->names('rounds');

        // Brackets
        Route::post('competitions/{competition}/rounds/{round}/brackets/auto-generate', [\App\Http\Controllers\Committee\BracketController::class, 'autoGenerate'])
            ->name('rounds.brackets.auto-generate');
        Route::post('competitions/{competition}/rounds/{round}/brackets', [\App\Http\Controllers\Committee\BracketController::class, 'store'])
            ->name('rounds.brackets.store');
        Route::delete('competitions/{competition}/rounds/{round}/brackets/{bracket}', [\App\Http\Controllers\Committee\BracketController::class, 'destroy'])
            ->name('rounds.brackets.destroy');
        Route::post('competitions/{competition}/rounds/{round}/brackets/{bracket}/winner', [\App\Http\Controllers\Committee\BracketController::class, 'setWinner'])
            ->name('rounds.brackets.winner');
    });

// ── Judge — Penilaian Submisi
Route::middleware(['auth', 'verified', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    Route::get('/dashboard', fn () => view('judge.dashboard'))->name('dashboard');

    // Daftar kompetisi yang ditugaskan ke juri ini
    Route::get('/submissions', [ScoringController::class, 'index'])->name('submissions.index');

    // Lihat semua submisi dalam satu round
    Route::get('/competitions/{competition}/rounds/{round}', [ScoringController::class, 'round'])->name('submissions.round');

    // Lihat detail & beri nilai satu submisi
    Route::get('/competitions/{competition}/submissions/{submission}', [ScoringController::class, 'show'])->name('submissions.show');
    Route::post('/competitions/{competition}/submissions/{submission}/score', [ScoringController::class, 'store'])->name('submissions.score');
});

// ── Participant Routes
Route::middleware(['auth', 'verified', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('participant.dashboard'))->name('dashboard');

        Route::get('/competitions', [ParticipantCompetitionController::class, 'index'])
            ->name('competitions.index');

        Route::get('/registrations', [RegistrationController::class, 'index'])
            ->name('registrations.index');

        Route::get('/competitions/{competition}/register', [RegistrationController::class, 'create'])
            ->name('registrations.create');

        Route::post('/competitions/{competition}/register', [RegistrationController::class, 'store'])
            ->name('registrations.store');

        Route::get('/competitions/{competition}/registrations/{registration}', [RegistrationController::class, 'show'])
            ->name('registrations.show');

        Route::get('/competitions/{competition}/registrations/{registration}/certificate', [RegistrationController::class, 'downloadCertificate'])
            ->name('registrations.certificate');

        Route::get('/competitions/{competition}/submissions', [SubmissionController::class, 'index'])
            ->name('submissions.index');

        Route::get('/competitions/{competition}/rounds/{round}/submit', [SubmissionController::class, 'create'])
            ->name('submissions.create');

        Route::post('/competitions/{competition}/rounds/{round}/submit', [SubmissionController::class, 'store'])
            ->name('submissions.store');
    });

// ── Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ── Leaderboard (real-time polling)
Route::middleware(['auth'])->group(function () {
    Route::get('/leaderboards', [LeaderboardController::class, 'list'])->name('leaderboards.list');
    Route::get('/competitions/{competition}/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/api/competitions/{competition}/leaderboard', [LeaderboardController::class, 'apiData'])->name('leaderboard.api');
});

require __DIR__ . '/auth.php';
