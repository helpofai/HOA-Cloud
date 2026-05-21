<?php

namespace App\Shared\Services;

class SharedHostingService
{
    public function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'is_php_supported' => version_compare(PHP_VERSION, '8.3.0', '>='),
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
    }

    public function checkDirectoryMapping(): array
    {
        $publicPath = public_path();
        $basePath = base_path();
        $isPublicHtml = str_contains($publicPath, 'public_html');

        return [
            'base_path' => $basePath,
            'public_path' => $publicPath,
            'is_public_html_mapped' => $isPublicHtml,
            'storage_linked' => file_exists(public_path('storage')),
        ];
    }

    public function canUseSymlinks(): bool
    {
        if (!function_exists('symlink')) {
            return false;
        }

        $testSymlink = public_path('symlink_test');
        $testTarget = storage_path('app/public');

        try {
            @symlink($testTarget, $testSymlink);
            $exists = file_exists($testSymlink);
            if ($exists) {
                @unlink($testSymlink);
            }
            return $exists;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getOptimizationSuggestions(): array
    {
        $suggestions = [];

        if (version_compare(PHP_VERSION, '8.3.0', '<')) {
            $suggestions[] = [
                'type' => 'error',
                'title' => 'Outdated PHP Version',
                'message' => 'HOA Cloud requires PHP 8.3+. Please upgrade your hosting environment.',
            ];
        }

        if (!$this->canUseSymlinks()) {
            $suggestions[] = [
                'type' => 'warning',
                'title' => 'Symlinks Disabled',
                'message' => 'Your shared host has symlinks disabled. File previews might require the fallback proxy.',
            ];
        }

        if (ini_get('upload_max_filesize') === '2M') {
            $suggestions[] = [
                'type' => 'info',
                'title' => 'Low Upload Limit',
                'message' => 'Default 2MB limit detected. Ensure Resumable.js chunks are set accordingly or increase ini settings.',
            ];
        }

        return $suggestions;
    }
}
