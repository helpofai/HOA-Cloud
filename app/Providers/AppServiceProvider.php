<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        \Livewire\Livewire::component('home-media-grid', \App\Modules\Media\Livewire\HomeMediaGridLivewireComponent::class);
        \Livewire\Livewire::component('advanced-media-upload', \App\Modules\Media\Livewire\AdvancedMediaUploadLivewireComponent::class);
        \Livewire\Livewire::component('media-processing-monitor', \App\Modules\Media\Livewire\MediaProcessingMonitorLivewireComponent::class);
        \Livewire\Livewire::component('media-pipeline', \App\Modules\Media\Livewire\MediaPipelineLivewireComponent::class);
    }
}
