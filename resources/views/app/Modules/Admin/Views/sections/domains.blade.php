<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">Ghost Hop Domains</h2>
            <p class="text-xs text-gray-500">Manage the Multi-Domain Hydra Architecture for evasive redirection.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Multi-Domain Architecture</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="multiDomainEnabled" wire:change="saveDomainSettings" class="sr-only peer">
                <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
            </label>
        </div>
    </div>

    <!-- Info Card -->
    <div class="p-6 glass-card border-blue-500/20 bg-blue-600/5 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-600/20 flex items-center justify-center flex-shrink-0 text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-blue-400 mb-1 uppercase tracking-widest">How Hydra Architecture Works</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    When enabled, the 5-Layer "Ghost Hop" redirection system will automatically cycle through the Active Redirect Nodes listed below. 
                    This ensures that your main domain (<code>{{ config('app.url') }}</code>) is never directly exposed as the source of shared files, protecting it from DMCA crawlers. 
                    If a node gets flagged, simply delete it and add a new disposable domain.
                </p>
                <div class="mt-4 flex gap-2">
                    <span class="px-2 py-1 glass bg-white/5 rounded text-[10px] font-mono text-gray-300">Layer 0: Node Rotation</span>
                    <span class="px-2 py-1 glass bg-white/5 rounded text-[10px] font-mono text-gray-300">Layer 1: Entry Gate</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Node Management -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Add Node Form -->
        <div class="lg:col-span-1">
            <div class="glass-card p-6 border-white/5 rounded-2xl">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-4">Add Redirect Node</h3>
                <form wire:submit.prevent="addNode" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Domain URL</label>
                        <input type="url" wire:model="newNodeDomain" placeholder="https://x-jump.top" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500/50 outline-none transition-all">
                        @error('newNodeDomain') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-500/30 transition-all">
                        Deploy Node
                    </button>
                </form>
            </div>
        </div>

        <!-- Node List -->
        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                            <th class="px-6 py-4">Node Domain</th>
                            <th class="px-6 py-4 text-center">Type</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($nodes as $node)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    </div>
                                    <div class="text-sm font-bold font-mono">{{ $node->domain }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 glass bg-white/5 rounded text-[9px] font-black uppercase tracking-widest text-gray-300">
                                    {{ $node->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 glass rounded text-[9px] font-black uppercase tracking-widest {{ $node->status === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                    {{ $node->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="deleteNode({{ $node->id }})" wire:confirm="Are you sure you want to remove this node?" class="w-8 h-8 inline-flex items-center justify-center rounded-lg glass border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center opacity-50">
                                <div class="text-xs font-bold uppercase tracking-widest mb-1">No Active Nodes</div>
                                <div class="text-[10px] text-gray-500">System will use the built-in domain.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
