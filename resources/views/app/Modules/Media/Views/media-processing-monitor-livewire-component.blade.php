<div wire:poll.2s class="fixed bottom-0 right-0 z-[150] w-full max-w-md p-4 space-y-3 pointer-events-none">
    @foreach($this->processes as $process)
    <div wire:key="process-{{ $process->id }}" class="pointer-events-auto animate-fadeIn">
        <div class="glass-card p-4 rounded-2xl border-blue-500/20 shadow-2xl relative overflow-hidden">
            <!-- Progress Shimmer Background -->
            <div class="absolute inset-0 bg-blue-500/5 transition-all duration-1000" style="width: {{ $process->progress }}%"></div>
            
            <div class="relative z-10 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-500">
                            @if($process->type === 'transcode')
                                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @elseif($process->type === 'hls')
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="text-[10px] font-black uppercase tracking-widest text-blue-500">{{ $process->type }} Engine</div>
                            <div class="text-xs font-bold text-white truncate max-w-[200px]">{{ $process->file ? $process->file->name : 'Initializing...' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-blue-500">{{ number_format($process->progress, 0) }}%</div>
                    </div>
                </div>

                <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.5)] transition-all duration-1000 ease-out shimmer" style="width: {{ $process->progress }}%"></div>
                </div>

                <div class="flex items-center justify-between text-[9px] font-bold text-gray-500 uppercase tracking-widest">
                    <span>Status: {{ $process->status }}</span>
                    <span class="text-blue-400">Processing Node #01</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
