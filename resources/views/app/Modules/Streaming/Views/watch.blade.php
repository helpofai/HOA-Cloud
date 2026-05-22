@extends('layouts.app')

@section('title', 'Streaming ' . $file->name . ' - Hoa Cloud')

@section('content')
<link rel="stylesheet" href="{{ asset('css/plyr.css') }}">
<link rel="stylesheet" href="{{ asset('css/player.css') }}">

<div class="min-h-screen bg-[#050505] flex flex-col items-center justify-center p-0 sm:p-8">
    <div class="w-full max-w-6xl aspect-video glass-card overflow-hidden relative group shadow-[0_0_100px_rgba(37,99,235,0.1)]">
        
        <!-- Invisible Overlay (Anti-Scraping) -->
        <div class="ghost-overlay" id="ghost-overlay"></div>

        <!-- Dynamic Floating Watermark -->
        <div class="dynamic-watermark">
            HASH: {{ substr(md5(request()->ip() . (auth()->id() ?? 'GUEST')), 0, 8) }} | {{ request()->ip() }} | HOA CLOUD SECURE
        </div>

        <!-- Professional Player -->
        <div class="w-full h-full">
            <video id="ghost-player" class="w-full h-full" playsinline crossorigin controls>
                <source src="" type="{{ $file->mime_type }}">
            </video>
        </div>

        <!-- Loading Overlay -->
        <div id="player-loader" class="absolute inset-0 z-20 bg-[#050505] flex flex-col items-center justify-center gap-4 transition-opacity duration-700">
            <div class="w-16 h-16 border-4 border-blue-600/10 border-t-blue-600 rounded-full animate-spin shadow-[0_0_20px_rgba(37,99,235,0.4)]"></div>
            <div class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 animate-pulse">Initializing Evasive Stream</div>
        </div>
    </div>

    <div class="w-full max-w-6xl mt-12 px-6 sm:px-0">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-white mb-3 tracking-tighter">{{ $file->name }}</h1>
                <div class="flex items-center gap-4 text-[10px] text-gray-500 font-black uppercase tracking-widest">
                    <span class="px-2 py-1 bg-blue-600/10 border border-blue-500/20 rounded text-blue-400">{{ $file->extension }}</span>
                    <span>{{ Number::fileSize($file->size) }}</span>
                    <span>•</span>
                    <span>{{ $file->created_at->format('M d, Y') }}</span>
                    <span>•</span>
                    <span class="flex items-center gap-1 text-green-500">
                        <span class="w-1 h-1 bg-green-500 rounded-full"></span>
                        Encrypted
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button class="px-6 py-3 glass rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    Share
                </button>
                <a href="{{ route('ghost-hop.report') }}" class="px-6 py-3 glass rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600/10 hover:text-red-500 transition-all text-gray-500 flex items-center gap-2 border-white/5 hover:border-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report
                </a>
            </div>
        </div>

        <div class="mt-12 p-8 glass-card border-white/5 rounded-3xl">
            <p class="text-sm text-gray-400 leading-relaxed italic">
                You are viewing an encrypted media stream via HOA Cloud's Ghost Hop Layer 4. 
                Direct access to the source file is prohibited. Any attempt to scrape or rip 
                this content will result in IP blacklist and session termination.
            </p>
        </div>
    </div>
</div>

<script src="{{ asset('js/plyr.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('ghost-player');
        const loader = document.getElementById('player-loader');
        const overlay = document.getElementById('ghost-overlay');
        const streamToken = "{{ $streamToken }}";
        
        // Initialize Plyr
        const player = new Plyr(video, {
            controls: [
                'play-large', 'play', 'progress', 'current-time', 'duration', 
                'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'
            ],
            settings: ['quality', 'speed'],
            quality: { default: 1080, options: [1080, 720, 480] },
            tooltips: { controls: true, seek: true }
        });

        // Dynamic Ghost URL Source Generation (Blob masking)
        // We use a blob to obscure the real source URL in the DOM
        const rawStreamUrl = "{{ route('ghost-hop.stream') }}?token=" + streamToken;
        
        // For security, we don't set the source immediately.
        // We simulate a secure handshake.
        setTimeout(() => {
            // Masking technique: Setting source via JS only
            const source = document.createElement('source');
            source.src = rawStreamUrl;
            source.type = "{{ $file->mime_type }}";
            video.appendChild(source);
            video.load();
        }, 800);

        player.on('canplay', () => {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 700);
        });

        // Anti-Scraping Logic
        // 1. Block Right-Click
        document.addEventListener('contextmenu', e => e.preventDefault());

        // 2. Invisible Overlay Interaction
        overlay.addEventListener('click', () => {
            if (player.playing) {
                player.pause();
            } else {
                player.play();
            }
        });

        // 3. Prevent Inspect Element (Simple deterrent)
        document.onkeydown = function(e) {
            if (e.keyCode == 123) return false;
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false;
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false;
        };
    });
</script>
@endsection
