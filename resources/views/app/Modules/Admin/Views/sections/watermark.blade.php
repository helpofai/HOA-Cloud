<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Watermark Configuration -->
    <div class="lg:col-span-2 space-y-8">
        <x-admin.card 
            title="Forensic Watermarking" 
            subtitle="Configure server-side dynamic overlay injection"
            variant="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </x-slot:icon>
            
            <x-slot:headerAction>
                <div class="flex items-center gap-2 px-3 py-1 glass rounded-full">
                    <span class="w-2 h-2 rounded-full {{ $watermarkEnabled ? 'bg-green-500 breathe' : 'bg-gray-500' }}"></span>
                    <span class="text-[9px] font-black text-gray-400 uppercase">{{ $watermarkEnabled ? 'Active' : 'Disabled' }}</span>
                </div>
            </x-slot:headerAction>

            <form wire:submit.prevent="saveWatermarkSettings" class="space-y-8">
                <!-- Global Toggle -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                    <x-admin.toggle 
                        label="Enable Global Watermarking" 
                        model="watermarkEnabled" 
                        description="Automatically injects User IP and ID into all media streams for leak tracking."
                    />
                </div>

                <!-- Appearance settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/5 space-y-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Overlay Opacity</label>
                        <input type="range" wire:model="watermarkOpacity" min="0.05" max="0.5" step="0.05" class="w-full accent-blue-600">
                        <div class="flex justify-between text-[10px] text-gray-500 font-bold">
                            <span>Faint (0.05)</span>
                            <span class="text-blue-400">{{ $watermarkOpacity }}</span>
                            <span>Visible (0.5)</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/5 border border-white/5 space-y-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Drift Speed</label>
                        <select wire:model="watermarkSpeed" class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-blue-500/50 transition-all outline-none">
                            <option value="slow">Stealth (Slow)</option>
                            <option value="medium">Standard (Medium)</option>
                            <option value="fast">Aggressive (Fast)</option>
                        </select>
                    </div>
                </div>

                <!-- User Control Toggle -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                    <x-admin.toggle 
                        label="Allow User Override" 
                        model="watermarkUserControl" 
                        description="Allow users to disable watermarking on their own share links (Premium feature)."
                        variant="orange"
                    />
                </div>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-[9px] text-gray-500 max-w-xs uppercase font-bold leading-relaxed">
                        Forensic watermarking uses client-side injection with server-side signature validation. 
                        Tampering with the watermark triggers a session kill.
                    </p>
                    <button type="submit" class="px-10 py-4 bg-blue-600 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-105 active:scale-95">
                        Apply Policy
                    </button>
                </div>
            </form>
        </x-admin.card>

        <!-- Preview Card -->
        <div class="glass-card p-8 border-white/5 relative overflow-hidden group">
            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6">Real-time Simulation</h3>
            <div class="aspect-video bg-black rounded-2xl relative overflow-hidden border border-white/5">
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
                
                <!-- Watermark Preview -->
                @if($watermarkEnabled)
                <div class="absolute font-black text-white pointer-events-none uppercase tracking-widest whitespace-nowrap" 
                     style="font-size: 8px; opacity: {{ $watermarkOpacity }}; animation: float-{{ $watermarkSpeed }} 10s infinite linear;">
                    HASH: XXXXXXXX | 192.168.1.1 | HOA CLOUD SECURE
                </div>
                @endif
            </div>

            <style>
                @keyframes float-slow { 0%, 100% { transform: translate(10%, 10%); } 50% { transform: translate(70%, 80%); } }
                @keyframes float-medium { 0%, 100% { transform: translate(5%, 5%); } 25% { transform: translate(80%, 10%); } 50% { transform: translate(10%, 80%); } 75% { transform: translate(80%, 80%); } }
                @keyframes float-fast { 0% { transform: translate(0,0); } 20% { transform: translate(80%, 20%); } 40% { transform: translate(20%, 80%); } 60% { transform: translate(80%, 80%); } 80% { transform: translate(20%, 20%); } 100% { transform: translate(0,0); } }
            </style>
        </div>
    </div>

    <!-- Forensic Logic Info -->
    <div class="space-y-8">
        <x-admin.card 
            title="Leak Identification" 
            variant="red"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </x-slot:icon>
            
            <div class="space-y-6 text-[11px] text-gray-400 leading-relaxed font-medium">
                <p>The watermark contains a cryptographic hash derived from:</p>
                <ul class="list-disc pl-5 space-y-2 text-gray-500">
                    <li><span class="text-red-400">User IP Address</span> (At time of stream)</li>
                    <li><span class="text-red-400">User ID</span> (If logged in)</li>
                    <li><span class="text-red-400">Session ID</span> (Evasive rotation)</li>
                    <li><span class="text-red-400">Timestamp</span> (Encrypted)</li>
                </ul>
                <p class="pt-4 border-t border-white/5">
                    If a recording or screenshot is found online, use the <span class="text-white">Forensic Decoder</span> (Internal Tool) to recover the source user identity instantly.
                </p>
            </div>
        </x-admin.card>

        <div class="glass-card p-8 border-orange-500/10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-sm font-bold uppercase tracking-tight text-orange-400">Legal Compliance</h4>
            </div>
            <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                Watermarking is mandatory for "Guest" access nodes to deter copyright infringement. Disabling it for all users may increase the risk of DMCA takedowns.
            </p>
        </div>
    </div>
</div>
