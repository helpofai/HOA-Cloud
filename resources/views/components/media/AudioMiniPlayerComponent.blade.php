<!-- Persistent Mini Player (Spotify Style) -->
<link rel="stylesheet" href="{{ asset('css/audio-player.css') }}">
<div id="audio-mini-player" 
     x-data="{ 
        visible: false, 
        playing: false, 
        trackName: '', 
        trackPoster: '', 
        currentTime: '0:00', 
        duration: '0:00' 
     }" 
     x-on:audio-play.window="
        visible = true;
        trackName = $event.detail[0].name;
        trackPoster = $event.detail[0].poster;
        $dispatch('initialize-wavesurfer', { url: $event.detail[0].url });
     "
     :class="visible ? '' : 'hidden'"
     class="mini-player" x-cloak>
    
    <div class="mini-player-info">
        <img :src="trackPoster || 'https://placehold.co/56x56/1e293b/white?text=Art'" class="mini-player-art" alt="Track Poster">
        <div class="overflow-hidden">
            <div class="text-sm font-bold text-white truncate" x-text="trackName"></div>
            <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Now Streaming</div>
        </div>
    </div>

    <div class="mini-player-controls">
        <div class="mini-player-btns">
            <button class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 6c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1s-1-.45-1-1V7c0-.55.45-1 1-1zm3.66 4.82L17 6.29V17.7l-6.34-4.53c-.44-.32-.44-1.04 0-1.35z"/></svg>
            </button>
            <button @click="window.wavesurfer.playPause()" class="btn-play-pause shadow-lg shadow-white/10">
                <template x-if="!playing">
                    <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </template>
                <template x-if="playing">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                </template>
            </button>
            <button class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 17.71V6.29l6.34 4.53c.44.32.44 1.04 0 1.35L7 17.71zM16 6c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1s-1-.45-1-1V7c0-.55.45-1 1-1z"/></svg>
            </button>
        </div>

        <div class="mini-player-progress">
            <span class="text-[9px] font-mono text-gray-500 w-8" x-text="currentTime"></span>
            <div id="waveform" class="waveform-container"></div>
            <span class="text-[9px] font-mono text-gray-500 w-8" x-text="duration"></span>
        </div>
    </div>

    <div class="mini-player-volume">
        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
        <div class="volume-slider">
            <div class="volume-slider-fill" style="width: 70%"></div>
        </div>
    </div>
</div>

<!-- Wavesurfer Scripts -->
<script src="{{ asset('js/wavesurfer.js') }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        window.addEventListener('initialize-wavesurfer', event => {
            const container = document.getElementById('audio-mini-player');
            const alpineData = Alpine.$data(container);
            
            if (window.wavesurfer) {
                window.wavesurfer.destroy();
            }

            window.wavesurfer = WaveSurfer.create({
                container: '#waveform',
                waveColor: 'rgba(255, 255, 255, 0.1)',
                progressColor: '#2563eb',
                cursorColor: 'transparent',
                barWidth: 2,
                barRadius: 3,
                cursorWidth: 1,
                height: 30,
                barGap: 3,
                url: event.detail.url,
            });

            window.wavesurfer.on('play', () => alpineData.playing = true);
            window.wavesurfer.on('pause', () => alpineData.playing = false);
            
            window.wavesurfer.on('decode', (duration) => {
                alpineData.duration = formatTime(duration);
                window.wavesurfer.play();
            });

            window.wavesurfer.on('timeupdate', (currentTime) => {
                alpineData.currentTime = formatTime(currentTime);
            });

            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
            }
        });
    });
</script>
