<?php

namespace App\Modules\Media\Livewire;

use Livewire\Component;
use App\Modules\Media\Services\MediaMetadataService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdvancedMediaUploadLivewireComponent extends Component
{
    public $searchQuery = '';
    public $searchResults = [];
    public $selectedMetadata = null;
    public $uploadProgress = 0;
    public $isUploading = false;
    public $uploadCompleted = false;
    public $fileUuid = null;
    public $fileName = '';
    public $fileSize = '';
    public $uploadSpeed = '0 MB/s';
    public $isProcessing = false;
    public $processingProgress = 0;
    public $processingType = '';

    protected $listeners = [
        'studio-upload-started' => 'onUploadStarted',
        'studio-upload-progress' => 'onUploadProgress',
        'studio-upload-success' => 'onUploadSuccess',
        'studio-upload-error' => 'onUploadError',
    ];

    public function getActiveProcessesProperty()
    {
        return \App\Modules\Media\Models\MediaProcess::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->with('file')
            ->latest()
            ->get();
    }

    public function checkProcessingStatus()
    {
        $processes = $this->activeProcesses;
        
        if (count($processes) > 0) {
            $this->isProcessing = true;
            
            // Specifically track the current upload if it exists
            if ($this->fileUuid) {
                $currentProcess = \App\Modules\Media\Models\MediaProcess::where('file_uuid', $this->fileUuid)
                    ->latest()
                    ->first();
                
                if ($currentProcess) {
                    $this->processingProgress = (int) $currentProcess->progress;
                    $this->processingType = $currentProcess->type;
                    
                    if ($currentProcess->status === 'completed') {
                        // Don't immediately turn off if other processes exist
                        if (count($processes) === 1 && $currentProcess->file_uuid === $this->fileUuid) {
                             $this->uploadCompleted = true;
                        }
                    }
                }
            }
        } else {
            // If we just finished an upload, wait a few seconds before giving up on processing
            if ($this->isProcessing && $this->uploadCompleted) {
                $this->isProcessing = false;
                $this->dispatch('notify', message: 'All studio tasks complete.');
                $this->dispatch('refresh-files');
            }
        }
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 3) {
            $this->searchResults = [];
            return;
        }

        $service = app(MediaMetadataService::class);
        $this->searchResults = $service->search($this->searchQuery);
    }

    public function selectMetadata($type, $id)
    {
        $service = app(MediaMetadataService::class);
        $this->selectedMetadata = $service->getDetails($type, $id);
        $this->searchResults = [];
        $this->searchQuery = $this->selectedMetadata['title'] ?? '';
    }

    public function clearMetadata()
    {
        $this->selectedMetadata = null;
        $this->searchQuery = '';
    }

    public function onUploadStarted($data)
    {
        $this->isUploading = true;
        $this->fileName = $data['fileName'] ?? 'Unknown File';
        $this->fileSize = $data['fileSize'] ?? '0 B';
        $this->uploadCompleted = false;
    }

    public function onUploadProgress($data)
    {
        $this->uploadProgress = $data['progress'] ?? 0;
        $this->uploadSpeed = $data['speed'] ?? '0 B/s';
    }

    public function onUploadSuccess($data)
    {
        $this->isUploading = false;
        $this->uploadProgress = 100;
        $this->uploadSpeed = 'Done';
        $this->fileUuid = $data['fileUuid'] ?? null;
        $this->uploadCompleted = true;
        
        // Start tracking processing (metadata extraction)
        $this->isProcessing = true;
        $this->checkProcessingStatus();

        // If metadata is selected, link it immediately
        if ($this->selectedMetadata) {
            $this->linkMetadata();
        }
    }

    public function onUploadError($message)
    {
        $this->isUploading = false;
        $this->dispatch('notify', message: 'Upload failed: ' . $message, type: 'error');
    }

    public function linkMetadata()
    {
        if (!$this->fileUuid || !$this->selectedMetadata) return;

        $file = \App\Modules\File\Models\File::where('uuid', $this->fileUuid)->first();
        if ($file) {
            $file->update([
                'poster_path' => $this->selectedMetadata['poster_path'],
                'backdrop_path' => $this->selectedMetadata['backdrop_path'],
                'overview' => $this->selectedMetadata['overview'],
                'rating' => $this->selectedMetadata['rating'],
                'release_date' => $this->selectedMetadata['release_date'],
                'cast' => $this->selectedMetadata['cast'],
                'genres' => $this->selectedMetadata['genres'],
                'metadata_fetched' => true
            ]);
            $this->dispatch('notify', message: 'Metadata linked successfully!');
        }
    }

    public function render()
    {
        return view('app.Modules.Media.Views.advanced-media-upload-livewire-component');
    }
}
