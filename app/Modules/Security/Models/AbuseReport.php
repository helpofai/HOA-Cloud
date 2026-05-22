<?php

namespace App\Modules\Security\Models;

use App\Modules\File\Models\File;
use App\Modules\File\Models\Share;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbuseReport extends Model
{
    protected $fillable = [
        'file_id',
        'share_id',
        'reported_url',
        'reporter_ip',
        'reason',
        'details',
        'status',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }
}
