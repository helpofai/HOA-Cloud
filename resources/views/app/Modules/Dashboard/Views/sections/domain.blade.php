<div class="space-y-8 max-w-4xl">
    <div>
        <h2 class="text-xl font-bold uppercase tracking-tight">Custom Branding</h2>
        <p class="text-xs text-gray-500">Professionalize your sharing links with your own custom domain.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Status Card -->
        <div class="glass-card p-8 border-white/5 rounded-2xl flex flex-col justify-between">
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4">Current Status</div>
                @if(auth()->user()->custom_domain)
                    <div class="text-lg font-bold font-mono text-blue-400 mb-2">{{ auth()->user()->custom_domain }}</div>
                    @if(auth()->user()->custom_domain_approved)
                        <div class="flex items-center gap-2 text-green-500">
                            <span class="w-2 h-2 bg-green-500 rounded-full breathe"></span>
                            <span class="text-xs font-bold uppercase tracking-widest">Active & Verified</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-orange-500">
                            <span class="w-2 h-2 bg-orange-500 rounded-full breathe"></span>
                            <span class="text-xs font-bold uppercase tracking-widest">Pending Approval</span>
                        </div>
                    @endif
                @else
                    <div class="text-lg font-bold text-gray-600 mb-2 italic">No Custom Domain Set</div>
                    <div class="text-xs text-gray-500 leading-relaxed">
                        Setting a custom domain masks the platform's origin and provides a premium experience for your viewers.
                    </div>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-white/5">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">DNS Setup Instructions</div>
                <p class="text-[10px] text-gray-400 leading-relaxed">
                    Point your domain's <strong>A Record</strong> to our server IP: <code class="text-blue-400">123.456.78.90</code> or use a <strong>CNAME</strong> pointing to <code class="text-blue-400">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</code>.
                </p>
            </div>
        </div>

        <!-- Request Form -->
        <div class="glass-card p-8 border-white/5 rounded-2xl">
            <h3 class="text-sm font-bold uppercase tracking-widest mb-6">Request New Domain</h3>
            <form wire:submit.prevent="requestCustomDomain" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Domain URL</label>
                    <input 
                        type="url" 
                        wire:model="customDomain" 
                        placeholder="https://your-brand.com" 
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500/50 outline-none transition-all"
                    >
                    @error('customDomain') <span class="text-[10px] text-red-500 font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <p class="text-[10px] text-yellow-500/80 leading-relaxed">
                            <strong>Important:</strong> Changing your domain will invalidate any previous approval. Our administrators will review your new request within 24-48 hours.
                        </p>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-500/30 transition-all transform active:scale-95">
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    <!-- FAQ -->
    <div class="glass-card p-8 border-white/5 rounded-2xl">
        <h3 class="text-xs font-bold uppercase tracking-widest mb-4">Why use a Custom Domain?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <div class="text-[11px] font-bold text-blue-400">100% Anti-Takedown</div>
                <p class="text-[10px] text-gray-500">If your custom domain is flagged, the main platform remains untouched. Just swap it for a new one.</p>
            </div>
            <div class="space-y-2">
                <div class="text-[11px] font-bold text-blue-400">Brand Authority</div>
                <p class="text-[10px] text-gray-500">Your users see your brand name in the address bar, increasing trust and engagement.</p>
            </div>
            <div class="space-y-2">
                <div class="text-[11px] font-bold text-blue-400">Social Previews</div>
                <p class="text-[10px] text-gray-500">Custom domains allow for unique OpenGraph tags, making your shared links look premium on Telegram & WhatsApp.</p>
            </div>
        </div>
    </div>
</div>
