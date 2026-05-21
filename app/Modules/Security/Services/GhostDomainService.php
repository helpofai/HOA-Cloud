<?php

namespace App\Modules\Security\Services;

use App\Modules\File\Models\File;
use App\Modules\Security\Models\Node;
use App\Shared\Models\Setting;

class GhostDomainService
{
    /**
     * Generate the evasive Layer 1 entry URL for a given file.
     */
    public function generateEntryUrl(File $file): string
    {
        $domain = $this->determineDomain($file);
        
        // Use relative path to append to the chosen domain
        $path = route('ghost-hop.entry', ['uuid' => $file->uuid], false);
        
        $scheme = request()->secure() ? 'https://' : 'http://';
        
        // Ensure domain has a scheme
        if (!preg_match("~^(?:f|ht)tps?://~i", $domain)) {
            $domain = $scheme . ltrim($domain, '/');
        }

        return rtrim($domain, '/') . $path;
    }

    protected function determineDomain(File $file): string
    {
        $isMultiDomainEnabled = (bool) Setting::get('multi_domain_enabled', false);

        // If Multi-Domain is DISABLED, always use built-in system
        if (!$isMultiDomainEnabled) {
            return config('app.url');
        }

        // 1. Check if the file owner has an approved custom domain (Overrides Hydra nodes)
        if ($file->user && $file->user->custom_domain && $file->user->custom_domain_approved) {
            return $file->user->custom_domain;
        }

        // 2. Try to get an active Redirect Node from Hydra
        $activeNode = Node::getActiveRedirectNode();
        if ($activeNode) {
            return $activeNode->domain;
        }

        // 3. Absolute Fallback: In-built system
        return config('app.url');
    }
}
