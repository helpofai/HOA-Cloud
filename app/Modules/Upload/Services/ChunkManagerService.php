<?php

namespace App\Modules\Upload\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Modules\Upload\Jobs\MergeChunksJob;
use Illuminate\Support\Str;

class ChunkManagerService
{
    protected string $chunkDir = 'chunks';

    public function chunkExists(Request $request): bool
    {
        $identifier = $request->get('resumableIdentifier');
        $chunkNumber = $request->get('resumableChunkNumber');
        $filename = $request->get('resumableFilename');

        $chunkPath = "{$this->chunkDir}/{$identifier}/{$filename}.part{$chunkNumber}";

        return Storage::disk('local')->exists($chunkPath);
    }

    public function saveChunk(Request $request): array
    {
        $file = $request->file('file');
        $identifier = $request->get('resumableIdentifier');
        $chunkNumber = $request->get('resumableChunkNumber');
        $totalChunks = (int) $request->get('resumableTotalChunks');
        $filename = $request->get('resumableFilename');
        $folderUuid = $request->get('folder_uuid');

        $chunkPath = "{$this->chunkDir}/{$identifier}";
        $chunkFilename = "{$filename}.part{$chunkNumber}";

        Storage::disk('local')->putFileAs($chunkPath, $file, $chunkFilename);

        if ($this->isUploadComplete($identifier, $totalChunks, $filename)) {
            // All chunks uploaded, dispatch merge job
            $fileUuid = (string) Str::uuid();
            
            // Create a "Merge" process IMMEDIATELY for UI feedback
            $process = \App\Modules\Media\Models\MediaProcess::create([
                'user_id' => auth()->id(),
                'file_uuid' => $fileUuid,
                'type' => 'merge',
                'status' => 'pending',
                'progress' => 0,
                'command' => 'Queued: Merging chunks for ' . $filename,
            ]);

            MergeChunksJob::dispatch(
                $identifier,
                $filename,
                $totalChunks,
                auth()->id(),
                $folderUuid,
                $fileUuid,
                $process->id
            );

            return [
                'status' => 'completed',
                'file_uuid' => $fileUuid
            ];
        }

        return ['status' => 'chunk_saved'];
    }

    protected function isUploadComplete(string $identifier, int $totalChunks, string $filename): bool
    {
        for ($i = 1; $i <= $totalChunks; $i++) {
            if (!Storage::disk('local')->exists("{$this->chunkDir}/{$identifier}/{$filename}.part{$i}")) {
                return false;
            }
        }
        return true;
    }
}
