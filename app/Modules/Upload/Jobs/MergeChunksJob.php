<?php

namespace App\Modules\Upload\Jobs;

use App\Modules\File\Models\File;
use App\Modules\Folder\Models\Folder;
use App\Modules\Media\Jobs\FetchMediaMetadataJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MergeChunksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $identifier,
        protected string $filename,
        protected int $totalChunks,
        protected int $userId,
        protected ?string $folderUuid,
        protected string $fileUuid,
        protected ?int $processId = null
    ) {}

    public function handle(): void
    {
        $pid = null;
        try {
            $pid = $this->processId;
        } catch (\Error $e) {
            // Property not initialized (serialized before the field was added)
        }

        // 1. Get or Create a "Merge" process for immediate UI feedback
        if ($pid) {
            $process = \App\Modules\Media\Models\MediaProcess::find($pid);
            $process?->update([
                'status' => 'processing',
                'progress' => 10,
                'command' => 'Merging chunks for ' . $this->filename,
            ]);
        } else {
            $process = \App\Modules\Media\Models\MediaProcess::create([
                'user_id' => $this->userId,
                'file_uuid' => $this->fileUuid,
                'type' => 'merge',
                'status' => 'processing',
                'progress' => 10,
                'command' => 'Merging chunks for ' . $this->filename,
            ]);
        }

        $disk = Storage::disk('local');
        $chunkDir = "chunks/{$this->identifier}";
        
        // 1. Determine User-Specific Root
        $user = \App\Models\User::find($this->userId);
        $userSlug = Str::slug($user?->name ?? 'unknown-user');
        
        // 2. Determine Category based on Mime Type
        $mime = mime_content_type($disk->path("{$chunkDir}/{$this->filename}.part1")); // Peek at first chunk
        $category = 'Other';
        if (Str::startsWith($mime, 'image/')) $category = 'Photos';
        elseif (Str::startsWith($mime, 'audio/')) $category = 'Music';
        elseif (Str::startsWith($mime, 'video/')) $category = 'Videos';

        $relativeDir = "private/{$userSlug}/{$category}";
        $randomFileName = Str::random(40);
        $relativeFinalPath = "{$relativeDir}/{$randomFileName}";
        $fullFinalPath = $disk->path($relativeFinalPath);

        // Ensure directories exist
        if (!$disk->exists($relativeDir)) {
            $disk->makeDirectory($relativeDir);
        }

        $out = fopen($fullFinalPath, 'wb');

        for ($i = 1; $i <= $this->totalChunks; $i++) {
            $chunkRelativePath = "{$chunkDir}/{$this->filename}.part{$i}";
            $chunkPath = $disk->path($chunkRelativePath);
            
            if (file_exists($chunkPath)) {
                $in = fopen($chunkPath, 'rb');
                stream_copy_to_stream($in, $out);
                fclose($in);
                unlink($chunkPath);
            }
            
            // Update progress occasionally
            if ($i % 5 === 0 || $i === $this->totalChunks) {
                $process->update(['progress' => 10 + (($i / $this->totalChunks) * 80)]);
            }
        }

        fclose($out);
        $disk->deleteDirectory($chunkDir);

        // Calculate Hash for deduplication
        $hash = hash_file('sha256', $fullFinalPath);

        // Get Folder ID
        $folderId = null;
        if ($this->folderUuid) {
            $folder = Folder::where('uuid', $this->folderUuid)->first();
            $folderId = $folder?->id;
        }

        // Create Database Record
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);
        $size = filesize($fullFinalPath);
        $mime = mime_content_type($fullFinalPath);

        $file = File::create([
            'uuid' => $this->fileUuid,
            'user_id' => $this->userId,
            'folder_id' => $folderId,
            'name' => $this->filename,
            'original_name' => $this->filename,
            'disk_name' => "{$userSlug}/{$category}/{$randomFileName}",
            'extension' => $extension,
            'mime_type' => $mime,
            'size' => $size,
            'disk' => 'local',
            'hash' => $hash,
        ]);

        // Link process to file and complete
        $process->update([
            'file_id' => $file->id,
            'status' => 'completed',
            'progress' => 100
        ]);

        // Dispatch metadata extraction job
        FetchMediaMetadataJob::dispatch($this->fileUuid);
    }
}
