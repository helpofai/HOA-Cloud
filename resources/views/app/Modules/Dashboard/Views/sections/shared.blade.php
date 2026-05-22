<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Shared Links</h2>
            <p class="text-xs text-gray-500">Manage your generated sharing links and monitor their performance.</p>
        </div>
    </div>

    <!-- Active Shares Table -->
    <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
        <div class="p-4 border-b border-white/5 bg-white/5 flex items-center justify-between">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Your Active Sharing Links</h3>
            <span class="px-2 py-0.5 glass rounded text-[9px] font-black text-blue-400">{{ $shares->count() }} Links</span>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">Media Content</th>
                    <th class="px-6 py-4">Shared Link</th>
                    <th class="px-6 py-4">Hits</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($shares as $share)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-500">
                                @if(Str::startsWith($share->file->mime_type, 'video/'))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                @elseif(Str::startsWith($share->file->mime_type, 'audio/'))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-300">{{ $share->file->name }}</div>
                                <div class="text-[9px] text-gray-500 font-mono mt-1 uppercase">{{ $share->file->uuid }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $share->ghost_url }}" class="bg-white/5 border border-white/5 rounded-lg px-2 py-1 text-[10px] font-mono text-blue-400 focus:outline-none w-48">
                            <button @click="navigator.clipboard.writeText('{{ $share->ghost_url }}'); $dispatch('notify', { message: 'Link copied to clipboard' })" class="p-1 hover:text-white transition-colors text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-black text-gray-400">{{ number_format($share->hits) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $reportCount = \App\Modules\Security\Models\AbuseReport::where('share_id', $share->id)->where('status', 'pending')->count();
                        @endphp
                        
                        @if($share->is_active && !$share->file->is_killed)
                            <span class="px-2 py-0.5 bg-green-500/10 border border-green-500/20 text-green-500 rounded text-[9px] font-black uppercase tracking-widest">Active</span>
                        @elseif($share->file->is_killed)
                            <span class="px-2 py-0.5 bg-red-600/10 border border-red-500/20 text-red-500 rounded text-[9px] font-black uppercase tracking-widest">File Killed</span>
                        @else
                            <span class="px-2 py-0.5 bg-orange-600/10 border border-orange-500/20 text-orange-500 rounded text-[9px] font-black uppercase tracking-widest">Revoked</span>
                        @endif

                        @if($reportCount > 0)
                            <button wire:click="viewReports({{ $share->id }})" class="mt-1 flex items-center gap-1 text-[8px] font-black text-red-500 uppercase tracking-tighter animate-pulse hover:underline">
                                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $reportCount }} Reports
                            </button>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button 
                                wire:click="toggleShare({{ $share->id }})"
                                class="px-3 py-1.5 glass {{ $share->is_active ? 'bg-orange-600/10 border-orange-500/20 text-orange-500 hover:bg-orange-600 hover:text-white' : 'bg-green-600/10 border-green-500/20 text-green-500 hover:bg-green-600 hover:text-white' }} rounded-lg text-[9px] font-black uppercase tracking-widest transition-all"
                            >
                                {{ $share->is_active ? 'Revoke Link' : 'Activate Link' }}
                            </button>
                            <button 
                                wire:click="deleteShare({{ $share->id }})"
                                wire:confirm="Permanently delete this sharing link? This cannot be undone."
                                class="w-8 h-8 rounded-lg glass border-white/10 text-gray-500 hover:bg-red-600/20 hover:text-red-500 transition-all flex items-center justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center opacity-30">
                        <div class="text-xs font-black uppercase tracking-widest">No shared links generated yet</div>
                        <p class="text-[10px] mt-2">Go to your files and click the share icon to generate links.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
