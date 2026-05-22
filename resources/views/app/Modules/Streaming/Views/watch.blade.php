@extends('layouts.app')

@section('title', 'Streaming ' . $file->name . ' - HOA Cloud')

@section('content')
<link rel="stylesheet" href="{{ asset('css/plyr.css') }}">
<link rel="stylesheet" href="{{ asset('css/player.css') }}">

<div class="min-h-screen bg-[#050505] relative overflow-x-hidden">
    <!-- Immersive Backdrop -->
    @if($file->backdrop_path)
    <div class="absolute inset-0 z-0">
        <img src="{{ config('hoa-cloud.tmdb.image_url') }}{{ $file->backdrop_path }}" class="w-full h-full object-cover opacity-20 blur-3xl scale-110">
        <div class="absolute inset-0 bg-gradient-to-b from-[#050505]/40 via-[#050505] to-[#050505]"></div>
    </div>
    @endif

    <div class="relative z-10 flex flex-col items-center pt-8 sm:pt-16 pb-20">
        <!-- Player Container -->
        <div class="w-full max-w-6xl aspect-video glass-card border-white/5 overflow-hidden relative group shadow-[0_0_100px_rgba(0,0,0,0.8)]">
            
            <!-- Invisible Overlay (Anti-Scraping) -->
            <div class="ghost-overlay" id="ghost-overlay"></div>

            <!-- Dynamic Floating Watermark -->
            @if($watermark['enabled'])
            <div class="dynamic-watermark" style="opacity: {{ $watermark['opacity'] }}; animation: float-{{ $watermark['speed'] }} 20s infinite linear;">
                GH-{{ strtoupper(substr($file->uuid, 0, 8)) }} | {{ request()->ip() }} | SECURE STREAM
            </div>
            @endif

            <!-- Professional Player -->
            <div class="w-full h-full">
                <video id="ghost-player" class="w-full h-full" playsinline crossorigin controls poster="{{ $file->backdrop_path ? config('hoa-cloud.tmdb.image_url') . $file->backdrop_path : '' }}">
                    <source src="" type="{{ $file->mime_type }}">
                </video>
            </div>

            <!-- Loading Overlay -->
            <div id="player-loader" class="absolute inset-0 z-20 bg-[#050505] flex flex-col items-center justify-center gap-4 transition-opacity duration-700">
                <div class="w-16 h-16 border-4 border-blue-600/10 border-t-blue-600 rounded-full animate-spin shadow-[0_0_20px_rgba(37,99,235,0.4)]"></div>
                <div class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 animate-pulse">Initializing Evasive Gateway</div>
            </div>
        </div>

        <!-- Media Context Area -->
        <div class="w-full max-w-6xl mt-12 px-6 sm:px-0 grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Main Details -->
            <div class="lg:col-span-8 space-y-10">
                <div class="space-y-6">
                    <div class="flex flex-wrap items-center gap-3">
                        @if($file->rating)
                        <div class="px-2 py-0.5 bg-yellow-500 text-black text-[10px] font-black rounded uppercase tracking-tighter flex items-center gap-1">
                            <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            {{ $file->rating }}
                        </div>
                        @endif
                        <div class="px-2 py-0.5 glass-dark border border-white/10 text-gray-400 text-[10px] font-black rounded uppercase tracking-widest">{{ $file->extension }}</div>
                        <div class="px-2 py-0.5 glass-dark border border-white/10 text-gray-400 text-[10px] font-black rounded uppercase tracking-widest">{{ $file->release_date ?? $file->created_at->year }}</div>
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tighter leading-[0.9]">{{ $file->name }}</h1>
                    
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-white/10">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($file->user->name) }}&background=2563eb&color=fff" class="w-full h-full object-cover">
                            </div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Shared by <span class="text-white">{{ $file->user->name }}</span></div>
                        </div>
                        <div class="h-4 w-px bg-white/10"></div>
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">{{ Number::fileSize($file->size) }}</div>
                    </div>
                </div>

                @if($file->overview)
                <div class="glass-card p-10 border-white/5 rounded-[2.5rem] relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mb-6">Synopsis</h2>
                    <p class="text-base text-gray-300 leading-relaxed font-medium">{{ $file->overview }}</p>
                    
                    @if($file->genres)
                    <div class="flex flex-wrap gap-2 mt-8">
                        @foreach($file->genres as $genre)
                        <span class="px-4 py-1.5 bg-white/5 border border-white/5 rounded-full text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $genre }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="lg:col-span-4 space-y-8">
                <div class="flex items-center gap-3">
                    <button class="flex-1 px-6 py-4 bg-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Share Link
                    </button>
                    <a href="{{ route('ghost-hop.report') }}" class="w-14 h-14 glass flex items-center justify-center rounded-2xl text-gray-500 hover:text-red-500 transition-all hover:bg-red-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </a>
                </div>

                @if($file->cast)
                <div class="glass-card p-8 border-white/5 rounded-3xl">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mb-6">Top Cast</h2>
                    <div class="space-y-5">
                        @foreach(array_slice($file->cast, 0, 5) as $actor)
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-blue-600/10 border border-blue-500/10 flex items-center justify-center text-xs font-black text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                {{ substr($actor, 0, 1) }}
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest text-gray-400 group-hover:text-white transition-colors">{{ $actor }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="p-8 glass bg-red-500/5 border border-red-500/10 rounded-3xl">
                    <div class="flex items-center gap-3 text-red-500 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">Anti-Scraping Active</span>
                    </div>
                    <p class="text-[10px] text-gray-600 font-bold uppercase leading-relaxed">
                        This media stream is encrypted and single-use. Direct download or redistribution is forbidden.
                    </p>
                </div>
            </div>
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

        // Dynamic Ghost URL Source Generation
        const rawStreamUrl = "{{ route('ghost-hop.stream') }}?token=" + streamToken;
        
        // Handshake simulation for security
        setTimeout(() => {
            const source = document.createElement('source');
            source.src = rawStreamUrl;
            source.type = "{{ $file->mime_type }}";
            video.appendChild(source);
            video.load();
        }, 1200);

        player.on('canplay', () => {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 700);
        });

        // Anti-Scraping deterrents
        document.addEventListener('contextmenu', e => e.preventDefault());

        overlay.addEventListener('click', () => {
            if (player.playing) {
                player.pause();
            } else {
                player.play();
            }
        });

        // Keyboard Shortcuts (Netflix style)
        document.addEventListener('keydown', e => {
            if (e.code === 'Space' || e.code === 'KeyK') { e.preventDefault(); player.togglePlay(); }
            if (e.code === 'KeyF') player.fullscreen.toggle();
            if (e.code === 'KeyM') player.muted = !player.muted;
            if (e.code === 'ArrowRight') player.forward(10);
            if (e.code === 'ArrowLeft') player.rewind(10);
        });

        // Block typical inspection keys
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

