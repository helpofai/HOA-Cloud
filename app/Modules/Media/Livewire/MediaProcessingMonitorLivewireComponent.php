<?php

namespace App\Modules\Media\Livewire;

use Livewire\Component;
use App\Modules\Media\Models\MediaProcess;
use Illuminate\Support\Facades\Auth;

class MediaProcessingMonitorLivewireComponent extends Component
{
    public function getProcessesProperty()
    {
        return MediaProcess::with('file')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('app.Modules.Media.Views.media-processing-monitor-livewire-component');
    }
}
