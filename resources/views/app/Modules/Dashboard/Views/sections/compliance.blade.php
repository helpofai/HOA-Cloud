<div class="max-w-4xl space-y-12">
    <div>
        <h1 class="text-3xl font-black tracking-tight mb-2 uppercase">Compliance <span class="text-orange-500">& Abuse</span></h1>
        <p class="text-gray-500 text-sm">Monitor your account standing and manage legal reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Status Card -->
        <div class="glass-card p-8 border-white/5 space-y-4">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Account Standing</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-lg font-bold text-white uppercase">Good</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">No active violations</div>
                </div>
            </div>
        </div>

        <!-- Reports Card -->
        <div class="glass-card p-8 border-white/5 space-y-4">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Total Reports</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="text-lg font-bold text-white">{{ \App\Modules\Security\Models\AbuseReport::whereHas('file', fn($q) => $q->where('user_id', auth()->id()))->count() }}</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">All-time reports</div>
                </div>
            </div>
        </div>

        <!-- Takedowns Card -->
        <div class="glass-card p-8 border-white/5 space-y-4">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500">Active Takedowns</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-red-600/10 flex items-center justify-center text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <div class="text-lg font-bold text-white">{{ auth()->user()->files()->where('is_killed', true)->count() }}</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Blocked files</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports for User -->
    <div class="glass-card border-white/5 overflow-hidden rounded-2xl">
        <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Recent Content Reports</h3>
            <span class="text-[9px] text-gray-500 uppercase font-bold">Showing last 10 entries</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] font-black uppercase tracking-widest text-gray-600 bg-white/5">
                        <th class="px-8 py-4">File / Link</th>
                        <th class="px-8 py-4">Reason</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @php
                        $userReports = \App\Modules\Security\Models\AbuseReport::with(['file', 'share'])
                            ->whereHas('file', fn($q) => $q->where('user_id', auth()->id()))
                            ->latest()
                            ->take(10)
                            ->get();
                    @endphp
                    @forelse($userReports as $report)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-8 py-4">
                            <div class="text-xs font-bold text-gray-300">{{ $report->file?->name ?? 'Deleted Media' }}</div>
                            <div class="text-[9px] text-blue-500 font-mono mt-1">{{ Str::limit($report->reported_url, 30) }}</div>
                        </td>
                        <td class="px-8 py-4 uppercase text-[10px] font-black text-orange-500">{{ $report->reason }}</td>
                        <td class="px-8 py-4">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest {{ $report->status === 'pending' ? 'bg-blue-600/10 text-blue-400 border border-blue-500/20' : 'bg-green-600/10 text-green-400 border border-green-500/20' }}">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-[10px] text-gray-500 font-mono">{{ $report->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center opacity-30 text-[10px] font-black uppercase tracking-widest">No reports found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-8 glass-card border-blue-600/10 bg-blue-600/5">
        <div class="flex items-center gap-4">
            <div class="text-blue-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-tight text-blue-400">Legal Transparency</h4>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Our reporting system is designed to protect both creators and the platform. If you believe a report is 
                    fraudulent, please contact our support team with proof of ownership.
                </p>
            </div>
        </div>
    </div>
</div>
