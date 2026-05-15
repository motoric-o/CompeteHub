<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\Committee\CompetitionController as CommitteeCompetitionController;
use App\Http\Controllers\Committee\FormTemplateController;
use App\Http\Controllers\Committee\RegistrationVerificationController;
use App\Http\Controllers\Participant\CompetitionController as ParticipantCompetitionController;
use App\Http\Controllers\Participant\RegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\Participant\SubmissionController;

Route::get('/', function () {
    // Mock login for testing to bypass auth middleware
    auth()->loginUsingId(5); // 5 = Budi Santoso (Participant)
    return redirect()->route('teams.index');
});

// Route untuk Broadcast Email (F-06)
Route::middleware(['auth'])->group(function () {
    Route::get('/broadcast', [BroadcastController::class, 'create'])->name('broadcast.create');
    Route::post('/broadcast', [BroadcastController::class, 'store'])->name('broadcast.store');
});

// ── F-07: Manajemen Tim ────────────────────────────────────
// Semua route memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Tim — CRUD & manajemen anggota
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/',           [TeamController::class, 'index'])->name('index');
        Route::get('/create',     [TeamController::class, 'create'])->name('create');
        Route::post('/',          [TeamController::class, 'store'])->name('store');
        Route::post('/join',      [TeamController::class, 'join'])->name('join');
        Route::get('/{team}',     [TeamController::class, 'show'])->name('show');

        // Aksi anggota
        Route::post('/{team}/kick/{member}',      [TeamController::class, 'kick'])->name('kick');
        Route::post('/{team}/leave',              [TeamController::class, 'leave'])->name('leave');
        Route::post('/{team}/regenerate-code',    [TeamController::class, 'regenerateCode'])->name('regenerateCode');
    });
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'committee' => redirect()->route('committee.dashboard'),
        'judge' => redirect()->route('judge.dashboard'),
        default => redirect()->route('participant.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified', 'role:committee'])->prefix('committee')->name('committee.')->group(function () {
    Route::get('/dashboard', fn() => view('committee.dashboard'))->name('dashboard');

    Route::get('/competitions', [CommitteeCompetitionController::class, 'index'])->name('competitions.index');

    Route::resource('competitions.form-templates', FormTemplateController::class)
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
});


Route::middleware(['auth', 'verified', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    Route::get('/dashboard', fn() => view('judge.dashboard'))->name('dashboard');
    Route::get('/competitions', [\App\Http\Controllers\Judge\CompetitionController::class, 'index'])->name('competitions.index');
    Route::get('/competitions/{competition}/submissions', [\App\Http\Controllers\Judge\SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/competitions/{competition}/submissions/{submission}/score', [\App\Http\Controllers\Judge\SubmissionController::class, 'score'])->name('submissions.score');
});

Route::middleware(['auth', 'verified', 'role:participant'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', fn() => view('participant.dashboard'))->name('dashboard');

    Route::get('/competitions', [ParticipantCompetitionController::class, 'index'])->name('competitions.index');

    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/competitions/{competition}/register', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/competitions/{competition}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/competitions/{competition}/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::get('/competitions/{competition}/registrations/{registration}/certificate', [RegistrationController::class, 'downloadCertificate'])->name('registrations.certificate');

    // Submissions
    Route::get('/competitions/{competition}/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/competitions/{competition}/rounds/{round}/submit', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/competitions/{competition}/rounds/{round}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
