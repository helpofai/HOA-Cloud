<?php

namespace App\Modules\Dashboard\Livewire;

use Livewire\Component;

class DashboardMainLivewireComponent extends Component
{
    public $section = 'files'; // files, photos, music, videos, shared, bin

    protected $queryString = ['section'];

    public function setSection($section)
    {
        $this->section = $section;
        
        // Use browser history to update URL
        $this->dispatch('url-updated', section: $section);
    }

    public function render()
    {
        return view('app.Modules.Dashboard.Views.dashboard-main-livewire-component')
            ->layout('layouts.dashboard');
    }
}
