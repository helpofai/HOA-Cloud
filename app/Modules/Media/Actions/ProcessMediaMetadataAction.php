<?php

namespace App\Modules\Media\Actions;

use App\Modules\File\Models\File;
use App\Modules\Media\Services\MediaMetadataService;

class ProcessMediaMetadataAction
{
    public function __construct(
        protected MediaMetadataService $service
    ) {}

    public function execute(File $file): bool
    {
        $metadata = $this->service->fetchMetadata($file->name);

        if (!$metadata) {
            return false;
        }

        $file->update(array_merge($metadata, [
            'metadata_fetched' => true,
        ]));

        return true;
    }
}
