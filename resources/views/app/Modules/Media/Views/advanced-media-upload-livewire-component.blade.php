<div class="space-y-8 max-w-7xl mx-auto" wire:poll.3s="checkProcessingStatus">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-white uppercase">Advanced Media Studio</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Professional Movie & Video Injection System</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="$parent.setSection('files')" class="px-5 py-2 glass rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all text-gray-300">
                Cancel & Exit
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Upload & Preview -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Drop Zone -->
            <div 
                id="advanced-drop-zone"
                class="relative h-48 rounded-3xl border-2 border-dashed border-white/10 glass flex flex-col items-center justify-center p-6 transition-all hover:border-blue-500/50 group overflow-hidden"
                x-data="{ isOver: false }"
                @dragover.prevent="isOver = true"
                @dragleave.prevent="isOver = false"
                @drop.prevent="isOver = false"
                :class="isOver ? 'bg-blue-600/5 border-blue-500/50' : ''"
            >
                @if($selectedMetadata && $selectedMetadata['backdrop_path'])
                    <img src="{{ config('hoa-cloud.tmdb.image_url') }}{{ $selectedMetadata['backdrop_path'] }}" class="absolute inset-0 w-full h-full object-cover opacity-20 blur-sm group-hover:blur-none transition-all duration-700">
                @endif

                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-500 mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-white mb-1">Select Video File</h3>
                    <p class="text-[9px] text-gray-500 font-bold uppercase">MP4, MKV, MOV (Max 5GB)</p>
                    <button id="advanced-browse-btn" class="mt-4 px-6 py-2 bg-blue-600 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">
                        Browse Files
                    </button>
                </div>
            </div>

            <!-- Upload Progress -->
            @if($isUploading || $uploadCompleted)
            <div class="glass-card p-6 rounded-3xl space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-500 shrink-0">
                            @if($uploadCompleted)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-black uppercase tracking-widest text-white truncate">{{ $fileName }}</div>
                            <div class="text-[9px] font-bold text-gray-500 uppercase flex items-center gap-2">
                                <span>{{ $fileSize }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-700"></span>
                                <span class="text-blue-500">{{ $uploadCompleted ? 'Finalized' : $uploadSpeed }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-blue-500">{{ $uploadProgress }}%</div>
                    </div>
                </div>
                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 shadow-[0_0_15px_rgba(37,99,235,0.6)] transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
                </div>
            </div>

            <!-- Real-time Processing Console -->
            @if(count($this->activeProcesses) > 0)
            <div class="glass-card p-0 rounded-[2.5rem] border-blue-500/20 shadow-2xl overflow-hidden animate-fadeIn">
                <div class="p-6 border-b border-white/5 bg-blue-600/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-white">Studio Processing Console</h3>
                            <p class="text-[8px] font-bold text-blue-500 uppercase tracking-widest">Hydra Engine v1.0 Active</p>
                        </div>
                    </div>
                    <div class="px-3 py-1 bg-blue-600/10 rounded-full border border-blue-500/20 text-[8px] font-black text-blue-400 uppercase tracking-widest animate-pulse">
                        {{ count($this->activeProcesses) }} Tasks Active
                    </div>
                </div>

                <div class="p-6 space-y-6 max-h-[400px] overflow-y-auto custom-scroll">
                    @foreach($this->activeProcesses as $process)
                    <div wire:key="studio-proc-{{ $process->id }}" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-gray-400">
                                    @if($process->type === 'transcode')
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    @elseif($process->type === 'merge')
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ $process->type }}</div>
                                    <div class="text-[11px] font-bold text-white truncate max-w-[180px]">{{ $process->file ? $process->file->name : 'Initializing Pipeline...' }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black text-blue-500">{{ number_format($process->progress, 0) }}%</div>
                                <div class="text-[8px] font-bold text-gray-600 uppercase tracking-tighter">{{ $process->status }}</div>
                            </div>
                        </div>
                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.4)] transition-all duration-1000 ease-out shimmer" style="width: {{ $process->progress }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="p-4 bg-[#050505] border-t border-white/5 flex items-center justify-center">
                    <div class="flex items-center gap-2 text-[8px] font-black text-gray-700 uppercase tracking-[0.3em]">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        Encrypted Background Pipeline Secure
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Selected Metadata Preview -->
            @if($selectedMetadata)
            <div class="glass-card p-0 rounded-3xl overflow-hidden animate-fadeIn">
                <div class="aspect-[16/9] relative">
                    @if($selectedMetadata['backdrop_path'])
                        <img src="{{ config('hoa-cloud.tmdb.image_url') }}{{ $selectedMetadata['backdrop_path'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-white/5 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/40 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 flex gap-6 items-end">
                        <div class="w-24 aspect-[2/3] rounded-xl overflow-hidden shadow-2xl border border-white/10 shrink-0">
                            @if($selectedMetadata['poster_path'])
                                <img src="{{ config('hoa-cloud.tmdb.image_url') }}{{ $selectedMetadata['poster_path'] }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 pb-2">
                            <h2 class="text-xl font-black text-white leading-none">{{ $selectedMetadata['title'] }}</h2>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-[10px] font-black px-2 py-0.5 bg-yellow-500 text-black rounded uppercase tracking-tighter">{{ $selectedMetadata['rating'] }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $selectedMetadata['release_date'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-xs text-gray-400 leading-relaxed line-clamp-3">{{ $selectedMetadata['overview'] }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedMetadata['genres'] as $genre)
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-white/5 text-gray-500 rounded-lg">{{ $genre }}</span>
                        @endforeach
                    </div>
                    <button wire:click="clearMetadata" class="w-full py-3 rounded-2xl glass text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-500/10 transition-all mt-2">
                        Remove Metadata
                    </button>
                </div>
            </div>
            @else
            <div class="glass-card p-12 rounded-3xl border-dashed border-white/5 flex flex-col items-center justify-center text-center opacity-30">
                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div class="text-[10px] font-black uppercase tracking-widest">Metadata Library Empty</div>
                <p class="text-[9px] mt-2 font-bold uppercase tracking-widest">Search for a movie or TV show to link</p>
            </div>
            @endif
        </div>

        <!-- Right Column: Metadata Search -->
        <div class="lg:col-span-7 space-y-6">
            <div class="glass-card p-8 rounded-3xl space-y-6">
                <div class="relative">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 ml-1">Database Search (TMDB)</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.500ms="searchQuery"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-12 py-4 text-sm focus:border-blue-500/50 transition-all outline-none" 
                            placeholder="Type movie or tv show title..."
                        >
                        <div class="absolute left-4 top-4 text-gray-500" wire:loading.remove wire:target="searchQuery">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div class="absolute left-4 top-4" wire:loading wire:target="searchQuery">
                            <div class="w-5 h-5 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>

                <!-- Results Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto custom-scroll pr-2">
                    @forelse($searchResults as $result)
                    <div 
                        wire:click="selectMetadata('{{ $result['media_type'] }}', {{ $result['id'] }})"
                        class="group p-3 glass border-white/5 rounded-2xl flex gap-4 cursor-pointer hover:border-blue-500/30 transition-all active:scale-95"
                    >
                        <div class="w-16 h-24 rounded-xl overflow-hidden shrink-0 bg-white/5 border border-white/10">
                            @if($result['poster_path'])
                                <img src="{{ config('hoa-cloud.tmdb.image_url') }}{{ $result['poster_path'] }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 py-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black text-white group-hover:text-blue-400 transition-colors line-clamp-2">{{ $result['title'] }}</h4>
                                <div class="text-[9px] font-bold text-gray-500 mt-1 uppercase tracking-tighter">
                                    {{ $result['media_type'] }} • {{ $result['release_date'] }}
                                </div>
                            </div>
                            <div class="text-[9px] text-gray-600 line-clamp-2 italic">{{ $result['overview'] }}</div>
                        </div>
                    </div>
                    @empty
                        @if(strlen($searchQuery) >= 3)
                        <div class="col-span-full py-20 text-center opacity-30">
                            <div class="text-xs font-black uppercase tracking-widest">No results found</div>
                        </div>
                        @else
                        <div class="col-span-full py-20 text-center opacity-30 flex flex-col items-center">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <div class="text-[10px] font-black uppercase tracking-widest">Global Movie Database Access</div>
                        </div>
                        @endif
                    @endforelse
                </div>
            </div>

            <!-- Meta Tips -->
            <div class="p-8 glass-card rounded-3xl border-blue-500/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <h4 class="text-xs font-black uppercase tracking-widest text-blue-500 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    System Intelligence
                </h4>
                <ul class="space-y-4">
                    <li class="flex gap-4 items-start">
                        <div class="w-6 h-6 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500 shrink-0 text-[10px] font-black">1</div>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Select your video file first. The system will start uploading in the background while you choose metadata.</p>
                    </li>
                    <li class="flex gap-4 items-start">
                        <div class="w-6 h-6 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500 shrink-0 text-[10px] font-black">2</div>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Search and select the correct Movie/Show. High-resolution posters and backdrops will be automatically fetched.</p>
                    </li>
                    <li class="flex gap-4 items-start">
                        <div class="w-6 h-6 rounded-lg bg-blue-600/10 flex items-center justify-center text-blue-500 shrink-0 text-[10px] font-black">3</div>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Once upload completes, metadata is permanently linked. Your media will appear in the grid with pro-tier visuals.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Resumable.js Logic for Advanced Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resumable = new Resumable({
                target: '{{ route('upload') }}',
                query: {
                    _token: '{{ csrf_token() }}'
                },
                chunkSize: 10 * 1024 * 1024,
                simultaneousUploads: 3,
                testChunks: true,
                throttleProgressCallbacks: 1,
            });

            const browseBtn = document.getElementById('advanced-browse-btn');
            const dropZone = document.getElementById('advanced-drop-zone');
            
            if (browseBtn) resumable.assignBrowse(browseBtn);
            if (dropZone) resumable.assignDrop(dropZone);

            let startTime = null;

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            resumable.on('fileAdded', function (file) {
                startTime = new Date().getTime();
                resumable.upload();
                
                // For global dashboard drawer
                window.dispatchEvent(new CustomEvent('upload-started', { 
                    detail: { 
                        file: {
                            uniqueIdentifier: file.uniqueIdentifier,
                            fileName: file.fileName,
                            size: file.size
                        }
                    } 
                }));

                // For this component
                Livewire.dispatch('studio-upload-started', { 
                    data: {
                        fileName: file.fileName,
                        fileSize: formatBytes(file.size)
                    }
                });
            });

            resumable.on('fileProgress', function (file) {
                const now = new Date().getTime();
                const secondsElapsed = (now - startTime) / 1000;
                const bytesUploaded = file.progress() * file.size;
                const speed = bytesUploaded / secondsElapsed;
                const formattedSpeed = formatBytes(speed) + '/s';
                const progress = Math.floor(file.progress() * 100);

                // For global dashboard drawer
                window.dispatchEvent(new CustomEvent('upload-progress', { 
                    detail: { 
                        id: file.uniqueIdentifier, 
                        progress: progress 
                    } 
                }));
                
                // For this component
                Livewire.dispatch('studio-upload-progress', { 
                    data: {
                        progress: progress,
                        speed: formattedSpeed
                    }
                });
            });

            resumable.on('fileSuccess', function (file, message) {
                const response = JSON.parse(message);

                // For global dashboard drawer
                window.dispatchEvent(new CustomEvent('upload-success', { 
                    detail: { id: file.uniqueIdentifier } 
                }));

                // For this component
                Livewire.dispatch('studio-upload-success', { 
                    data: {
                        fileUuid: response.file_uuid 
                    }
                });
                Livewire.dispatch('refresh-files');
            });

            resumable.on('fileError', function (file, message) {
                Livewire.dispatch('upload-error', { message: message });
            });
        });
    </script>
</div>
