<?php

namespace App\Modules\Media\Models;

use App\Models\User;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaProcess extends Model
{
    protected $fillable = [
        'user_id',
        'file_id',
        'file_uuid',
        'type',
        'status',
        'progress',
        'pid',
        'command',
        'output_path',
        'error',
    ];

    protected $casts = [
        'progress' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
