@extends('layouts.app')

@section('title', 'Hoa Cloud - Professional Evasive File Sharing')

@section('content')
    <!-- Navbar -->
    @include('components.home.HomeNavbarComponent')

    <main>
        <!-- Hero Section -->
        @include('components.home.HomeHeroComponent')

        <!-- Media Grid Section -->
        <livewire:home-media-grid />

        <!-- Features Section -->
        @include('components.home.HomeFeaturesComponent')

        <!-- Pricing Section -->
        @include('components.home.HomePricingComponent')

        <!-- Testimonials Section -->
        @include('components.home.HomeTestimonialsComponent')
    </main>

    <!-- Footer -->
    @include('components.home.HomeFooterComponent')
@endsection
