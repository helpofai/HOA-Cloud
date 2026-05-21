<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold uppercase tracking-tight">Users & Quota</h2>
            <p class="text-xs text-gray-500">Manage user accounts, roles, and custom domain approvals.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="relative group">
                <input 
                    type="text" 
                    wire:model.live="searchUser" 
                    placeholder="Search by name or email..." 
                    class="w-64 glass bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                >
                <div class="absolute right-3 top-2.5 opacity-20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="glass-card overflow-hidden border-white/5 rounded-2xl">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black uppercase tracking-widest text-gray-500">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role & Quota</th>
                    <th class="px-6 py-4">Custom Domain</th>
                    <th class="px-6 py-4 text-center">Approval</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($usersData as $user)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl glass border-white/10 flex items-center justify-center font-bold text-gray-300 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff" alt="Avatar">
                            </div>
                            <div>
                                <div class="text-sm font-bold">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-1 glass bg-white/5 rounded text-[9px] font-black uppercase tracking-widest inline-block w-max
                                {{ $user->role->value === 'super-admin' ? 'text-red-500' : ($user->role->value === 'pro' ? 'text-yellow-500' : 'text-blue-500') }}">
                                {{ $user->role->label() }}
                            </span>
                            <div class="text-[10px] text-gray-400 font-mono mt-1 uppercase">{{ Number::fileSize($user->quota_limit) }} Max</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->custom_domain)
                            <div class="text-xs font-mono text-gray-300">{{ $user->custom_domain }}</div>
                            @if($user->custom_domain_approved)
                                <span class="text-[9px] text-green-500 font-black uppercase tracking-widest flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Active
                                </span>
                            @else
                                <span class="text-[9px] text-orange-500 font-black uppercase tracking-widest flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pending Review
                                </span>
                            @endif
                        @else
                            <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">Not Set</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->custom_domain)
                        <label class="relative inline-flex items-center cursor-pointer" title="Toggle Domain Approval">
                            <input type="checkbox" wire:click="toggleUserDomainApproval({{ $user->id }})" class="sr-only peer" {{ $user->custom_domain_approved ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        @else
                        <span class="text-gray-600">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="px-3 py-1.5 glass bg-white/5 hover:bg-white/10 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors opacity-0 group-hover:opacity-100">
                            Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center opacity-30">
                        <div class="text-xs font-black uppercase tracking-widest">No users found</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($usersData->hasPages())
        <div class="px-6 py-4 border-t border-white/5 bg-black/20">
            {{ $usersData->links() }}
        </div>
        @endif
    </div>
</div>
