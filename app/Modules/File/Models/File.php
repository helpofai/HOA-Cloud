<?php

namespace App\Modules\File\Models;

use App\Models\User;
use App\Modules\Folder\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class File extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'folder_id',
        'name',
        'original_name',
        'disk_name',
        'extension',
        'mime_type',
        'size',
        'disk',
        'hash',
        'poster_path',
        'backdrop_path',
        'overview',
        'rating',
        'release_date',
        'media_type',
        'cast',
        'genres',
        'metadata_fetched',
        'duration',
        'width',
        'height',
        'codec',
        'technical_metadata',
    ];

    protected function casts(): array
    {
        return [
            'cast' => 'array',
            'genres' => 'array',
            'metadata_fetched' => 'boolean',
            'rating' => 'decimal:1',
            'technical_metadata' => 'array',
            'duration' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
