<?php

namespace App\Modules\Support\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;

class SharedHostingQueueService
{
    protected string $lockKey = 'shared_hosting_queue_worker_lock';

    /**
     * Start the queue worker if it's not already running.
     * Optimized for Linux Shared Hosting environments.
     */
    public function startWorker(): void
    {
        // 1. Check if a worker is already active (Lock expires in 5 minutes)
        if (Cache::has($this->lockKey)) {
            \Illuminate\Support\Facades\Log::debug('Queue worker lock active, skipping start.');
            return;
        }

        // 2. Set the lock
        Cache::put($this->lockKey, true, now()->addMinutes(5));

        // 3. Execute the worker as a background process
        $artisan = base_path('artisan');
        
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows fallback for local dev
            $cmd = "start /B php \"{$artisan}\" queue:work --stop-when-empty --max-time=300 --tries=3";
            \Illuminate\Support\Facades\Log::debug("Starting Windows background worker: {$cmd}");
            pclose(popen($cmd, "r"));
        } else {
            $cmd = "php \"{$artisan}\" queue:work --stop-when-empty --max-time=300 --tries=3 > /dev/null 2>&1 &";
            \Illuminate\Support\Facades\Log::debug("Starting Linux background worker: {$cmd}");
            exec($cmd);
        }
    }

    /**
     * Force start without checking lock
     */
    public function forceStart(): void
    {
        Cache::forget($this->lockKey);
        $this->startWorker();
    }
}
