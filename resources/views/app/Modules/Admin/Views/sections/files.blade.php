<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">File Monitoring</h2>
            <p class="text-xs text-gray-500">Global oversight of all uploaded media and their security status.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative group">
                <input 
                    type="text" 
                    wire:model.live="searchFile" 
                    placeholder="Search by name or UUID..." 
                    class="w-64 glass bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-red-500 outline-none transition-all"
                >
                <div class="absolute right-3 top-2.5 opacity-20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <button wire:click="loadFiles" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/5 transition-all">
                Refresh
            </button>
        </div>
    </div>

    <!-- Files Table -->
    <div class="glass-card overflow-hidden border-white/5">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">Media</th>
                    <th class="px-6 py-4">Metadata</th>
                    <th class="px-6 py-4">Owner</th>
                    <th class="px-6 py-4 text-center">Security</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($files as $file)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-14 rounded-lg bg-white/5 border border-white/5 flex-shrink-0 relative overflow-hidden flex items-center justify-center">
                                @if($file->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w92{{ $file->poster_path }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] font-bold text-gray-600 uppercase">{{ $file->extension }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-xs font-bold truncate max-w-[200px]" title="{{ $file->name }}">{{ $file->name }}</div>
                                <div class="text-[10px] text-gray-500 font-mono mt-1 uppercase">{{ number_format($file->size / 1048576, 2) }} MB • {{ $file->extension }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($file->metadata_fetched)
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-green-500 font-black uppercase flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Synced
                                </span>
                                <div class="text-[9px] text-gray-500 font-bold">
                                    {{ $file->width }}x{{ $file->height }} • {{ gmdate('H:i:s', $file->duration) }}
                                </div>
                            </div>
                        @else
                            <span class="text-[10px] text-orange-500 font-black uppercase flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full breathe"></span>
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs">
                        <div class="text-gray-300 font-bold">{{ $file->user->name ?? 'System' }}</div>
                        <div class="text-[10px] text-gray-500">{{ $file->user->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 glass text-[9px] font-black uppercase tracking-tighter rounded {{ $file->metadata_fetched ? 'text-green-500' : 'text-red-500' }}">
                            {{ $file->metadata_fetched ? 'Safe' : 'Flagged' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button 
                                wire:click="killFile('{{ $file->uuid }}')"
                                wire:confirm="Activate Global Kill Switch for this file? This will instantly invalidate all active stream tokens."
                                class="w-8 h-8 rounded-lg glass border-red-500/20 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg shadow-red-500/0 hover:shadow-red-500/20"
                                title="Kill Switch"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </button>
                            <button 
                                wire:click="deleteFile('{{ $file->uuid }}')"
                                wire:confirm="Permanently purge this file and its data? This cannot be undone."
                                class="w-8 h-8 rounded-lg glass border-white/10 text-gray-400 flex items-center justify-center hover:bg-white/10 hover:text-white transition-all"
                                title="Purge"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center opacity-30">
                        <div class="text-xs font-black uppercase tracking-widest">System is empty</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($files) >= 50)
    <p class="text-[10px] text-center text-gray-500 uppercase font-bold tracking-widest">Showing last 50 entries. Refine search for specific records.</p>
    @endif
</div>
