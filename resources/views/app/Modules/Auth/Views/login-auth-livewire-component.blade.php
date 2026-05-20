<div class="min-h-screen flex items-center justify-center px-6 relative overflow-hidden bg-[#050505]">
    <!-- Background Decoration -->
    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none">
        <div class="absolute top-[10%] left-[-10%] w-[60%] h-[60%] bg-blue-900/30 blur-[150px] rounded-full"></div>
        <div class="absolute bottom-[10%] right-[-10%] w-[60%] h-[60%] bg-purple-900/30 blur-[150px] rounded-full"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md glass-card p-10 relative z-10 border-white/10 shadow-[0_0_80px_rgba(37,99,235,0.15)] transform transition-all">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl mx-auto flex items-center justify-center shadow-2xl shadow-blue-500/50 mb-6">
                <span class="text-white font-black text-3xl">H</span>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-white">Vault Access</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium uppercase tracking-widest">Secure Authentication</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Identity</label>
                <div class="relative">
                    <input type="email" wire:model="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-blue-500/50 transition-all placeholder-gray-700" placeholder="Enter your email">
                </div>
                @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase tracking-tight">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between ml-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Passkey</label>
                    <a href="#" class="text-[9px] font-black text-blue-500 uppercase hover:text-blue-400 transition-colors">Recovery?</a>
                </div>
                <div class="relative">
                    <input type="password" wire:model="password" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-blue-500/50 transition-all placeholder-gray-700" placeholder="••••••••">
                </div>
                @error('password') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase tracking-tight">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3 ml-1">
                <input type="checkbox" wire:model="remember" id="remember" class="w-4 h-4 bg-white/5 border-white/10 rounded text-blue-600 focus:ring-0 cursor-pointer">
                <label for="remember" class="text-[10px] text-gray-500 font-black uppercase cursor-pointer tracking-wider">Persist Session</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest py-5 rounded-xl shadow-2xl shadow-blue-500/40 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                <span>Unlock Vault</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                New Distributor? 
                <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-400 transition-colors ml-1">Initialize Account</a>
            </p>
        </div>
    </div>
</div>
