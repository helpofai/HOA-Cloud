<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Dashboard\Livewire\DashboardMainLivewireComponent;
use App\Modules\Admin\Livewire\AdminMainLivewireComponent;
use App\Modules\Auth\Livewire\LoginAuthLivewireComponent;
use App\Modules\Auth\Livewire\RegisterAuthLivewireComponent;

Route::get('/', function () {
    return view('home');
});

// Authentication
Route::get('/login', LoginAuthLivewireComponent::class)->name('login');
Route::get('/register', RegisterAuthLivewireComponent::class)->name('register');

// Dashboard & Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardMainLivewireComponent::class)->name('dashboard');
    
    // Admin Routes - Only for Admin and Super Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', AdminMainLivewireComponent::class)->name('admin');
    });
});

// Public Abuse Reporting Gateway
Route::get('/report-abuse', \App\Modules\Security\Livewire\AbuseReportingLivewireComponent::class)->name('ghost-hop.report');
