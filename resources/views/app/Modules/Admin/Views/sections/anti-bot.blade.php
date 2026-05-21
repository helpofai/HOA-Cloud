<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">Anti-Bot & Crawler</h2>
            <p class="text-xs text-gray-500">Protect your source nodes with IP blacklisting and behavioral analysis.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <button wire:click="$refresh" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/5 transition-all">
                Refresh Status
            </button>
        </div>
    </div>

    <!-- System Health Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6 border-white/5 rounded-2xl">
            <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Total Blacklisted IPs</div>
            <div class="text-3xl font-black text-red-500">{{ \App\Modules\Security\Models\Blacklist::where('type', 'ip')->count() }}</div>
        </div>
        <div class="glass-card p-6 border-white/5 rounded-2xl">
            <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Active Bot Filters</div>
            <div class="text-3xl font-black text-blue-500">24</div>
        </div>
        <div class="glass-card p-6 border-white/5 rounded-2xl">
            <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Auto-Block Status</div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 breathe"></span>
                <span class="text-xs font-bold text-green-500 uppercase tracking-widest">Behavioral Enabled</span>
            </div>
        </div>
    </div>

    <!-- Manual IP Block -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="glass-card p-6 border-white/5 rounded-2xl">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-4">Manual IP Block</h3>
                <form wire:submit.prevent="addToBlacklist" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">IP Address</label>
                        <input type="text" wire:model="newBlacklistIp" placeholder="e.g. 1.2.3.4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-red-500/50 outline-none transition-all">
                        @error('newBlacklistIp') <span class="text-[10px] text-red-500 font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Reason</label>
                        <input type="text" wire:model="newBlacklistReason" placeholder="Crawler/Bot activity" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-red-500/50 outline-none transition-all">
                        @error('newBlacklistReason') <span class="text-[10px] text-red-500 font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-red-500/30 transition-all">
                        Block IP
                    </button>
                </form>
            </div>
        </div>

        <!-- Blacklist Table -->
        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
                <div class="p-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-widest">Active Blacklist</h3>
                    <input type="text" wire:model.live="searchBlacklist" placeholder="Search IP or reason..." class="bg-white/5 border border-white/10 rounded-lg px-4 py-1.5 text-[10px] focus:outline-none focus:border-white/20 transition-all">
                </div>
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4">Reason</th>
                            <th class="px-6 py-4">Blocked At</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($blacklistData as $entry)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 font-mono font-bold text-gray-300">{{ $entry->ip }}</td>
                            <td class="px-6 py-4 text-gray-500 italic">{{ $entry->reason ?? 'No reason provided' }}</td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-[10px]">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="removeFromBlacklist({{ $entry->id }})" class="w-8 h-8 rounded-lg glass border-white/10 text-gray-400 hover:bg-green-500/20 hover:text-green-500 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center opacity-30 uppercase font-black text-[10px] tracking-widest">
                                The wall is empty. No threats detected.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($blacklistData && $blacklistData->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $blacklistData->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
