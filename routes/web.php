<?php

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
