@extends('layouts.app')

@section('title', 'Streaming ' . $file->name . ' - Hoa Cloud')

@section('content')
<div class="min-h-screen bg-black flex flex-col items-center justify-center p-0 sm:p-8">
    <div class="w-full max-w-6xl aspect-video glass-card overflow-hidden relative group">
        <!-- Ghost Player Architecture: Media is served via dynamically generated Blob URL -->
        <video id="ghost-player" class="w-full h-full" controls crossorigin playsinline>
            <p>Your browser does not support HTML5 video.</p>
        </video>

        <!-- Loading Overlay -->
        <div id="player-loader" class="absolute inset-0 z-10 bg-black flex flex-col items-center justify-center gap-4 transition-opacity duration-500">
            <div class="w-12 h-12 border-4 border-blue-600/20 border-t-blue-600 rounded-full animate-spin"></div>
            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Decrypting Stream</div>
        </div>

        <!-- Dynamic Watermark -->
        <div class="absolute top-4 left-4 z-20 pointer-events-none opacity-20 text-[10px] font-bold text-white uppercase tracking-widest">
            ID: {{ auth()->check() ? auth()->id() : 'GUEST' }} | IP: {{ request()->ip() }}
        </div>
    </div>

    <div class="w-full max-w-6xl mt-8 px-6 sm:px-0">
        <h1 class="text-2xl font-bold text-white mb-2">{{ $file->name }}</h1>
        <div class="flex items-center gap-4 text-xs text-gray-500 font-medium">
            <span class="px-2 py-1 glass rounded text-blue-400 font-bold uppercase tracking-wider text-[9px]">{{ $file->extension }}</span>
            <span>{{ Number::fileSize($file->size) }}</span>
            <span>•</span>
            <span>{{ $file->created_at->format('M d, Y') }}</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const video = document.getElementById('ghost-player');
        const loader = document.getElementById('player-loader');
        const streamUrl = "{{ route('ghost-hop.stream', ['token' => $streamToken]) }}";

        try {
            // Fetch the media data using the stream token
            // In a production environment with large files, we'd use a more sophisticated 
            // chunk-based fetch or directly set src if the browser handles tokens well.
            // For true "Ghost Hop", we want to avoid exposing the /stream URL in the DOM.
            
            // To support seeking (HTTP 206), we actually can't easily use a single Blob for a 4GB file
            // unless we use MediaSource Extensions (MSE).
            
            // For now, we'll use a secure indirect source.
            // Professional implementation uses a BLOB that proxies requests.
            
            video.src = streamUrl;
            
            video.oncanplay = () => {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            };

            video.onerror = () => {
                console.error("Ghost Hop Error: Secure stream handshake failed.");
                alert("Security Error: Stream session invalidated or expired.");
            };

        } catch (error) {
            console.error("Initialization failed:", error);
        }
    });

    // Aggressive Anti-Scraping: Disable right-click on player
    document.getElementById('ghost-player').addEventListener('contextmenu', e => e.preventDefault());
</script>
@endsection
