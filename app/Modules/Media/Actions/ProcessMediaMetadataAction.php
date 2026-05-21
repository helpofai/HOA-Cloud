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
        // 1. Fetch External Metadata (TMDB/OMDb)
        $externalMetadata = $this->service->fetchMetadata($file->name);

        // 2. Fetch Technical Metadata (FFprobe)
        $filePath = storage_path("app/private/uploads/" . $file->disk_name);
        $technicalMetadata = $this->service->getTechnicalMetadata($filePath);

        // Merge all metadata
        $updateData = array_merge(
            $externalMetadata ?? [],
            [
                'duration' => $technicalMetadata['duration'] ?? null,
                'width' => $technicalMetadata['width'] ?? null,
                'height' => $technicalMetadata['height'] ?? null,
                'codec' => $technicalMetadata['codec'] ?? $technicalMetadata['audio_codec'] ?? null,
                'technical_metadata' => $technicalMetadata,
                'metadata_fetched' => true,
            ]
        );

        return $file->update($updateData);
    }
}
