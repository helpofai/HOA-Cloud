<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">Global Kill Switch</h2>
            <p class="text-xs text-gray-500">Instant link revocation engine. Stop viral or compromised links immediately.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative group">
                <input 
                    type="text" 
                    wire:model.live="searchKilled" 
                    placeholder="Search killed files..." 
                    class="w-64 glass bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-red-500 outline-none transition-all"
                >
            </div>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="p-4 bg-red-600/10 border border-red-500/20 rounded-2xl flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-600/20 flex items-center justify-center flex-shrink-0 text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-red-500 mb-1">Active Revocation Status</h3>
            <p class="text-[10px] text-gray-400 leading-relaxed">
                When a file is "Killed", its unique verification hash is blacklisted system-wide. Any active stream tokens are instantly invalidated, and all future Layer 1 entry attempts will result in a hard access denial.
            </p>
        </div>
    </div>

    <!-- Killed Files Table -->
    <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
        <div class="p-4 border-b border-white/5 bg-white/5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Restricted Content Registry</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">Media</th>
                    <th class="px-6 py-4">Owner</th>
                    <th class="px-6 py-4">Revocation Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($killedFilesData as $file)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-600/10 border border-red-500/20 flex items-center justify-center text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-300">{{ $file->name }}</div>
                                <div class="text-[9px] text-gray-500 font-mono mt-1 uppercase">{{ $file->uuid }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-gray-400">{{ $file->user->name ?? 'System' }}</div>
                        <div class="text-[10px] text-gray-600">{{ $file->user->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-[10px] font-mono text-gray-500">
                        {{ $file->updated_at->format('Y-m-d H:i:s') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button 
                            wire:click="reviveFile('{{ $file->uuid }}')"
                            wire:confirm="Restore this file? Shared links will become functional again immediately."
                            class="px-3 py-1.5 glass bg-green-500/10 border-green-500/20 text-green-500 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-green-500 hover:text-white transition-all opacity-0 group-hover:opacity-100"
                        >
                            Restore Access
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center opacity-30">
                        <div class="text-xs font-black uppercase tracking-widest">No restricted files found</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($killedFilesData->hasPages())
        <div class="px-6 py-4 border-t border-white/5">
            {{ $killedFilesData->links() }}
        </div>
        @endif
    </div>
</div>
