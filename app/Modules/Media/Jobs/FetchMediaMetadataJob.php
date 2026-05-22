<?php

namespace App\Modules\Media\Jobs;

use App\Modules\File\Models\File;
use App\Modules\Media\Actions\ProcessMediaMetadataAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchMediaMetadataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $fileUuid
    ) {}

    public function handle(ProcessMediaMetadataAction $action): void
    {
        $file = File::where('uuid', $this->fileUuid)->first();

        if ($file) {
            $process = \App\Modules\Media\Models\MediaProcess::create([
                'user_id' => $file->user_id,
                'file_id' => $file->id,
                'file_uuid' => $file->uuid,
                'type' => 'metadata',
                'status' => 'processing',
                'progress' => 50,
            ]);

            $action->execute($file);

            $process->update([
                'status' => 'completed',
                'progress' => 100,
            ]);
        }
    }
}
