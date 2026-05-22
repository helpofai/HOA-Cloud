<div class="max-w-4xl space-y-12">
    <div>
        <h1 class="text-3xl font-black tracking-tight mb-2 uppercase">Security & <span class="text-blue-500">Forensics</span></h1>
        <p class="text-gray-500 text-sm">Configure advanced protection for your shared content</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Watermark Control -->
        <div class="glass-card p-8 border-white/5 space-y-6 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-600/10 rounded-2xl flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                @if($watermarkEnabled)
                <span class="px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full text-[9px] font-black text-green-500 uppercase tracking-widest">Active</span>
                @else
                <span class="px-3 py-1 bg-gray-500/10 border border-white/10 rounded-full text-[9px] font-black text-gray-500 uppercase tracking-widest">Disabled</span>
                @endif
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2">Forensic Watermarking</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Inject your User IP and ID into shared streams. This helps you track down the source of any leaks if your content is ripped.
                </p>
            </div>

            <div class="pt-6 border-t border-white/5 space-y-4">
                @if($allowWatermarkToggle)
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-300">Inject Watermark</span>
                    <button wire:click="$toggle('watermarkEnabled')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $watermarkEnabled ? 'bg-blue-600' : 'bg-white/10' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $watermarkEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
                @else
                <div class="p-4 bg-orange-500/5 border border-orange-500/20 rounded-xl">
                    <p class="text-[10px] text-orange-400 font-bold uppercase tracking-tight">System Enforcement Active</p>
                    <p class="text-[10px] text-gray-500 mt-1">The administrator requires watermarking for your account level.</p>
                </div>
                @endif

                <button wire:click="saveSecuritySettings" class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    Update Security Policy
                </button>
            </div>
        </div>

        <!-- Leak Tracking Info -->
        <div class="glass-card p-8 border-white/5 space-y-6">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Forensic Identity</h3>
            <div class="p-6 bg-[#050505] rounded-2xl border border-white/5 font-mono text-[10px] space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">IDENTITY_HASH:</span>
                    <span class="text-blue-500">{{ substr(md5(auth()->id() . 'SALT'), 0, 12) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">NETWORK_ID:</span>
                    <span class="text-gray-300">{{ request()->ip() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">STATUS:</span>
                    <span class="text-green-500">PROTECTED</span>
                </div>
            </div>
            <p class="text-[10px] text-gray-500 leading-relaxed italic">
                Our forensic engine uses evasive JS challenges to ensure the watermark remains visible even if the video element is modified.
            </p>
        </div>
    </div>
</div>
