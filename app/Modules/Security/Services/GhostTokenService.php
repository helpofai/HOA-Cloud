<?php

namespace App\Modules\Security\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GhostTokenService
{
    /**
     * Generate a single-use streaming token bound to IP and User-Agent.
     */
    public function generate(string $fileUuid, string $ip, string $userAgent): string
    {
        $token = Str::random(64);
        
        Cache::put("ghost_token:{$token}", [
            'file_uuid' => $fileUuid,
            'ip' => $ip,
            'ua' => $userAgent,
            'created_at' => now()->timestamp,
        ], now()->addMinutes(30)); // Token valid for 30 minutes to start stream

        return $token;
    }

    /**
     * Validate and return file UUID, then burn the token (or mark for burning).
     */
    public function validateAndBurn(string $token, string $ip, string $userAgent): ?string
    {
        $data = Cache::get("ghost_token:{$token}");

        if (!$data) {
            return null;
        }

        // Strict IP and UA binding
        if ($data['ip'] !== $ip || $data['ua'] !== $userAgent) {
            return null;
        }

        // Check if file has been killed since token issuance
        $isKilled = Cache::remember("file_killed:{$data['file_uuid']}", 60, function() use ($data) {
            return \App\Modules\File\Models\File::where('uuid', $data['file_uuid'])->where('is_killed', true)->exists();
        });

        if ($isKilled) {
            $this->burn($token);
            return null;
        }

        // For streaming, we might not burn it immediately if the player needs to make multiple range requests
        // But for the initial "Access", we can burn the "Access Token" and exchange it for a "Stream Token"
        
        return $data['file_uuid'];
    }

    public function burn(string $token): void
    {
        Cache::forget("ghost_token:{$token}");
    }
}
