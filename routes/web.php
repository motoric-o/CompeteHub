<?php

use App\Http\Controllers\Committee\FormTemplateController;
use App\Http\Controllers\Committee\RegistrationVerificationController;
use App\Http\Controllers\Participant\RegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard — generic (redirect by role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'committee'   => redirect()->route('committee.dashboard'),
        'judge'       => redirect()->route('judge.dashboard'),
        default       => redirect()->route('participant.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Committee routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:committee'])->prefix('committee')->name('committee.')->group(function () {
    Route::get('/dashboard', fn () => view('committee.dashboard'))->name('dashboard');

    // F-02: Form Template CRUD
    Route::resource('competitions.form-templates', FormTemplateController::class)
         ->except(['show'])
         ->names('form-templates');
    Route::get('/form-templates/{template}/fields', [FormTemplateController::class, 'getFields'])
         ->name('form-templates.fields');

    // F-10: Registration Verification (CoR)
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

/*
|--------------------------------------------------------------------------
| Judge routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    Route::get('/dashboard', fn () => view('judge.dashboard'))->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Participant routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:participant'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', fn () => view('participant.dashboard'))->name('dashboard');

    // F-02: Registration
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/competitions/{competition}/register', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/competitions/{competition}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/competitions/{competition}/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
