<?php

namespace App\Modules\Security\Services;

use Illuminate\Http\Request;
use App\Modules\Security\Models\Blacklist;
use Illuminate\Support\Facades\Cache;

class BotDetectionService
{
    protected array $crawlerKeywords = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 
        'ahrefsbot', 'semrushbot', 'dotbot', 'rogerbot', 'exabot', 'mj12bot',
        'python-requests', 'curl', 'wget', 'postman', 'postmanruntime'
    ];

    protected array $socialKeywords = [
        'facebookexternalhit', 'twitterbot', 'linkedinbot', 'embedly',
        'quora link preview', 'showyoubot', 'outbrain', 'pinterest/0.',
        'telegrambot', 'whatsapp', 'viber', 'skypeuripreview', 'discordbot'
    ];

    public function isBot(Request $request): bool
    {
        return $this->isCrawler($request) || $this->isSocialBot($request);
    }

    public function isCrawler(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        $ip = $request->ip();

        if (empty($userAgent)) return true;

        foreach ($this->crawlerKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) return true;
        }

        return Cache::remember("ip_blacklisted:{$ip}", 3600, function() use ($ip) {
            return Blacklist::where('ip', $ip)->where('is_active', true)->exists();
        }) ?: $this->isAbusiveBehavior($ip);
    }

    public function isSocialBot(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        foreach ($this->socialKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) return true;
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
