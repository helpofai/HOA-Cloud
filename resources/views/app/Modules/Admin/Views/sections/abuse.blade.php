<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">Abuse & Legal Reports</h2>
            <p class="text-xs text-gray-500">Manage DMCA and abuse reports. Act on reported links and files.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <input 
                type="text" 
                wire:model.live="searchAbuse" 
                placeholder="Search reports..." 
                class="w-64 glass bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none transition-all"
            >
        </div>
    </div>

    <!-- Abuse System Policy -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <x-admin.card 
            title="Reporting Policy" 
            subtitle="Configure automated compliance triggers"
            variant="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.233-2.047-.652-2.956z"/></svg>
            </x-slot:icon>

            <form wire:submit.prevent="saveAbuseSettings" class="space-y-6">
                <x-admin.toggle 
                    label="Enable Public Reporting" 
                    model="abuseSystemEnabled" 
                    description="Allow users/guests to submit abuse reports via the public gateway."
                />

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Auto-Kill Threshold</label>
                    <input type="number" wire:model="abuseAutoKillThreshold" class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-blue-500/50 transition-all outline-none">
                    <p class="text-[9px] text-gray-500 font-bold italic">Automatically disable links after X unique reports.</p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 bg-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                        Save Policy
                    </button>
                </div>
            </form>
        </x-admin.card>

        <div class="glass-card p-8 border-orange-500/10 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-widest">Compliance Hub</h3>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed italic">
                A formal abuse system helps the platform look like a compliant, legitimate business to hosting providers and ISPs. 
                It allows you to moderate content before third-party interventions occur.
            </p>
        </div>
    </div>

    <!-- Active Reports Table -->
    <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
        <div class="p-4 border-b border-white/5 bg-white/5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Pending Investigation</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">Reported Content</th>
                    <th class="px-6 py-4">Reason & Details</th>
                    <th class="px-6 py-4">Reporter IP</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($abuseReportsData as $report)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-600/10 border border-orange-500/20 flex items-center justify-center text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-300">
                                    @if($report->file)
                                        {{ $report->file->name }}
                                    @else
                                        Unknown Media
                                    @endif
                                </div>
                                <a href="{{ $report->reported_url }}" target="_blank" class="text-[9px] text-blue-400 hover:underline font-mono mt-1 block truncate max-w-[200px]">{{ $report->reported_url }}</a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-1">{{ $report->reason }}</div>
                        <div class="text-[10px] text-gray-500 italic max-w-xs">{{ Str::limit($report->details, 100) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[10px] font-mono text-gray-500">{{ $report->reporter_ip }}</div>
                        <div class="text-[9px] text-gray-600">{{ $report->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($report->share_id && $report->share && $report->share->is_active)
                            <button 
                                wire:click="killShare({{ $report->share_id }})"
                                class="px-3 py-1.5 glass bg-red-600/10 border-red-500/20 text-red-500 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all"
                                title="Kill this specific link only"
                            >
                                Kill Link
                            </button>
                            @endif

                            @if($report->file_id && $report->file && !$report->file->is_killed)
                            <button 
                                wire:click="killFile('{{ $report->file->uuid }}')"
                                class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-500/20 transition-all"
                                title="Kill the file system-wide"
                            >
                                Kill File
                            </button>
                            @endif

                            <button 
                                wire:click="dismissAbuse({{ $report->id }})"
                                class="px-3 py-1.5 glass bg-white/5 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all"
                            >
                                Dismiss
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center opacity-30">
                        <div class="text-xs font-black uppercase tracking-widest">No pending abuse reports</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($abuseReportsData->hasPages())
        <div class="px-6 py-4 border-t border-white/5">
            {{ $abuseReportsData->links() }}
        </div>
        @endif
    </div>
</div>
