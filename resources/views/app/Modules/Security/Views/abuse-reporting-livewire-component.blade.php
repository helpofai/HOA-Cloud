<div class="min-h-screen bg-[#050505] flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Evasive Mesh Background -->
    <div class="absolute top-0 left-0 w-full h-full z-0 opacity-20">
        <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] bg-blue-900/30 blur-[150px] rounded-full"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-red-900/20 blur-[150px] rounded-full"></div>
    </div>

    <div class="w-full max-w-2xl relative z-10">
        <div class="text-center mb-12">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-500/40 mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h1 class="text-4xl font-black text-white tracking-tighter uppercase mb-2">Compliance <span class="text-blue-500">& Abuse</span> Gateway</h1>
            <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Secure Content Reporting System</p>
        </div>

        @if($submitted)
        <div class="glass-card p-12 text-center border-green-500/20 shadow-[0_0_50px_rgba(34,197,94,0.1)]">
            <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center text-green-500 mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Report Received</h2>
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                Your report has been logged and assigned to our legal review team. We take all claims of copyright 
                infringement and policy violations seriously. Action will be taken within 24-48 hours.
            </p>
            <a href="/" class="inline-block px-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all">
                Return to Home
            </a>
        </div>
        @else
        <div class="glass-card p-10 border-white/5 shadow-2xl relative overflow-hidden group">
            <form wire:submit.prevent="submit" class="space-y-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reported URL</label>
                    <input 
                        type="url" 
                        wire:model="reportedUrl" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm focus:border-blue-500/50 transition-all outline-none text-white font-mono"
                        placeholder="https://hoacloud.xyz/v/..."
                    >
                    @error('reportedUrl') <span class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reason for Report</label>
                        <select 
                            wire:model="reason" 
                            class="w-full bg-[#0a0a0a] border border-white/10 rounded-2xl px-6 py-4 text-sm focus:border-blue-500/50 transition-all outline-none text-white"
                        >
                            <option value="">Select a reason...</option>
                            <option value="copyright">Copyright Infringement (DMCA)</option>
                            <option value="abuse">Harassment or Abuse</option>
                            <option value="malware">Malware / Phishing</option>
                            <option value="other">Other Violation</option>
                        </select>
                        @error('reason') <span class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-600/5 border border-blue-500/10 flex items-center gap-4">
                        <div class="text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[9px] text-gray-500 leading-relaxed font-medium">
                            Your IP address <span class="text-blue-400 font-bold">({{ request()->ip() }})</span> is logged with this report to prevent fraudulent claims.
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Additional Details</label>
                    <textarea 
                        wire:model="details" 
                        rows="4" 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm focus:border-blue-500/50 transition-all outline-none text-white resize-none"
                        placeholder="Provide more context, proof, or ownership details..."
                    ></textarea>
                    @error('details') <span class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-blue-600 rounded-2xl text-xs font-black uppercase tracking-[0.3em] shadow-xl shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-[1.02] active:scale-95 text-white">
                        Submit Legal Report
                    </button>
                    <p class="text-center mt-6 text-[9px] text-gray-600 font-bold uppercase tracking-widest">
                        By submitting, you affirm that the information provided is accurate under penalty of perjury.
                    </p>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
