<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- API Configuration -->
    <div class="lg:col-span-2 space-y-8">
        <x-admin.card 
            title="Media Metadata Engine" 
            subtitle="Configure external data sources"
            variant="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </x-slot:icon>
            
            <x-slot:headerAction>
                <div class="flex items-center gap-2 px-3 py-1 glass rounded-full">
                    <span class="w-2 h-2 rounded-full bg-green-500 breathe"></span>
                    <span class="text-[9px] font-black text-gray-400 uppercase">Engine Active</span>
                </div>
            </x-slot:headerAction>

            <form wire:submit.prevent="saveApiSettings" class="space-y-8">
                <!-- TMDB Setup -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_square_2-d537fb228cf3ded904ef09b136fe3fec72548ebc1fea3fbbd1ad9e36364db38b.svg" class="w-20">
                    </div>
                    <h4 class="text-sm font-bold mb-4 flex items-center gap-2 text-blue-400">The Movie Database (TMDB)</h4>
                    <x-admin.input 
                        label="API Key (v3 auth)" 
                        model="tmdbApiKey" 
                        type="password" 
                        placeholder="Enter TMDB API Key..."
                    />
                </div>

                <!-- OMDb Setup -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-yellow-500 font-black text-4xl">OMDb</div>
                    <div class="mb-4">
                        <x-admin.toggle 
                            label="OMDb API (Fallback Engine)" 
                            model="useOmdb" 
                            variant="yellow" 
                        />
                    </div>
                    <div class="space-y-4 {{ $useOmdb ? '' : 'opacity-40 grayscale pointer-events-none' }} transition-all">
                        <x-admin.input 
                            label="API Key (Required for usage)" 
                            model="omdbApiKey" 
                            type="password" 
                            placeholder="Enter OMDb API Key..."
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-[9px] text-gray-500 max-w-xs uppercase font-bold leading-relaxed">
                        Settings are applied instantly to background scraper jobs. Existing media will not be re-indexed unless manually triggered.
                    </p>
                    <button type="submit" class="px-10 py-4 bg-blue-600 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-105 active:scale-95">
                        Deploy Configuration
                    </button>
                </div>
            </form>
        </x-admin.card>

        <div class="glass-card p-8">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6">Engine Diagnostics</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.status-card 
                    label="TMDB Status" 
                    :status="$tmdbApiKey ? 'active' : 'inactive'"
                >
                    <x-slot:icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot:icon>
                    {{ $tmdbApiKey ? 'Connected' : 'Missing Key' }}
                </x-admin.status-card>

                <x-admin.status-card 
                    label="OMDb Status" 
                    :status="$useOmdb ? ($omdbApiKey ? 'warning' : 'inactive') : 'inactive'"
                >
                    <x-slot:icon>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </x-slot:icon>
                    {{ $useOmdb ? ($omdbApiKey ? 'Enabled' : 'Key Missing') : 'Disabled' }}
                </x-admin.status-card>
            </div>
        </div>
    </div>

    <!-- System Binaries -->
    <div class="space-y-8">
        <x-admin.card 
            title="Portable Binaries" 
            variant="red"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </x-slot:icon>
            
            <div class="space-y-6">
                <x-admin.input 
                    label="FFmpeg Engine" 
                    :value="config('hoa-cloud.bin.ffmpeg')" 
                    readonly 
                    icon='<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                />

                <x-admin.input 
                    label="FFprobe Scraper" 
                    :value="config('hoa-cloud.bin.ffprobe')" 
                    readonly 
                    icon='<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                />

                <div class="pt-6 border-t border-white/5 space-y-3">
                    <button wire:click="clearMetadataCache" class="w-full py-3 rounded-xl glass text-[10px] font-bold uppercase hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Clear Metadata Cache
                    </button>
                    <button wire:click="reIndexAllFiles" class="w-full py-3 rounded-xl glass text-[10px] font-bold uppercase hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                        Force Re-Index All Files
                    </button>
                </div>
            </div>
        </x-admin.card>

        <div class="glass-card p-8 border-orange-500/10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h4 class="text-sm font-bold uppercase tracking-tight text-orange-400">Security Warning</h4>
            </div>
            <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                Changing API keys or rotating binary paths can disrupt active stream processes. Use diagnostic utilities with caution on production nodes.
            </p>
        </div>
    </div>
</div>
