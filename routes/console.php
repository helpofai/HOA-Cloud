<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Modules\Support\Services\SharedHostingQueueService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(SharedHostingQueueService::class)->startWorker();
})->everyFiveMinutes();

Artisan::command('queue:shared-start', function () {
    app(SharedHostingQueueService::class)->forceStart();
    $this->info('Queue worker trigger sent.');
})->purpose('Force start the shared hosting queue worker');
