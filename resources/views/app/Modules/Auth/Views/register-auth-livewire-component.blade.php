<div class="min-h-screen flex items-center justify-center px-6 relative overflow-hidden bg-[#050505]">
    <!-- Background Decoration -->
    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none">
        <div class="absolute top-[10%] right-[-10%] w-[60%] h-[60%] bg-purple-900/30 blur-[150px] rounded-full"></div>
        <div class="absolute bottom-[10%] left-[-10%] w-[60%] h-[60%] bg-blue-900/30 blur-[150px] rounded-full"></div>
    </div>

    <!-- Register Card -->
    <div class="w-full max-w-md glass-card p-10 relative z-10 border-white/10 shadow-[0_0_80px_rgba(168,85,247,0.15)] transform transition-all">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-purple-600 rounded-2xl mx-auto flex items-center justify-center shadow-2xl shadow-purple-500/50 mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-white">Join Network</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium uppercase tracking-widest">Initialize Node Access</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-6">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Distributor Name</label>
                <input type="text" wire:model="name" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-purple-500/50 transition-all placeholder-gray-700" placeholder="e.g. John Doe">
                @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase tracking-tight">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Communication Channel</label>
                <input type="email" wire:model="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-purple-500/50 transition-all placeholder-gray-700" placeholder="name@example.com">
                @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase tracking-tight">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Passkey</label>
                    <input type="password" wire:model="password" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-purple-500/50 transition-all placeholder-gray-700" placeholder="••••••••">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Confirm</label>
                    <input type="password" wire:model="password_confirmation" class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-sm focus:border-purple-500/50 transition-all placeholder-gray-700" placeholder="••••••••">
                </div>
                @error('password') <div class="col-span-2 text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tight">{{ $message }}</div> @enderror
            </div>

            <p class="text-[9px] text-gray-600 font-bold leading-relaxed uppercase tracking-widest text-center px-4">
                By deploying, you agree to our 
                <span class="text-purple-500">Security Protocols</span> and 
                <span class="text-purple-500">Evasive Standards</span>.
            </p>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black text-sm uppercase tracking-widest py-5 rounded-xl shadow-2xl shadow-purple-500/40 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                <span>Deploy Account</span>
                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                Already registered? 
                <a href="{{ route('login') }}" class="text-purple-500 hover:text-purple-400 transition-colors ml-1">Establish Link</a>
            </p>
        </div>
    </div>
</div>
