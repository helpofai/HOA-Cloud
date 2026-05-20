<?php

namespace App\Modules\Security\Services;

use Illuminate\Http\Request;

class BotDetectionService
{
    protected array $botKeywords = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 
        'ahrefsbot', 'semrushbot', 'dotbot', 'rogerbot', 'exabot', 'mj12bot',
        'facebookexternalhit', 'twitterbot', 'rogerbot', 'linkedinbot', 'embedly',
        'quora link preview', 'showyoubot', 'outbrain', 'pinterest/0.',
        'telegrambot', 'whatsapp', 'viber', 'skypeuripreview'
    ];

    public function isBot(Request $request): bool
    {
        $userAgent = strtolower($request->header('User-Agent', ''));

        if (empty($userAgent)) {
            return true;
        }

        foreach ($this->botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        // Add IP-based blacklisting here later
        
        return false;
    }
}
