<?php

namespace App\Modules\Security\Livewire;

use Livewire\Component;
use App\Modules\Security\Models\AbuseReport;
use App\Modules\File\Models\Share;
use App\Shared\Models\Setting;
use Illuminate\Support\Facades\Request;

class AbuseReportingLivewireComponent extends Component
{
    public $reportedUrl = '';
    public $reason = '';
    public $details = '';
    public $submitted = false;

    public function mount()
    {
        if (!Setting::get('abuse_system_enabled', true)) {
            abort(404, 'Reporting system is currently offline.');
        }

        $this->reportedUrl = request()->query('url', '');
    }

    public function submit()
    {
        $this->validate([
            'reportedUrl' => 'required|url|max:255',
            'reason' => 'required|string|max:100',
            'details' => 'nullable|string|max:1000'
        ]);

        // Attempt to find share and file from URL
        $shareId = null;
        $fileId = null;

        // HOA Cloud URL pattern: /v/HASH
        if (preg_match('/\/v\/([a-zA-Z0-9]+)/', $this->reportedUrl, $matches)) {
            $share = Share::where('share_hash', $matches[1])->first();
            if ($share) {
                $shareId = $share->id;
                $fileId = $share->file_id;
            }
        }

        $report = AbuseReport::create([
            'file_id' => $fileId,
            'share_id' => $shareId,
            'reported_url' => $this->reportedUrl,
            'reporter_ip' => request()->ip(),
            'reason' => $this->reason,
            'details' => $this->details,
            'status' => 'pending'
        ]);

        // Auto-Kill logic
        if ($shareId) {
            $reportCount = AbuseReport::where('share_id', $shareId)->count();
            $threshold = (int) Setting::get('abuse_auto_kill_threshold', 5);
            
            if ($reportCount >= $threshold) {
                Share::where('id', $shareId)->update(['is_active' => false]);
            }
        }

        $this->submitted = true;
    }

    public function render()
    {
        return view('app.Modules.Security.Views.abuse-reporting-livewire-component')
            ->layout('layouts.app', ['title' => 'Report Abuse - Hoa Cloud']);
    }
}
