<?php

namespace App\Modules\Streaming\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamingService
{
    /**
     * Professional Stream Controller with HTTP 206 Partial Content support.
     * Prevents full file buffering and allows instant seeking.
     */
    public function stream(string $path, string $mimeType, string $filename): StreamedResponse
    {
        $size = filesize($path);
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Accept-Ranges' => 'bytes',
        ];

        if (request()->headers->has('Range')) {
            $range = request()->header('Range');
            preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches);
            $start = intval($matches[1]);
            $end = isset($matches[2]) ? intval($matches[2]) : $size - 1;

            $headers['Content-Length'] = ($end - $start) + 1;
            $headers['Content-Range'] = "bytes $start-$end/$size";
            $status = 206;
        } else {
            $headers['Content-Length'] = $size;
            $status = 200;
        }

        return response()->stream(function () use ($path, $start, $end) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);

            $remaining = ($end - $start) + 1;
            $chunkSize = 8192; // 8KB chunks

            while ($remaining > 0 && !feof($stream)) {
                $read = min($remaining, $chunkSize);
                echo fread($stream, $read);
                flush();
                $remaining -= $read;
            }

            fclose($stream);
        }, $status, $headers);
    }
}
