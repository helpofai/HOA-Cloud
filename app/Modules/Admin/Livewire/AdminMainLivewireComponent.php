<?php

namespace App\Modules\Admin\Livewire;

use Livewire\Component;

use App\Shared\Models\Setting;

class AdminMainLivewireComponent extends Component
{
    public $section = 'overview'; // overview, users, files, domains, media-engine, settings, abuse
    
    // API Settings
    public $tmdbApiKey = '';
    public $omdbApiKey = '';
    public $useOmdb = false;

    protected $queryString = ['section'];

    public function mount()
    {
        $this->tmdbApiKey = (string) Setting::get('tmdb_api_key', config('hoa-cloud.tmdb.api_key', ''));
        $this->omdbApiKey = (string) Setting::get('omdb_api_key', config('hoa-cloud.omdb.api_key', ''));
        $this->useOmdb = (bool) Setting::get('use_omdb', false);
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
        $this->dispatch('url-updated', section: $section);
    }

    public function render()
    {
        return view('app.Modules.Admin.Views.admin-main-livewire-component')
            ->layout('layouts.dashboard', ['title' => 'Super Admin - Hoa Cloud']);
    }
}
