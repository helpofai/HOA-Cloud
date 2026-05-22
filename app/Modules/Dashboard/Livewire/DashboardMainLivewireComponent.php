<?php

namespace App\Modules\Dashboard\Livewire;

use Livewire\Component;

use App\Modules\Folder\Models\Folder;
use App\Modules\File\Models\File;
use Illuminate\Support\Facades\Auth;

use App\Modules\Folder\Actions\CreateFolderAction;
use App\Modules\Folder\DTOs\CreateFolderData;

class DashboardMainLivewireComponent extends Component
{
    public $section = 'files'; // files, photos, music, videos, shared, bin, domain
    public $filter = 'all'; // all, movies, music, docs
    public $currentFolderUuid = null;
    public $newFolderName = '';
    public $customDomain = '';
    public $viewingShareReports = null;
    public $shareReports = [];

    protected $listeners = ['refresh-files' => '$refresh'];

    protected $queryString = [
        'section',
        'folder' => ['except' => '', 'as' => 'folder'],
    ];

    public function mount()
    {
        $this->currentFolderUuid = request()->query('folder');
        $this->customDomain = Auth::user()->custom_domain;
    }

    public function requestCustomDomain()
    {
        $this->validate([
            'customDomain' => 'nullable|url|max:255'
        ]);

        $user = Auth::user();
        
        // If domain changed, reset approval
        if ($user->custom_domain !== $this->customDomain) {
            $user->update([
                'custom_domain' => $this->customDomain,
                'custom_domain_approved' => false
            ]);
            $this->dispatch('notify', message: 'Domain request submitted for approval.');
        } else {
            $this->dispatch('notify', message: 'No changes detected.');
        }
    }

    public function createFolder(CreateFolderAction $action)
    {
        $this->validate([
            'newFolderName' => 'required|string|max:255',
        ]);

        $parent = $this->currentFolder;
        
        $action->execute(new CreateFolderData(
            user_id: Auth::id(),
            name: $this->newFolderName,
            parent_id: $parent?->id,
        ));

        $this->newFolderName = '';
        $this->dispatch('close-modal', name: 'create-folder');
        $this->dispatch('notify', message: 'Folder created successfully');
    }

    public function setSection($section)
    {
        $this->section = $section;
        $this->currentFolderUuid = null;
        $this->dispatch('url-updated', section: $section);
    }

    public function openFolder($uuid)
    {
        $this->currentFolderUuid = $uuid;
        // Logic to update URL for folder would go here or via queryString
    }

    public function goBack()
    {
        if ($this->currentFolderUuid) {
            $folder = Folder::where('uuid', $this->currentFolderUuid)->first();
            if ($folder && $folder->parent_id) {
                $this->currentFolderUuid = $folder->parent->uuid;
            } else {
                $this->currentFolderUuid = null;
            }
        }
    }

    public function getCurrentFolderProperty()
    {
        if (!$this->currentFolderUuid) {
            return null;
        }
        return Folder::where('uuid', $this->currentFolderUuid)->where('user_id', Auth::id())->first();
    }

    public function getFoldersProperty()
    {
        $query = Auth::user()->folders();
        
        if ($this->currentFolderUuid) {
            $folder = $this->currentFolder;
            if ($folder) {
                return $folder->children;
            }
        }

        return $query->whereNull('parent_id')->get();
    }

    public function getFilesProperty()
    {
        $query = Auth::user()->files();

        if ($this->currentFolderUuid) {
            $folder = $this->currentFolder;
            if ($folder) {
                $query = $folder->files();
            }
        } else {
            $query = $query->whereNull('folder_id');
        }

        // Apply Media Filtering
        if ($this->filter === 'movies') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($this->filter === 'music') {
            $query->where('mime_type', 'like', 'audio/%');
        } elseif ($this->filter === 'docs') {
            $query->where('mime_type', 'like', 'application/%')
                  ->orWhere('mime_type', 'like', 'text/%');
        }

        return $query->latest()->get();
    }

    public function getSharesProperty()
    {
        return \App\Modules\File\Models\Share::with('file')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function generateShareLink($fileUuid)
    {
        $file = File::where('uuid', $fileUuid)->where('user_id', Auth::id())->firstOrFail();

        \App\Modules\File\Models\Share::create([
            'file_id' => $file->id,
            'user_id' => Auth::id(),
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'New sharing link generated.');
    }

    public function toggleShare($shareId)
    {
        $share = \App\Modules\File\Models\Share::where('id', $shareId)->where('user_id', Auth::id())->first();
        if ($share) {
            $share->update(['is_active' => !$share->is_active]);
            $this->dispatch('notify', message: $share->is_active ? 'Link activated.' : 'Link revoked.');
        }
    }

    public function deleteShare($shareId)
    {
        \App\Modules\File\Models\Share::where('id', $shareId)->where('user_id', Auth::id())->delete();
        $this->dispatch('notify', message: 'Sharing link deleted.');
    }

    public function viewReports($shareId)
    {
        $share = \App\Modules\File\Models\Share::where('id', $shareId)->where('user_id', Auth::id())->firstOrFail();
        $this->viewingShareReports = $shareId;
        $this->shareReports = \App\Modules\Security\Models\AbuseReport::where('share_id', $shareId)->get();
        $this->dispatch('open-modal', name: 'view-reports');
    }

    public function playAudio($fileUuid)
    {
        $file = File::where('uuid', $fileUuid)->firstOrFail();
        
        // Generate a stream token for the audio
        $tokenService = app(\App\Modules\Security\Services\GhostTokenService::class);
        $token = $tokenService->generate($file->uuid, request()->ip(), request()->userAgent());
        
        $streamUrl = route('ghost-hop.stream', ['token' => $token]);

        $this->dispatch('audio-play', [
            'url' => $streamUrl,
            'name' => $file->name,
            'poster' => $file->poster_path ? (Str::startsWith($file->poster_path, 'http') ? $file->poster_path : config('hoa-cloud.tmdb.image_url') . $file->poster_path) : null,
            'uuid' => $file->uuid
        ]);
    }

    public function render()
    {
        return view('app.Modules.Dashboard.Views.dashboard-main-livewire-component', [
            'folders' => $this->folders,
            'files' => $this->files,
            'shares' => $this->shares,
            'currentFolder' => $this->currentFolder,
        ])->layout('layouts.dashboard');
    }
}
