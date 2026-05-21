<?php

namespace App\Modules\Admin\Livewire;

use Livewire\Component;
use App\Shared\Models\Setting;
use App\Shared\Services\SharedHostingService;
use App\Modules\File\Models\File;
use Illuminate\Support\Facades\Storage;

class AdminMainLivewireComponent extends Component
{
    public $section = 'overview'; // overview, users, files, domains, media-engine, shared-hosting, settings, abuse
    
    // File Monitoring Data
    public $searchFile = '';

    // API Settings
    public $tmdbApiKey = '';
    public $omdbApiKey = '';
    public $useOmdb = false;

    // Shared Hosting Optimization Data
    public $systemInfo = [];
    public $dirMapping = [];
    public $optimizationSuggestions = [];
    public $canUseSymlinks = false;

    protected $queryString = ['section'];

    public function mount(SharedHostingService $sharedHostingService)
    {
        $this->tmdbApiKey = (string) Setting::get('tmdb_api_key', config('hoa-cloud.tmdb.api_key', ''));
        $this->omdbApiKey = (string) Setting::get('omdb_api_key', config('hoa-cloud.omdb.api_key', ''));
        $this->useOmdb = (bool) Setting::get('use_omdb', false);

        $this->loadSystemHealth($sharedHostingService);
    }

    public function loadSystemHealth(SharedHostingService $sharedHostingService)
    {
        $this->systemInfo = $sharedHostingService->getSystemInfo();
        $this->dirMapping = $sharedHostingService->checkDirectoryMapping();
        $this->optimizationSuggestions = $sharedHostingService->getOptimizationSuggestions();
        $this->canUseSymlinks = $sharedHostingService->canUseSymlinks();
    }

    public function killFile($uuid)
    {
        $file = File::where('uuid', $uuid)->first();
        if ($file) {
            $file->update(['metadata_fetched' => false]); 
            $this->dispatch('notify', message: 'Global Kill Switch activated for file.');
        }
    }

    public function deleteFile($uuid)
    {
        $file = File::where('uuid', $uuid)->first();
        if ($file) {
            Storage::disk('local')->delete("private/uploads/{$file->disk_name}");
            $file->delete();
            $this->dispatch('notify', message: 'File permanently purged from system.');
        }
    }

    public function repairStorageLink(SharedHostingService $sharedHostingService)
    {
        if ($this->canUseSymlinks) {
            @unlink(public_path('storage'));
            @symlink(storage_path('app/public'), public_path('storage'));
        }
        $this->loadSystemHealth($sharedHostingService);
        $this->dispatch('notify', message: 'Storage link repair attempted.');
    }

    public function saveApiSettings()
    {
        Setting::set('tmdb_api_key', $this->tmdbApiKey, 'string', 'api');
        Setting::set('omdb_api_key', $this->omdbApiKey, 'string', 'api');
        Setting::set('use_omdb', $this->useOmdb, 'boolean', 'api');

        $this->dispatch('notify', message: 'API Configurations updated successfully');
    }

    public function setSection($section)
    {
        $this->section = $section;
        $this->dispatch('url-updated', section: $section);
    }

    public function render()
    {
        $files = [];
        if ($this->section === 'files') {
            $files = File::with(['user'])
                ->when($this->searchFile, function($q) {
                    $q->where('name', 'like', "%{$this->searchFile}%")
                      ->orWhere('uuid', 'like', "%{$this->searchFile}%");
                })
                ->latest()
                ->take(50)
                ->get();
        }

        return view('app.Modules.Admin.Views.admin-main-livewire-component', [
            'files' => $files
        ])->layout('layouts.dashboard', ['title' => 'Super Admin - Hoa Cloud']);
    }
}
