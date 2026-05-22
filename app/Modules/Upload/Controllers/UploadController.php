<?php

namespace App\Modules\Upload\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Modules\Upload\Services\ChunkManagerService;

use App\Modules\Support\Services\SharedHostingQueueService;

class UploadController extends Controller
{
    public function __construct(
        protected ChunkManagerService $chunkManager,
        protected SharedHostingQueueService $queueService
    ) {}

    public function upload(Request $request)
    {
        // Resumable.js sends a GET request to check if a chunk exists
        if ($request->isMethod('GET')) {
            if ($this->chunkManager->chunkExists($request)) {
                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'not_found'], 204);
        }

        // POST request to upload a chunk
        $result = $this->chunkManager->saveChunk($request);

        if ($result['status'] === 'completed') {
            // Trigger background worker for shared hosting
            $this->queueService->startWorker();

            return response()->json([
                'status' => 'completed',
                'file_uuid' => $result['file_uuid']
            ], 200);
        }

        return response()->json(['status' => 'chunk_saved'], 201);
    }
}
