<?php

namespace App\Modules\Streaming\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamingService
{
    /**
     * Professional Stream Controller with HTTP 206 Partial Content support.
     * Prevents full file buffering and allows instant seeking.
     * 
     * @param int|null $speedLimitKB Speed limit in KB/s (null for unlimited)
     */
    public function stream(string $path, string $mimeType, string $filename, ?int $speedLimitKB = null): StreamedResponse
    {
        $size = filesize($path);
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        if (request()->headers->has('Range')) {
            $range = request()->header('Range');
            if (preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $size - 1;
            }

            // Boundary checks
            $start = max(0, min($start, $size - 1));
            $end = max($start, min($end, $size - 1));

            $headers['Content-Length'] = ($end - $start) + 1;
            $headers['Content-Range'] = "bytes $start-$end/$size";
            $status = 206;
        } else {
            $headers['Content-Length'] = $size;
            $status = 200;
        }

        return response()->stream(function () use ($path, $start, $end, $speedLimitKB) {
            $stream = fopen($path, 'rb');
            if (!$stream) return;

            fseek($stream, $start);

            $remaining = ($end - $start) + 1;
            
            // Adjust chunk size based on speed limit or defaults
            // For throttled streams, we use smaller chunks to maintain smooth flow
            $chunkSize = $speedLimitKB ? min(64 * 1024, $speedLimitKB * 1024) : 1024 * 1024; // 64KB or 1MB

            while ($remaining > 0 && !feof($stream) && !connection_aborted()) {
                $startTime = microtime(true);
                
                $readSize = min($remaining, $chunkSize);
                $data = fread($stream, $readSize);
                echo $data;
                flush();
                
                $remaining -= $readSize;

                if ($speedLimitKB > 0) {
                    $elapsedTime = microtime(true) - $startTime;
                    $expectedTime = $readSize / ($speedLimitKB * 1024);
                    
                    if ($elapsedTime < $expectedTime) {
                        usleep(($expectedTime - $elapsedTime) * 1000000);
                    }
                }
            }

            fclose($stream);
        }, $status, $headers);
    }
}
