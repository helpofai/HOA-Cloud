<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Security\Controllers\GhostHopController;

Route::middleware(['web'])->group(function () {
    // Layer 1: Public Link Entry
    Route::get('/v/{uuid}', [GhostHopController::class, 'entry'])->name('ghost-hop.entry');

    // Layer 2: Verification Hop
    Route::get('/verify/{hash}', [GhostHopController::class, 'verify'])->name('ghost-hop.verify');
    Route::post('/verify/process', [GhostHopController::class, 'processVerification'])->name('ghost-hop.verify.process');
});
