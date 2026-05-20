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
    public $section = 'files'; // files, photos, music, videos, shared, bin
    public $currentFolderUuid = null;
    public $newFolderName = '';

    protected $listeners = ['refresh-files' => '$refresh'];

    protected $queryString = [
        'section',
        'folder' => ['except' => '', 'as' => 'folder'],
    ];

    public function mount()
    {
        $this->currentFolderUuid = request()->query('folder');
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
                return $folder->files;
            }
        }

        return $query->whereNull('folder_id')->get();
    }

    public function render()
    {
        return view('app.Modules.Dashboard.Views.dashboard-main-livewire-component', [
            'folders' => $this->folders,
            'files' => $this->files,
            'currentFolder' => $this->currentFolder,
        ])->layout('layouts.dashboard');
    }
}
