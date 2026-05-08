<?php

use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Mock login for testing to bypass auth middleware
    auth()->loginUsingId(5); // 5 = Budi Santoso (Participant)
    return redirect()->route('teams.index');
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
