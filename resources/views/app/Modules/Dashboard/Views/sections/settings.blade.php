<div class="max-w-4xl space-y-12">
    <div>
        <h1 class="text-3xl font-black tracking-tight mb-2 uppercase">Account <span class="text-blue-500">Settings</span></h1>
        <p class="text-gray-500 text-sm">Manage your personal preferences and account security</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Appearance & Experience -->
        <div class="glass-card p-8 border-white/5 space-y-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Appearance</h3>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-bold text-gray-200">System Theme</div>
                        <div class="text-[10px] text-gray-500">Switch between dark and obsidian modes</div>
                    </div>
                    <button wire:click="toggleTheme" class="px-4 py-2 glass rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all">
                        {{ $theme === 'dark' ? 'Switch to Light' : 'Switch to Dark' }}
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-bold text-gray-200">Language</div>
                        <div class="text-[10px] text-gray-500">Global interface language</div>
                    </div>
                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">English (US)</span>
                </div>
            </div>
        </div>

        <!-- Role & Quota Status -->
        <div class="glass-card p-8 border-blue-600/10 bg-blue-600/5 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-blue-400">Subscription Status</h3>
            
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/20 flex items-center justify-center text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.233-2.047-.652-2.956z"/></svg>
                </div>
                <div>
                    <div class="text-lg font-bold text-white uppercase">{{ auth()->user()->role->label() }}</div>
                    <div class="text-[10px] text-blue-400 font-bold uppercase tracking-tight">Active Plan</div>
                </div>
            </div>

            <div class="pt-4 border-t border-blue-500/10">
                <div class="flex justify-between text-[10px] font-bold text-gray-400 mb-2">
                    <span>STORAGE QUOTA</span>
                    <span>{{ Number::fileSize(auth()->user()->quota_used) }} / {{ Number::fileSize(auth()->user()->quota_limit) }}</span>
                </div>
                <div class="w-full h-1.5 bg-blue-900/30 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500" style="width: {{ (auth()->user()->quota_used / auth()->user()->quota_limit) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information (Static for now) -->
    <div class="glass-card p-10 border-white/5 space-y-10">
        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Security & Profile</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Full Name</label>
                <div class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm text-gray-400 font-bold">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Email Address</label>
                <div class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm text-gray-400 font-bold">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wide leading-relaxed max-w-sm">
                To update your email or password, please contact the system administrator. 
                HOA Cloud uses hardware-bound sessions for your protection.
            </p>
            <div class="flex items-center gap-4">
                <button class="px-8 py-3 glass rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 opacity-50 cursor-not-allowed">Change Password</button>
                <button wire:click="setSection('security')" class="px-8 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-blue-500 hover:bg-white/10 transition-all">Audit Security</button>
            </div>
        </div>
    </div>
</div>
