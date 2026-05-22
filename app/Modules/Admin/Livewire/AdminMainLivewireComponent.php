<?php

namespace App\Modules\Admin\Livewire;

use Livewire\Component;
use App\Shared\Models\Setting;
use App\Shared\Services\SharedHostingService;
use App\Modules\File\Models\File;
use App\Modules\Security\Models\Node;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class AdminMainLivewireComponent extends Component
{
    use WithPagination;

    public $section = 'overview'; // overview, users, files, domains, media-engine, anti-bot, kill-switch, shared-hosting, settings, abuse
    
    // File Monitoring Data
    public $searchFile = '';
    public $filter = 'all'; // all, movies, music, docs

    // Global Kill Switch Data
    public $searchKilled = '';

    // Abuse Management Data
    public $searchAbuse = '';

    // Multi-Domain Data
    public $multiDomainEnabled = false;
    public $newNodeDomain = '';
    public $nodes = [];

    // User Management Data
    public $usersList = [];
    public $searchUser = '';

    // Anti-Bot Data
    public $blacklist = [];
    public $searchBlacklist = '';
    public $newBlacklistIp = '';
    public $newBlacklistReason = '';

    // API Settings
    public $tmdbApiKey = '';
    public $omdbApiKey = '';
    public $useOmdb = false;

    // Watermark Settings
    public $watermarkEnabled = true;
    public $watermarkOpacity = 0.2;
    public $watermarkSpeed = 'medium';
    public $watermarkUserControl = true;

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
        $this->multiDomainEnabled = (bool) Setting::get('multi_domain_enabled', false);

        // Load Watermark Settings
        $this->watermarkEnabled = (bool) Setting::get('watermark_enabled', true);
        $this->watermarkOpacity = (float) Setting::get('watermark_opacity', 0.2);
        $this->watermarkSpeed = (string) Setting::get('watermark_speed', 'medium');
        $this->watermarkUserControl = (bool) Setting::get('watermark_user_control', true);

        $this->loadSystemHealth($sharedHostingService);
        if ($this->section === 'domains') {
            $this->loadNodes();
        }
    }

    public function saveWatermarkSettings()
    {
        Setting::set('watermark_enabled', $this->watermarkEnabled, 'boolean', 'security');
        Setting::set('watermark_opacity', $this->watermarkOpacity, 'string', 'security');
        Setting::set('watermark_speed', $this->watermarkSpeed, 'string', 'security');
        Setting::set('watermark_user_control', $this->watermarkUserControl, 'boolean', 'security');

        $this->dispatch('notify', message: 'Watermarking policy updated globally');
    }

    public function loadNodes()
    {
        $this->nodes = Node::latest()->get();
    }

    public function addNode()
    {
        $this->validate([
            'newNodeDomain' => 'required|url|unique:nodes,domain'
        ]);

        Node::create([
            'domain' => $this->newNodeDomain,
            'type' => 'redirect',
            'status' => 'active'
        ]);

        $this->newNodeDomain = '';
        $this->loadNodes();
        $this->dispatch('notify', message: 'Node added successfully.');
    }

    public function deleteNode($id)
    {
        Node::find($id)?->delete();
        $this->loadNodes();
        $this->dispatch('notify', message: 'Node removed.');
    }

    public function saveDomainSettings()
    {
        Setting::set('multi_domain_enabled', $this->multiDomainEnabled, 'boolean', 'security');
        $this->dispatch('notify', message: 'Multi-Domain architecture settings updated.');
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
            $file->update(['is_killed' => true]); 
            \Illuminate\Support\Facades\Cache::forget("file_status:{$uuid}");
            $this->dispatch('notify', message: 'Global Kill Switch activated for file.');
        }
    }

    public function reviveFile($uuid)
    {
        $file = File::where('uuid', $uuid)->first();
        if ($file) {
            $file->update(['is_killed' => false]);
            \Illuminate\Support\Facades\Cache::forget("file_status:{$uuid}");
            $this->dispatch('notify', message: 'File has been restored to active status.');
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

    public function reIndexAllFiles()
    {
        $files = \App\Modules\File\Models\File::all();
        foreach ($files as $file) {
            \App\Modules\Media\Jobs\FetchMediaMetadataJob::dispatch($file->uuid);
        }

        $this->dispatch('notify', message: 'Re-indexing job dispatched for ' . $files->count() . ' files');
    }

    public function clearMetadataCache()
    {
        \App\Modules\File\Models\File::query()->update([
            'poster_path' => null,
            'backdrop_path' => null,
            'overview' => null,
            'rating' => null,
            'release_date' => null,
            'cast' => null,
            'genres' => null,
            'metadata_fetched' => false
        ]);

        $this->dispatch('notify', message: 'Metadata cache cleared for all files');
    }

    public function saveAntiBotSettings()
    {
        // ... Anti-Bot settings logic if any
    }

    public function setSection($section)
    {
        $this->section = $section;
        if ($section === 'domains') {
            $this->loadNodes();
        }
        $this->dispatch('url-updated', section: $section);
    }

    public function toggleUserDomainApproval($userId)
    {
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->custom_domain_approved = !$user->custom_domain_approved;
            $user->save();
            $this->dispatch('notify', message: 'User custom domain approval updated.');
        }
    }

    public function addToBlacklist()
    {
        $this->validate([
            'newBlacklistIp' => 'required|ip|unique:blacklists,ip',
            'newBlacklistReason' => 'nullable|string|max:255'
        ]);

        \App\Modules\Security\Models\Blacklist::create([
            'ip' => $this->newBlacklistIp,
            'reason' => $this->newBlacklistReason,
            'type' => 'ip',
            'is_active' => true
        ]);

        $this->newBlacklistIp = '';
        $this->newBlacklistReason = '';
        $this->dispatch('notify', message: 'IP added to blacklist.');
    }

    public function removeFromBlacklist($id)
    {
        \App\Modules\Security\Models\Blacklist::find($id)?->delete();
        $this->dispatch('notify', message: 'IP removed from blacklist.');
    }

    public function killShare($shareId)
    {
        $share = \App\Modules\File\Models\Share::find($shareId);
        if ($share) {
            $share->update(['is_active' => false]);
            $this->dispatch('notify', message: 'Link-wise Kill Switch activated.');
        }
    }

    public function restoreShare($shareId)
    {
        $share = \App\Modules\File\Models\Share::find($shareId);
        if ($share) {
            $share->update(['is_active' => true]);
            $this->dispatch('notify', message: 'Share link restored.');
        }
    }

    public function dismissAbuse($abuseId)
    {
        $report = \App\Modules\Security\Models\AbuseReport::find($abuseId);
        if ($report) {
            $report->update(['status' => 'dismissed']);
            $this->dispatch('notify', message: 'Abuse report dismissed.');
        }
    }

    public function render()
    {
        $files = [];
        $usersData = [];
        $blacklistData = [];
        $killedFilesData = [];
        $abuseReportsData = [];

        if ($this->section === 'files') {
            $files = File::with(['user'])
                ->when($this->searchFile, function($q) {
                    $q->where('name', 'like', "%{$this->searchFile}%")
                      ->orWhere('uuid', 'like', "%{$this->searchFile}%");
                })
                ->when($this->filter !== 'all', function($q) {
                    if ($this->filter === 'movies') {
                        $q->where('mime_type', 'like', 'video/%');
                    } elseif ($this->filter === 'music') {
                        $q->where('mime_type', 'like', 'audio/%');
                    } elseif ($this->filter === 'docs') {
                        $q->where(function($sq) {
                            $sq->where('mime_type', 'like', 'application/%')
                               ->orWhere('mime_type', 'like', 'text/%');
                        });
                    }
                })
                ->latest()
                ->take(50)
                ->get();
        } elseif ($this->section === 'users') {
            $usersData = \App\Models\User::when($this->searchUser, function($q) {
                    $q->where('name', 'like', "%{$this->searchUser}%")
                      ->orWhere('email', 'like', "%{$this->searchUser}%");
                })
                ->latest()
                ->paginate(20);
        } elseif ($this->section === 'anti-bot') {
            $blacklistData = \App\Modules\Security\Models\Blacklist::when($this->searchBlacklist, function($q) {
                    $q->where('ip', 'like', "%{$this->searchBlacklist}%")
                      ->orWhere('reason', 'like', "%{$this->searchBlacklist}%");
                })
                ->latest()
                ->paginate(20);
        } elseif ($this->section === 'kill-switch') {
            $killedFilesData = File::with(['user'])
                ->where('is_killed', true)
                ->when($this->searchKilled, function($q) {
                    $q->where('name', 'like', "%{$this->searchKilled}%")
                      ->orWhere('uuid', 'like', "%{$this->searchKilled}%");
                })
                ->latest()
                ->paginate(20);
        } elseif ($this->section === 'abuse') {
            $abuseReportsData = \App\Modules\Security\Models\AbuseReport::with(['file', 'share'])
                ->when($this->searchAbuse, function($q) {
                    $q->where('reported_url', 'like', "%{$this->searchAbuse}%")
                      ->orWhere('reason', 'like', "%{$this->searchAbuse}%");
                })
                ->where('status', 'pending')
                ->latest()
                ->paginate(20);
        }

        return view('app.Modules.Admin.Views.admin-main-livewire-component', [
            'files' => $files,
            'usersData' => $usersData,
            'blacklistData' => $blacklistData,
            'killedFilesData' => $killedFilesData,
            'abuseReportsData' => $abuseReportsData
        ])->layout('layouts.dashboard', ['title' => 'Super Admin - Hoa Cloud']);
    }
}
