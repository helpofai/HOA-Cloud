<div class="flex w-full h-full overflow-hidden">
    <style>
        .inner-shadow {
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.5);
        }
        .breathe {
            animation: breathe 3s ease-in-out infinite;
        }
        @keyframes breathe {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }
        .admin-sidebar { width: 280px; }
        .admin-content { width: calc(100% - 280px); }
    </style>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar h-full glass-dark border-r border-white/5 flex flex-col z-20">
        <div class="p-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/40">
                <span class="text-white font-black text-xl">A</span>
            </div>
            <div>
                <span class="text-lg font-bold tracking-tight block">HOA<span class="text-red-500">ADMIN</span></span>
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Root System</span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-4">
            <button wire:click="setSection('overview')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'overview' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span class="font-medium text-sm">Overview</span>
            </button>
            <button wire:click="setSection('users')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'users' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="font-medium text-sm">Users & Quota</span>
            </button>
            <button wire:click="setSection('files')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'files' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="font-medium text-sm">File Monitoring</span>
            </button>
            <button wire:click="setSection('domains')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'domains' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span class="font-medium text-sm">Ghost Hop Domains</span>
            </button>
            
            <div class="pt-6 pb-2 px-4 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Security</div>
            <button wire:click="setSection('abuse')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'abuse' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="font-medium text-sm">Abuse Reports</span>
            </button>
            <button wire:click="setSection('settings')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'settings' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="font-medium text-sm">System Config</span>
            </button>
        </nav>

        <div class="p-4 glass m-4 rounded-2xl border-red-500/20">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-gray-400">Master Server CPU</span>
                <span class="text-[10px] text-red-400">42%</span>
            </div>
            <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                <div class="h-full bg-red-600" style="width: 42%"></div>
            </div>
        </div>
    </aside>

    <!-- Admin Content -->
    <main class="admin-content h-full flex flex-col relative">
        <header class="h-16 flex items-center justify-between px-8 border-b border-white/5 glass-dark z-10">
            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-400">System Control Panel</h2>
            
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 breathe shadow-[0_0_10px_rgba(34,197,94,0.8)]"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">System Live</span>
                </div>
                <div class="w-px h-6 bg-white/5"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-xs font-bold">Admin Root</div>
                        <div class="text-[10px] text-red-500 font-bold uppercase">Super User</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl glass border-red-500/30 flex items-center justify-center font-bold text-red-500">
                        SR
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scroll p-8">
            <!-- Analytics Overview -->
            @if($section === 'overview')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card p-6 inner-shadow">
                    <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Total Users</div>
                    <div class="text-3xl font-black">12,482</div>
                    <div class="text-xs text-green-500 mt-2 font-bold">+12% this week</div>
                </div>
                <div class="glass-card p-6 inner-shadow">
                    <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Total Files</div>
                    <div class="text-3xl font-black">842,109</div>
                    <div class="text-xs text-red-500 mt-2 font-bold">4.2 TB storage</div>
                </div>
                <div class="glass-card p-6 inner-shadow">
                    <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Active Streams</div>
                    <div class="text-3xl font-black">1,842</div>
                    <div class="text-xs text-blue-500 mt-2 font-bold">Bandwidth: 450Mbps</div>
                </div>
                <div class="glass-card p-6 inner-shadow border-red-500/20">
                    <div class="text-gray-500 text-[10px] font-bold uppercase mb-1">Security Health</div>
                    <div class="text-3xl font-black text-green-500">EXCELLENT</div>
                    <div class="text-xs text-gray-500 mt-2">No active threats</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-card p-8 min-h-[300px] flex flex-col">
                    <h3 class="font-bold mb-6 flex items-center justify-between">
                        <span>Domain Traffic Distribution</span>
                        <span class="text-[10px] glass px-2 py-1 rounded text-gray-400">LIVE</span>
                    </h3>
                    <div class="flex-1 flex items-end gap-2 px-2">
                        @for($i=0; $i<20; $i++)
                        <div class="flex-1 bg-red-600/20 border-t border-red-500/40 rounded-t-sm" style="height: {{ rand(20, 90) }}%"></div>
                        @endfor
                    </div>
                    <div class="flex justify-between mt-4 text-[10px] text-gray-600 font-bold uppercase">
                        <span>Primary Node</span>
                        <span>Ghost Hop 04</span>
                        <span>Storage Node Alpha</span>
                    </div>
                </div>

                <div class="glass-card p-8 min-h-[300px]">
                    <h3 class="font-bold mb-6">Recent System Logs</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                            <span class="text-gray-400">New Pro User Signup: user_842</span>
                            <span class="text-gray-600">2 mins ago</span>
                        </div>
                        <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                            <span class="text-red-400 font-bold">Bot Detection: IP 182.xx.xx blocked</span>
                            <span class="text-gray-600">14 mins ago</span>
                        </div>
                        <div class="flex items-center justify-between text-xs border-b border-white/5 pb-2">
                            <span class="text-blue-400">Node Rotation: Switching to x-jump.top</span>
                            <span class="text-gray-600">1 hour ago</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($section === 'domains')
            <div class="glass-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold">Ghost Hop Domain Management</h2>
                        <p class="text-xs text-gray-500">Configure your multi-domain hydra architecture for evasive redirection.</p>
                    </div>
                    <button class="bg-red-600 px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-red-500/30">Add New Node</button>
                </div>

                <div class="space-y-4">
                    <div class="glass p-4 rounded-2xl flex items-center justify-between border-green-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 glass flex items-center justify-center rounded-xl text-green-500 font-bold">01</div>
                            <div>
                                <div class="font-bold">yourbrand.com</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Primary UI Node</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="glass px-3 py-1 rounded-full text-[10px] text-green-500 font-bold">ACTIVE</span>
                            <button class="text-gray-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="glass p-4 rounded-2xl flex items-center justify-between border-blue-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 glass flex items-center justify-center rounded-xl text-blue-500 font-bold">02</div>
                            <div>
                                <div class="font-bold">x-jump.top</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Redirect Node (Ghost Hop)</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="glass px-3 py-1 rounded-full text-[10px] text-blue-500 font-bold">LIVE TRAFFIC</span>
                            <button class="text-gray-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="glass p-4 rounded-2xl flex items-center justify-between border-white/5 opacity-50 grayscale">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 glass flex items-center justify-center rounded-xl text-gray-500 font-bold">03</div>
                            <div>
                                <div class="font-bold">data-cdn.xyz</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Storage Node Mask</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="glass px-3 py-1 rounded-full text-[10px] text-gray-500 font-bold">OFFLINE / STANDBY</span>
                            <button class="text-gray-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
