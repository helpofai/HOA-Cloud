<?php

namespace App\Modules\Media\Livewire;

use Livewire\Component;
use App\Modules\Media\Models\MediaProcess;
use Illuminate\Support\Facades\Auth;

class MediaPipelineLivewireComponent extends Component
{
    public function getProcessesProperty()
    {
        $query = MediaProcess::with(['file', 'user'])->latest();

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query->paginate(20);
    }

    public function render()
    {
        return view('app.Modules.Media.Views.media-pipeline-livewire-component');
    }
}
