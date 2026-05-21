<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = [
        'domain',
        'type',
        'status',
    ];

    /**
     * Get active redirect node
     */
    public static function getActiveRedirectNode(): ?self
    {
        return self::where('type', 'redirect')
            ->where('status', 'active')
            ->inRandomOrder()
            ->first();
    }
}
