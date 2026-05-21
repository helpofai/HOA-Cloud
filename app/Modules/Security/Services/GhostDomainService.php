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
        
        $path = route('ghost-hop.entry', ['uuid' => $file->uuid], false);
        
        $scheme = request()->secure() ? 'https://' : 'http://';
        
        // If domain doesn't start with http/https, prepend the current scheme
        if (!str_starts_with($domain, 'http')) {
            $domain = $scheme . ltrim($domain, '/');
        }

        return rtrim($domain, '/') . $path;
    }

    protected function determineDomain(File $file): string
    {
        // 1. Check if the file owner has an approved custom domain
        if ($file->user && $file->user->custom_domain && $file->user->custom_domain_approved) {
            return $file->user->custom_domain;
        }

        // 2. Check if global Multi-Domain Hydra is enabled
        $isMultiDomainEnabled = Setting::get('multi_domain_enabled', false);
        
        if ($isMultiDomainEnabled) {
            $activeNode = Node::getActiveRedirectNode();
            if ($activeNode) {
                return $activeNode->domain;
            }
        }

        // 3. Fallback to in-built default URL
        return config('app.url');
    }
}
