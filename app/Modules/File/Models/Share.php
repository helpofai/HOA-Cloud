<?php

namespace App\Modules\File\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Share extends Model
{
    protected $fillable = [
        'file_id',
        'user_id',
        'slug',
        'password',
        'is_active',
        'expires_at',
        'hits',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::random(12);
            }
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGhostUrlAttribute(): string
    {
        $domainService = app(\App\Modules\Security\Services\GhostDomainService::class);
        $domain = $this->determineDomain();
        $path = route('ghost-hop.share', ['slug' => $this->slug], false);
        $scheme = request()->secure() ? 'https://' : 'http://';
        
        if (!preg_match("~^(?:f|ht)tps?://~i", $domain)) {
            $domain = $scheme . ltrim($domain, '/');
        }

        return rtrim($domain, '/') . $path;
    }

    protected function determineDomain(): string
    {
        $isMultiDomainEnabled = (bool) \App\Shared\Models\Setting::get('multi_domain_enabled', false);

        if (!$isMultiDomainEnabled) {
            return config('app.url');
        }

        if ($this->user && $this->user->custom_domain && $this->user->custom_domain_approved) {
            return $this->user->custom_domain;
        }

        $activeNode = \App\Modules\Security\Models\Node::getActiveRedirectNode();
        if ($activeNode) {
            return $activeNode->domain;
        }

        return config('app.url');
    }
}
