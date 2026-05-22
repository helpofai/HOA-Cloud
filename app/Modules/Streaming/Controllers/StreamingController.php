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
        
        // We use a stream-specific validation that allows multiple ranges for the SAME session
        $fileUuid = $this->getStreamFileUuid($token, $request);

        if (!$fileUuid) {
            abort(403, 'Stream session expired or unauthorized.');
        }

        $file = File::where('uuid', $fileUuid)->firstOrFail();
        $path = storage_path("app/private/uploads/{$file->disk_name}");

        if (!file_exists($path)) {
            abort(404, 'Physical file not found.');
        }

        // Determine speed limit based on user role
        // Premium roles (Admin, Super-Admin, Pro) get unlimited speed
        // Others are throttled to 1MB/s (1024 KB/s) or 500KB/s
        $speedLimit = $this->determineSpeedLimit($file->user);
        
        return $this->streamingService->stream($path, $file->mime_type, $file->name, $speedLimit);
    }

    protected function getStreamFileUuid(string $token, Request $request): ?string
    {
        // First check if this token is ALREADY an active stream session
        $sessionKey = "active_stream:" . md5($token . $request->ip() . $request->userAgent());
        $cachedUuid = \Illuminate\Support\Facades\Cache::get($sessionKey);
        
        if ($cachedUuid) {
            return $cachedUuid;
        }

        // If not cached, validate the token normally (and burn it)
        $fileUuid = $this->tokenService->validateAndBurn($token, $request->ip(), $request->userAgent());

        if ($fileUuid) {
            // Mark this token (or its signature) as an active session for 4 hours
            // to allow seeking/range requests
            \Illuminate\Support\Facades\Cache::put($sessionKey, $fileUuid, now()->addHours(4));
        }

        return $fileUuid;
    }

    protected function determineSpeedLimit($owner): ?int
    {
        $user = auth()->user();
        
        // If requester is the owner, or an admin/pro, unlimited
        if ($user && ($user->isAdmin() || $user->role === \App\Core\Enums\UserRole::PRO || $user->id === $owner->id)) {
            return null;
        }

        // Default limit for guests or standard users (1000 KB/s = ~8Mbps)
        return (int) \App\Shared\Models\Setting::get('default_stream_speed', 1024);
    }
}
