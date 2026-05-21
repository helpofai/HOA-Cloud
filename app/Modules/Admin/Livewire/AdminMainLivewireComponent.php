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

    public $section = 'overview'; // overview, users, files, domains, media-engine, shared-hosting, settings, abuse
    
    // File Monitoring Data
    public $searchFile = '';

    // Multi-Domain Data
    public $multiDomainEnabled = false;
    public $newNodeDomain = '';
    public $nodes = [];

    // User Management Data
    public $usersList = [];
    public $searchUser = '';

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
        $this->multiDomainEnabled = (bool) Setting::get('multi_domain_enabled', false);

        $this->loadSystemHealth($sharedHostingService);
        if ($this->section === 'domains') {
            $this->loadNodes();
        }
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

    public function render()
    {
        $files = [];
        $usersData = [];

        if ($this->section === 'files') {
            $files = File::with(['user'])
                ->when($this->searchFile, function($q) {
                    $q->where('name', 'like', "%{$this->searchFile}%")
                      ->orWhere('uuid', 'like', "%{$this->searchFile}%");
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
        }

        return view('app.Modules.Admin.Views.admin-main-livewire-component', [
            'files' => $files,
            'usersData' => $usersData
        ])->layout('layouts.dashboard', ['title' => 'Super Admin - Hoa Cloud']);
    }
}
