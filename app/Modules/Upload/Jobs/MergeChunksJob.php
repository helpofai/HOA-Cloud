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
        protected string $fileUuid
    ) {}

    public function handle(): void
    {
        $chunkDir = "chunks/{$this->identifier}";
        $finalPath = "app/private/uploads/" . Str::random(40);
        $fullFinalPath = storage_path($finalPath);

        // Ensure directory exists
        if (!file_exists(dirname($fullFinalPath))) {
            mkdir(dirname($fullFinalPath), 0755, true);
        }

        $out = fopen($fullFinalPath, 'wb');

        for ($i = 1; $i <= $this->totalChunks; $i++) {
            $chunkPath = storage_path("app/chunks/{$this->identifier}/{$this->filename}.part{$i}");
            $in = fopen($chunkPath, 'rb');
            stream_copy_to_stream($in, $out);
            fclose($in);
            unlink($chunkPath); // Delete chunk after merging
        }

        fclose($out);
        rmdir(storage_path("app/chunks/{$this->identifier}"));

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

        File::create([
            'uuid' => $this->fileUuid,
            'user_id' => $this->userId,
            'folder_id' => $folderId,
            'name' => $this->filename,
            'original_name' => $this->filename,
            'disk_name' => basename($finalPath),
            'extension' => $extension,
            'mime_type' => $mime,
            'size' => $size,
            'disk' => 'local',
            'hash' => $hash,
        ]);

        // Dispatch metadata extraction job
        FetchMediaMetadataJob::dispatch($this->fileUuid);
    }
}
