<?php

namespace App\Modules\Media\Livewire;

use Livewire\Component;
use App\Modules\File\Models\File;

class HomeMediaGridLivewireComponent extends Component
{
    public $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function getFilesProperty()
    {
        $query = File::where('is_killed', false)
            ->whereNotNull('poster_path'); // Only show files with posters on home page for Netflix look

        if ($this->filter === 'movies') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($this->filter === 'music') {
            $query->where('mime_type', 'like', 'audio/%');
        } elseif ($this->filter === 'docs') {
            $query->where(function($q) {
                $q->where('mime_type', 'like', 'application/%')
                  ->orWhere('mime_type', 'like', 'text/%');
            });
        }

        return $query->latest()->take(18)->get();
    }

    public function render()
    {
        return view('app.Modules.Media.Views.home-media-grid-livewire-component', [
            'files' => $this->files
        ]);
    }
}
