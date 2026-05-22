<?php

namespace App\Modules\Media\Services;

use App\Shared\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaMetadataService
{
    protected ?string $tmdbApiKey = null;
    protected string $tmdbBaseUrl = 'https://api.themoviedb.org/3';
    
    protected ?string $omdbApiKey = null;
    protected string $omdbBaseUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->tmdbApiKey = (string) Setting::get('tmdb_api_key', config('hoa-cloud.tmdb.api_key') ?? '');
        $this->omdbApiKey = (string) Setting::get('omdb_api_key', config('hoa-cloud.omdb.api_key') ?? '');
    }

    /**
     * Extract technical metadata using FFprobe
     */
    public function getTechnicalMetadata(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $ffprobe = config('hoa-cloud.bin.ffprobe');
        
        // On Windows, ensure it has .exe if missing
        if (PHP_OS_FAMILY === 'Windows' && !str_ends_with($ffprobe, '.exe')) {
            $ffprobe .= '.exe';
        }

        // Run FFprobe to get JSON metadata
        $command = "\"$ffprobe\" -v quiet -print_format json -show_format -show_streams \"$path\"";
        $output = shell_exec($command);
        
        if (!$output) {
            return [];
        }

        $data = json_decode($output, true);
        if (!$data) {
            return [];
        }

        $metadata = [
            'duration' => (float) ($data['format']['duration'] ?? 0),
            'bitrate' => (int) ($data['format']['bit_rate'] ?? 0),
            'format' => $data['format']['format_name'] ?? null,
            'tags' => $data['format']['tags'] ?? [], // ID3 Tags
        ];

        // Find Video Stream for Resolution
        foreach ($data['streams'] as $stream) {
            if ($stream['codec_type'] === 'video') {
                $metadata['width'] = $stream['width'] ?? null;
                $metadata['height'] = $stream['height'] ?? null;
                $metadata['codec'] = $stream['codec_name'] ?? null;
                break;
            }
        }

        // Find Audio Stream if no video (for music files)
        if (!isset($metadata['codec'])) {
            foreach ($data['streams'] as $stream) {
                if ($stream['codec_type'] === 'audio') {
                    $metadata['audio_codec'] = $stream['codec_name'] ?? null;
                    $metadata['sample_rate'] = $stream['sample_rate'] ?? null;
                    break;
                }
            }
        }

        return $metadata;
    }

    public function search(string $query): array
    {
        try {
            $search = Http::get("{$this->tmdbBaseUrl}/search/multi", [
                'api_key' => $this->tmdbApiKey,
                'query' => $query,
                'include_adult' => false,
            ]);

            if ($search->successful()) {
                return collect($search->json()['results'] ?? [])
                    ->filter(fn($r) => in_array($r['media_type'] ?? '', ['movie', 'tv']))
                    ->map(fn($r) => [
                        'id' => $r['id'],
                        'title' => $r['title'] ?? $r['name'] ?? 'Unknown',
                        'release_date' => $r['release_date'] ?? $r['first_air_date'] ?? 'N/A',
                        'poster_path' => $r['poster_path'] ?? null,
                        'overview' => $r['overview'] ?? '',
                        'media_type' => $r['media_type'],
                        'popularity' => $r['popularity'] ?? 0,
                    ])
                    ->sortByDesc('popularity')
                    ->values()
                    ->toArray();
            }
        } catch (\Exception $e) {
            Log::error("TMDB Search Error: " . $e->getMessage());
        }

        return [];
    }

    public function getDetails(string $type, int $id): ?array
    {
        try {
            $details = Http::get("{$this->tmdbBaseUrl}/{$type}/{$id}", [
                'api_key' => $this->tmdbApiKey,
                'append_to_response' => 'credits',
            ]);

            if ($details->successful()) {
                $data = $details->json();
                
                $cast = collect($data['credits']['cast'] ?? [])
                    ->take(10)
                    ->map(fn($c) => $c['name'])
                    ->toArray();

                $genres = collect($data['genres'] ?? [])
                    ->map(fn($g) => $g['name'])
                    ->toArray();

                return [
                    'poster_path' => $data['poster_path'] ?? null,
                    'backdrop_path' => $data['backdrop_path'] ?? null,
                    'overview' => $data['overview'] ?? null,
                    'rating' => $data['vote_average'] ?? null,
                    'release_date' => $data['release_date'] ?? $data['first_air_date'] ?? null,
                    'media_type' => $type,
                    'cast' => $cast,
                    'genres' => $genres,
                    'title' => $data['title'] ?? $data['name'] ?? 'Unknown',
                ];
            }
        } catch (\Exception $e) {
            Log::error("TMDB Details Error: " . $e->getMessage());
        }

        return null;
    }

    public function fetchMetadata(string $filename): ?array
    {
        $cleanName = $this->cleanFileName($filename);
        
        // Try TMDB first (Primary)
        if (!empty($this->tmdbApiKey)) {
            $metadata = $this->fetchFromTmdb($cleanName);
            if ($metadata) return $metadata;
        }

        // Fallback to OMDb if enabled
        if (Setting::get('use_omdb', false) && !empty($this->omdbApiKey)) {
            return $this->fetchFromOmdb($cleanName);
        }

        return null;
    }

    protected function fetchFromTmdb(string $query): ?array
    {
        try {
            $search = Http::get("{$this->tmdbBaseUrl}/search/multi", [
                'api_key' => $this->tmdbApiKey,
                'query' => $query,
                'include_adult' => false,
            ]);

            if ($search->successful() && !empty($search->json()['results'])) {
                // Filter for movies and tv shows, prioritize highest popularity
                $results = collect($search->json()['results'])
                    ->filter(fn($r) => in_array($r['media_type'] ?? '', ['movie', 'tv']))
                    ->sortByDesc('popularity');

                if ($results->isEmpty()) {
                    return null;
                }

                $result = $results->first();
                $type = $result['media_type'];
                
                $details = Http::get("{$this->tmdbBaseUrl}/{$type}/{$result['id']}", [
                    'api_key' => $this->tmdbApiKey,
                    'append_to_response' => 'credits',
                ]);

                if ($details->successful()) {
                    $data = $details->json();
                    
                    // Extract Cast (Top 10)
                    $cast = collect($data['credits']['cast'] ?? [])
                        ->take(10)
                        ->map(fn($c) => $c['name'])
                        ->toArray();

                    // Extract Genres
                    $genres = collect($data['genres'] ?? [])
                        ->map(fn($g) => $g['name'])
                        ->toArray();

                    return [
                        'poster_path' => $data['poster_path'] ?? null,
                        'backdrop_path' => $data['backdrop_path'] ?? null,
                        'overview' => $data['overview'] ?? null,
                        'rating' => $data['vote_average'] ?? null,
                        'release_date' => $data['release_date'] ?? $data['first_air_date'] ?? null,
                        'media_type' => $type,
                        'cast' => $cast,
                        'genres' => $genres,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("TMDB Error: " . $e->getMessage());
        }

        return null;
    }

    protected function fetchFromOmdb(string $query): ?array
    {
        try {
            $response = Http::get($this->omdbBaseUrl, [
                'apikey' => $this->omdbApiKey,
                't' => $query,
                'plot' => 'full'
            ]);

            if ($response->successful() && ($response->json()['Response'] ?? 'False') === 'True') {
                $data = $response->json();
                
                $cast = array_filter(array_map('trim', explode(',', $data['Actors'] ?? '')));
                $genres = array_filter(array_map('trim', explode(',', $data['Genre'] ?? '')));

                return [
                    'poster_path' => ($data['Poster'] !== 'N/A') ? $data['Poster'] : null,
                    'backdrop_path' => null,
                    'overview' => $data['Plot'] ?? null,
                    'rating' => (float) ($data['imdbRating'] ?? 0),
                    'release_date' => $data['Released'] ?? null,
                    'media_type' => strtolower($data['Type'] ?? 'movie'),
                    'cast' => array_values($cast),
                    'genres' => array_values($genres),
                ];
            }
        } catch (\Exception $e) {
            Log::error("OMDb Error: " . $e->getMessage());
        }

        return null;
    }

    public function cleanFileName(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/(19|20)\d{2}/', '', $name);
        $name = preg_replace('/(720p|1080p|2160p|4k|bluray|h264|h265|x264|x265|webrip|web-dl|brrip)/i', '', $name);
        $name = str_replace(['.', '_', '-'], ' ', $name);
        return trim($name);
    }
}
