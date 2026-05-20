<?php

use App\Shared\Models\Setting;

return [
    'tmdb' => [
        'api_key' => env('TMDB_API_KEY'),
        'base_url' => 'https://api.themoviedb.org/3',
        'image_url' => 'https://image.tmdb.org/t/p/w500',
    ],
    'omdb' => [
        'api_key' => env('OMDB_API_KEY'),
        'base_url' => 'https://www.omdbapi.com/',
    ],
    'bin' => [
        'ffmpeg' => env('FFMPEG_PATH', base_path('bin/ffmpeg')),
        'ffprobe' => env('FFPROBE_PATH', base_path('bin/ffprobe')),
    ],
];
