<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->user()->settings['theme'] ?? 'dark' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard - Hoa Cloud' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .dark body { background-color: #0a0a0a; color: #f5f5f5; }
        .light body { background-color: #f5f5f5; color: #0a0a0a; }
        body { overflow: hidden; transition: background-color 0.3s, color 0.3s; }
        .sidebar-width { width: 260px; }
        .content-calc { width: calc(100% - 260px); }
        
        /* Professional Thin Scrollbar */
        .custom-scroll::-webkit-scrollbar { 
            width: 4px; 
            height: 4px;
        }
        .custom-scroll::-webkit-scrollbar-track { 
            background: transparent; 
        }
        .custom-scroll::-webkit-scrollbar-thumb { 
            background: rgba(128,128,128,0.1); 
            border-radius: 20px; 
            transition: background 0.3s;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover { 
            background: rgba(128,128,128,0.3); 
        }

        /* Side Nav specific scroll visibility */
        nav.custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(128,128,128,0.05);
        }
        nav.custom-scroll:hover::-webkit-scrollbar-thumb {
            background: rgba(128,128,128,0.2);
        }

        .dark .glass-dark { background: rgba(15, 15, 15, 0.7); backdrop-filter: blur(20px); }
        .light .glass-dark { background: rgba(240, 240, 240, 0.7); backdrop-filter: blur(20px); }
        .dark .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .light .glass-card { background: rgba(0, 0, 0, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(0, 0, 0, 0.05); }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.5s ease forwards; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer {
            background: linear-gradient(90deg, rgba(37,99,235,0.8) 0%, rgba(59,130,246,1) 50%, rgba(37,99,235,0.8) 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body class="antialiased flex h-screen" x-data="{ theme: '{{ auth()->user()->settings['theme'] ?? 'dark' }}' }" x-on:theme-updated.window="theme = $event.detail.theme; document.documentElement.className = theme">
    <!-- Global Loading Overlay -->
    <div wire:loading.delay.longer class="fixed inset-0 z-[200] flex items-center justify-center bg-black/20 backdrop-blur-[2px]">
        <div class="w-12 h-12 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin"></div>
    </div>

    <!-- Background Mesh -->
    <div class="fixed inset-0 z-[-1] opacity-30">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-900/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-900/20 blur-[120px] rounded-full"></div>
    </div>

    {{ $slot }}

    @include('components.media.AudioMiniPlayerComponent')
    <livewire:media-processing-monitor />

    @livewireScripts
</body>
</html>
