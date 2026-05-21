<?php

namespace App\Modules\Security\Services;

use Illuminate\Http\Request;
use App\Modules\Security\Models\Blacklist;
use Illuminate\Support\Facades\Cache;

class BotDetectionService
{
    protected array $botKeywords = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 
        'ahrefsbot', 'semrushbot', 'dotbot', 'rogerbot', 'exabot', 'mj12bot',
        'facebookexternalhit', 'twitterbot', 'rogerbot', 'linkedinbot', 'embedly',
        'quora link preview', 'showyoubot', 'outbrain', 'pinterest/0.',
        'telegrambot', 'whatsapp', 'viber', 'skypeuripreview', 'python-requests',
        'curl', 'wget', 'postman', 'postmanruntime'
    ];

    public function isBot(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        $ip = $request->ip();

        // 1. Empty User-Agent is usually a bot
        if (empty($userAgent)) {
            $this->logPotentialBot($ip, 'Empty User-Agent');
            return true;
        }

        // 2. Keyword matching in UA
        foreach ($this->botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        // 3. Database IP Blacklist check
        $isBlacklisted = Cache::remember("ip_blacklisted:{$ip}", 3600, function() use ($ip) {
            return Blacklist::where('ip', $ip)->where('is_active', true)->exists();
        });

        if ($isBlacklisted) {
            return true;
        }

        // 4. Behavioral Analysis (Rate Limiting check)
        if ($this->isAbusiveBehavior($ip)) {
            $this->logPotentialBot($ip, 'Rate limit exceeded (Behavioral)');
            return true;
        }
        
        return false;
    }

    protected function isAbusiveBehavior(string $ip): bool
    {
        $key = "behavior_hits:{$ip}";
        $hits = Cache::get($key, 0);
        
        if ($hits > 100) { // More than 100 hits in 1 minute
            return true;
        }

        Cache::put($key, $hits + 1, 60);
        return false;
    }

    protected function logPotentialBot(string $ip, string $reason): void
    {
        // For now just a simple log, maybe auto-blacklist later
        \Illuminate\Support\Facades\Log::warning("Potential Bot Detected: IP {$ip}, Reason: {$reason}");
    }
}
