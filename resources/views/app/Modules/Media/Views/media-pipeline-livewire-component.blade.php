<div wire:poll.3s class="space-y-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-white uppercase">Media Processing Pipeline</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Real-time status of all media optimization tasks</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="$refresh" class="p-2 glass rounded-xl text-gray-400 hover:text-white transition-colors" title="Force Refresh">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
            <div class="px-4 py-2 glass rounded-xl text-[10px] font-black uppercase tracking-widest text-blue-400 animate-pulse">
                Live Monitoring Active
            </div>
        </div>
    </div>

    <div class="glass-card p-0 rounded-[2.5rem] border-white/5 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
                        <th class="px-8 py-6">Media & Engine</th>
                        <th class="px-8 py-6">Owner</th>
                        <th class="px-8 py-6">Status</th>
                        <th class="px-8 py-6 w-64">Progress</th>
                        <th class="px-8 py-6 text-right">Started</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($this->processes as $process)
                    <tr wire:key="pipeline-{{ $process->id }}" class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center shrink-0">
                                    @if($process->type === 'transcode')
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    @elseif($process->type === 'merge')
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    @else
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-black uppercase tracking-widest text-gray-500 mb-1">{{ $process->type }} Engine</div>
                                    @php
                                        $displayName = 'Initializing Pipeline...';
                                        if ($process->file) {
                                            $displayName = $process->file->name;
                                        } elseif ($process->command && str_contains($process->command, 'for ')) {
                                            $displayName = Str::after($process->command, 'for ');
                                        }
                                    @endphp
                                    <div class="text-sm font-bold text-white truncate max-w-[250px]" title="{{ $displayName }}">{{ $displayName }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full border border-blue-500/30 flex items-center justify-center text-[10px] font-bold text-blue-500">
                                    {{ substr($process->user->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-gray-300">{{ $process->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span @class([
                                    'w-2 h-2 rounded-full',
                                    'bg-blue-500 animate-pulse' => $process->status === 'processing',
                                    'bg-yellow-500' => $process->status === 'pending',
                                    'bg-green-500' => $process->status === 'completed',
                                    'bg-red-500' => $process->status === 'failed',
                                ])></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $process->status }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-[10px] font-black text-blue-500 uppercase tracking-tighter">
                                    <span>{{ number_format($process->progress, 0) }}%</span>
                                    @if($process->status === 'processing')
                                        <span class="text-[8px] text-gray-600">Active</span>
                                    @endif
                                </div>
                                <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div @class([
                                        'h-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(37,99,235,0.4)]',
                                        'bg-blue-600 shimmer' => $process->status === 'processing',
                                        'bg-green-600' => $process->status === 'completed',
                                        'bg-red-600' => $process->status === 'failed',
                                        'bg-yellow-600' => $process->status === 'pending',
                                    ]) style="width: {{ $process->progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $process->created_at->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <div class="text-sm font-black uppercase tracking-[0.3em]">Pipeline Idle</div>
                                <p class="text-xs mt-2">No active or recent media tasks found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($this->processes->hasPages())
        <div class="p-8 border-t border-white/5">
            {{ $this->processes->links() }}
        </div>
        @endif
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6 rounded-3xl flex items-center gap-4 border-blue-500/10">
            <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Active Nodes</div>
                <div class="text-xl font-black text-white">01 Secure Node</div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl flex items-center gap-4 border-green-500/10">
            <div class="w-12 h-12 rounded-2xl bg-green-600/10 flex items-center justify-center text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tasks Completed</div>
                <div class="text-xl font-black text-white">{{ \App\Modules\Media\Models\MediaProcess::where('status', 'completed')->count() }} Jobs</div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl flex items-center gap-4 border-purple-500/10">
            <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Queue Health</div>
                <div class="text-xl font-black text-white">100% Operational</div>
            </div>
        </div>
    </div>
</div>
