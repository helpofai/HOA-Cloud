<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- System Health -->
    <div class="lg:col-span-2 space-y-8">
        <x-admin.card 
            title="Shared Hosting Optimization" 
            subtitle="System health and environment verification"
            variant="green"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </x-slot:icon>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.status-card label="PHP Version" :status="$systemInfo['is_php_supported'] ? 'active' : 'error'">
                        {{ $systemInfo['php_version'] }} (Required: 8.3+)
                    </x-admin.status-card>
                    <x-admin.status-card label="Symlink Support" :status="$canUseSymlinks ? 'active' : 'warning'">
                        {{ $canUseSymlinks ? 'Supported' : 'Disabled by Host' }}
                    </x-admin.status-card>
                </div>

                <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                    <h4 class="text-[10px] font-black uppercase text-gray-500 mb-4 tracking-widest">Directory Mappings</h4>
                    <div class="space-y-4">
                        <x-admin.input label="Public Path" :value="$dirMapping['public_path']" readonly />
                        <x-admin.input label="Storage Linked" :value="$dirMapping['storage_linked'] ? 'Link Active' : 'Link Broken'" readonly />
                    </div>
                </div>

                @if($optimizationSuggestions)
                <div class="space-y-3">
                    <h4 class="text-[10px] font-black uppercase text-orange-500 ml-1 tracking-widest">Recommended Actions</h4>
                    @foreach($optimizationSuggestions as $suggestion)
                    <div class="p-4 glass rounded-xl border-orange-500/10 flex items-start gap-3">
                        <div class="mt-0.5 text-orange-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white">{{ $suggestion['title'] }}</div>
                            <div class="text-[10px] text-gray-500 leading-relaxed">{{ $suggestion['message'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </x-admin.card>
    </div>

    <!-- Utilities -->
    <div class="space-y-8">
        <x-admin.card title="Maintenance Utilities" variant="orange">
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </x-slot:icon>

            <div class="space-y-3">
                <button wire:click="repairStorageLink" class="w-full py-4 glass rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600/10 transition-all flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Repair Storage Symlink
                </button>
                <button class="w-full py-4 glass rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all opacity-40 grayscale cursor-not-allowed">
                    Optimize public_html Mapping
                </button>
            </div>
        </x-admin.card>

        <div class="glass-card p-6 border-blue-500/10">
            <h4 class="text-[10px] font-black uppercase text-blue-400 mb-3 tracking-widest">Environment Info</h4>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-[10px]">
                    <span class="text-gray-500">Max Upload Size</span>
                    <span class="text-white font-mono">{{ $systemInfo['upload_max_filesize'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="text-gray-500">Memory Limit</span>
                    <span class="text-white font-mono">{{ $systemInfo['memory_limit'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="text-gray-500">Execution Timeout</span>
                    <span class="text-white font-mono">{{ $systemInfo['max_execution_time'] }}s</span>
                </div>
            </div>
        </div>
    </div>
</div>
