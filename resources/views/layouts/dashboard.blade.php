<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard - Hoa Cloud' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="{{ asset('js/alpine.js') }}"></script>
    
    @livewireStyles
    <style>
        body { background-color: #0a0a0a; color: #f5f5f5; overflow: hidden; }
        .sidebar-width { width: 260px; }
        .content-calc { width: calc(100% - 260px); }
        
        /* Custom scrollbar for media grid */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="antialiased flex h-screen">
    <!-- Background Mesh -->
    <div class="fixed inset-0 z-[-1] opacity-30">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-900/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-900/20 blur-[120px] rounded-full"></div>
    </div>

    {{ $slot }}

    @include('components.media.AudioMiniPlayerComponent')

    @livewireScripts
    <script>
        window.addEventListener('url-updated', event => {
            const url = new URL(window.location);
            url.searchParams.set('section', event.detail.section);
            window.history.pushState({}, '', url);
        });
    </script>
</body>
</html>
