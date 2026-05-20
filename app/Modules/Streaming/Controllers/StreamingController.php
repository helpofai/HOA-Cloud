<?php

namespace App\Modules\Streaming\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\File\Models\File;
use App\Modules\Security\Services\GhostTokenService;
use App\Modules\Streaming\Services\StreamingService;
use Illuminate\Support\Facades\Session;

class StreamingController extends Controller
{
    public function __construct(
        protected GhostTokenService $tokenService,
        protected StreamingService $streamingService
    ) {}

    /**
     * Layer 3: The Player Hop (Hidden Referrer)
     * Loads the actual player page and generates a short-lived Blob URL source token.
     */
    public function watch(Request $request, string $accessToken)
    {
        $fileUuid = $this->tokenService->validateAndBurn($accessToken, $request->ip(), $request->userAgent());

        if (!$fileUuid) {
            abort(403, 'Invalid or expired access token.');
        }

        $file = File::where('uuid', $fileUuid)->firstOrFail();

        // Generate a new SINGLE-USE STREAM TOKEN for the player's internal request
        $streamToken = $this->tokenService->generate($fileUuid, $request->ip(), $request->userAgent());

        return view('app.Modules.Streaming.Views.watch', [
            'file' => $file,
            'streamToken' => $streamToken,
        ]);
    }

    /**
     * Layer 4: The Stream Controller
     * Handles the actual media binary delivery.
     */
    public function stream(Request $request)
    {
        $token = $request->get('token');
        
        $fileUuid = $this->tokenService->validateAndBurn($token, $request->ip(), $request->userAgent());

        if (!$fileUuid) {
            abort(403, 'Stream session expired or unauthorized.');
        }

        $file = File::where('uuid', $fileUuid)->firstOrFail();
        $path = storage_path("app/private/uploads/{$file->disk_name}");

        if (!file_exists($path)) {
            abort(404, 'Physical file not found.');
        }

        // We DON'T burn the token here if we want to support seeking (multiple range requests)
        // However, the architecture says Layer 5 is "Token Burn".
        // To support seeking, we can either:
        // 1. Keep the token alive for a short duration (e.g. 2 hours)
        // 2. Exchange it for a temporary session-based stream session.
        
        return $this->streamingService->stream($path, $file->mime_type, $file->name);
    }
}
