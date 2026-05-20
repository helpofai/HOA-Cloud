<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Upload\Controllers\UploadController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::match(['get', 'post'], '/upload', [UploadController::class, 'upload'])->name('upload');
});
