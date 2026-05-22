<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Models\MediaProcess;
use Illuminate\Support\Facades\Log;

class FFmpegProcessService
{
    /**
     * Execute an FFmpeg command and track its progress in the database.
     */
    public function execute(MediaProcess $process, array $args): void
    {
        $ffmpeg = config('hoa-cloud.bin.ffmpeg');
        
        // On Windows, ensure it has .exe if missing
        if (PHP_OS_FAMILY === 'Windows' && !str_ends_with($ffmpeg, '.exe')) {
            $ffmpeg .= '.exe';
        }

        // Build command
        $command = "\"$ffmpeg\" " . implode(' ', $args) . " 2>&1";
        
        $process->update([
            'status' => 'processing',
            'command' => $command,
            'pid' => getmypid(),
        ]);

        $duration = $process->file?->duration ?: 0;
        
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr (ffmpeg output is here)
        ];

        $process_res = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process_res)) {
            while (!feof($pipes[1])) {
                $line = fgets($pipes[1]);
                
                // Parse progress: time=00:00:05.12
                if (preg_match('/time=(\d+):(\d+):(\d+\.\d+)/', $line, $matches) && $duration > 0) {
                    $hours = (int) $matches[1];
                    $mins = (int) $matches[2];
                    $secs = (float) $matches[3];
                    
                    $currentTime = ($hours * 3600) + ($mins * 60) + $secs;
                    $progress = min(99, ($currentTime / $duration) * 100);
                    
                    // Throttle DB updates
                    if ($progress > $process->progress + 1) {
                        $process->update(['progress' => $progress]);
                    }
                }
            }

            $return_value = proc_close($process_res);

            if ($return_value === 0) {
                $process->update([
                    'status' => 'completed',
                    'progress' => 100
                ]);
            } else {
                $process->update([
                    'status' => 'failed',
                    'error' => 'FFmpeg exited with code ' . $return_value
                ]);
            }
        }
    }
}
