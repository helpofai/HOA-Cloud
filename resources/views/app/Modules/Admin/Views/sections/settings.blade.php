<div class="space-y-8">
    <div>
        <h2 class="text-xl font-bold uppercase tracking-tight">System Configuration</h2>
        <p class="text-xs text-gray-500">Global toggles and core platform behavior</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Security & Evasion Toggles -->
        <x-admin.card 
            title="Security & Evasion" 
            subtitle="Master switches for anti-tracking systems"
            variant="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.233-2.047-.652-2.956z"/></svg>
            </x-slot:icon>

            <div class="space-y-6">
                <x-admin.toggle 
                    label="Ghost Hop Redirection" 
                    model="hydraEnabled" 
                    description="Enable 5-layer evasive redirection system for all share links."
                />

                <x-admin.toggle 
                    label="Anti-Bot Firewall" 
                    model="antiBotEnabled" 
                    description="Block known crawlers and suspicious IP ranges from entering the gateway."
                />

                <x-admin.toggle 
                    label="Forensic Watermarking" 
                    model="watermarkEnabled" 
                    description="Inject identifying data into all media streams globally."
                />
                
                <div class="flex justify-end pt-4">
                    <button wire:click="saveSecuritySettings" class="px-8 py-3 bg-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                        Update Evasion Policy
                    </button>
                </div>
            </div>
        </x-admin.card>

        <!-- Compliance & Performance -->
        <x-admin.card 
            title="Compliance & Limits" 
            subtitle="Global limits and automated moderation"
            variant="orange"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </x-slot:icon>

            <div class="space-y-6">
                <x-admin.toggle 
                    label="Abuse Reporting Gateway" 
                    model="abuseSystemEnabled" 
                    description="Allow public reporting and enable the automated DMCA gateway."
                />

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Global Throttling</label>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-xl flex items-center justify-between">
                        <span class="text-xs text-gray-400">Current Base Speed: <span class="text-orange-500 font-bold">{{ $defaultStreamSpeed }} KB/s</span></span>
                        <button wire:click="setSection('media-engine')" class="text-[9px] text-blue-400 hover:underline uppercase font-bold">Adjust in Media Center</button>
                    </div>
                </div>

                <div class="p-4 bg-orange-600/5 border border-orange-500/10 rounded-xl">
                    <p class="text-[9px] text-gray-500 italic font-bold leading-relaxed uppercase">
                        Configuration changes are written to the persistent system layer. 
                        Some changes may require 60 seconds to propagate to edge nodes.
                    </p>
                </div>

                <div class="flex justify-end pt-4">
                    <button wire:click="saveAbuseSettings" class="px-8 py-3 bg-orange-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all">
                        Update Compliance Policy
                    </button>
                </div>
            </div>
        </x-admin.card>
    </div>

    <!-- Binary Paths & Versions -->
    <div class="glass-card p-8 border-white/5 space-y-6">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Environment & Binaries</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <span class="text-[10px] text-gray-600 font-black uppercase tracking-widest block">PHP Engine</span>
                <span class="text-sm font-bold text-gray-300">{{ PHP_VERSION }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] text-gray-600 font-black uppercase tracking-widest block">Binary Hub</span>
                <span class="text-sm font-bold text-gray-300">/bin/win/ffmpeg.exe</span>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] text-gray-600 font-black uppercase tracking-widest block">App Version</span>
                <span class="text-sm font-bold text-blue-500">v2.1.0-STABLE</span>
            </div>
        </div>
    </div>
</div>
