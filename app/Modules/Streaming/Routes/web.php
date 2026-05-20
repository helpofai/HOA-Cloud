<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Streaming\Controllers\StreamingController;

Route::middleware(['web'])->group(function () {
    // Layer 3: The Player Page
    Route::get('/watch/{accessToken}', [StreamingController::class, 'watch'])->name('ghost-hop.watch');

    // Layer 4: The Stream Controller
    Route::get('/stream/media', [StreamingController::class, 'stream'])->name('ghost-hop.stream');
});
