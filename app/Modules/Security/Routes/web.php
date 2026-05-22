<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Security\Controllers\GhostHopController;

Route::middleware(['web'])->group(function () {
    // Layer 1: Public Link Entry (Direct & Share)
    Route::get('/v/{uuid}', [GhostHopController::class, 'entry'])->name('ghost-hop.entry');
    Route::get('/s/{slug}', [GhostHopController::class, 'entryByShare'])->name('ghost-hop.share');

    // Layer 2: Verification Hop
    Route::get('/verify/{hash}', [GhostHopController::class, 'verify'])->name('ghost-hop.verify');
    Route::post('/verify/process', [GhostHopController::class, 'processVerification'])->name('ghost-hop.verify.process');

    // Abuse Reporting
    Route::get('/report-abuse', [GhostHopController::class, 'reportForm'])->name('ghost-hop.report');
    Route::post('/report-abuse', [GhostHopController::class, 'submitReport'])->name('ghost-hop.report.submit');
});
