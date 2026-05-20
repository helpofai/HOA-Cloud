<?php

namespace App\Modules\Admin\Livewire;

use Livewire\Component;

class AdminMainLivewireComponent extends Component
{
    public $section = 'overview'; // overview, users, files, domains, settings, abuse

    protected $queryString = ['section'];

    public function setSection($section)
    {
        $this->section = $section;
        $this->dispatch('url-updated', section: $section);
    }

    public function render()
    {
        return view('app.Modules.Admin.Views.admin-main-livewire-component')
            ->layout('layouts.dashboard', ['title' => 'Super Admin - Hoa Cloud']);
    }
}
