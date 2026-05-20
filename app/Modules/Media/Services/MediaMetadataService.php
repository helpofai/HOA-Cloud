<?php

namespace App\Modules\Media\Services;

use App\Shared\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaMetadataService
{
    protected string $tmdbApiKey;
    protected string $tmdbBaseUrl = 'https://api.themoviedb.org/3';
    
    protected string $omdbApiKey;
    protected string $omdbBaseUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->tmdbApiKey = Setting::get('tmdb_api_key', config('hoa-cloud.tmdb.api_key', ''));
        $this->omdbApiKey = Setting::get('omdb_api_key', config('hoa-cloud.omdb.api_key', ''));
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
            ]);

            if ($search->successful() && !empty($search->json()['results'])) {
                $result = $search->json()['results'][0];
                $type = $result['media_type'] ?? 'movie';
                
                $details = Http::get("{$this->tmdbBaseUrl}/{$type}/{$result['id']}", [
                    'api_key' => $this->tmdbApiKey,
                    'append_to_response' => 'credits',
                ]);

                if ($details->successful()) {
                    $data = $details->json();
                    return [
                        'poster_path' => $data['poster_path'] ?? null,
                        'backdrop_path' => $data['backdrop_path'] ?? null,
                        'overview' => $data['overview'] ?? null,
                        'rating' => $data['vote_average'] ?? null,
                        'release_date' => $data['release_date'] ?? $data['first_air_date'] ?? null,
                        'media_type' => $type,
                        'cast' => collect($data['credits']['cast'] ?? [])->take(5)->map(fn($c) => $c['name'])->toArray(),
                        'genres' => collect($data['genres'] ?? [])->map(fn($g) => $g['name'])->toArray(),
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
            ]);

            if ($response->successful() && ($response->json()['Response'] ?? 'False') === 'True') {
                $data = $response->json();
                return [
                    'poster_path' => $data['Poster'] !== 'N/A' ? $data['Poster'] : null,
                    'backdrop_path' => null,
                    'overview' => $data['Plot'] ?? null,
                    'rating' => (float) ($data['imdbRating'] ?? 0),
                    'release_date' => $data['Released'] ?? null,
                    'media_type' => strtolower($data['Type'] ?? 'movie'),
                    'cast' => array_map('trim', explode(',', $data['Actors'] ?? '')),
                    'genres' => array_map('trim', explode(',', $data['Genre'] ?? '')),
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
