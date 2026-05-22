<?php

namespace App\Modules\Media\Jobs;

use App\Modules\File\Models\File;
use App\Modules\Media\Models\MediaProcess;
use App\Modules\Media\Services\FFmpegProcessService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TranscodeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $fileId,
        protected int $userId,
        protected string $targetFormat = 'mp4'
    ) {}

    public function handle(FFmpegProcessService $service): void
    {
        $file = File::find($this->fileId);
        if (!$file) return;

        // Create Process record
        $process = MediaProcess::create([
            'user_id' => $this->userId,
            'file_id' => $file->id,
            'file_uuid' => $file->uuid,
            'type' => 'transcode',
            'status' => 'pending',
            'progress' => 0,
        ]);

        $inputPath = storage_path("app/private/" . $file->disk_name);
        if (!file_exists($inputPath)) {
            $inputPath = storage_path("app/private/uploads/" . $file->disk_name);
        }

        if (!file_exists($inputPath)) {
            $process->update(['status' => 'failed', 'command' => 'Source file not found.']);
            return;
        }

        $user = \App\Models\User::find($this->userId);
        $userSlug = Str::slug($user?->name ?? 'unknown-user');
        $outputDiskName = "{$userSlug}/Videos/" . Str::random(40) . "." . $this->targetFormat;
        $outputPath = storage_path("app/private/" . $outputDiskName);

        // Ensure output directory exists
        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        // FFmpeg args for a standard H.264 transcode
        $args = [
            "-i \"$inputPath\"",
            "-c:v libx264",
            "-preset fast",
            "-crf 23",
            "-c:a aac",
            "-b:a 128k",
            "-y \"$outputPath\""
        ];

        $service->execute($process, $args);

        if ($process->fresh()->status === 'completed') {
            // Update file record or create new version? 
            // For now, let's just update the file to the new transcoded version
            $oldPath = $inputPath;
            $file->update([
                'disk_name' => $outputDiskName,
                'extension' => $this->targetFormat,
                'mime_type' => 'video/mp4',
                'size' => filesize($outputPath),
            ]);
            
            if (file_exists($oldPath)) unlink($oldPath);
        }
    }
}
