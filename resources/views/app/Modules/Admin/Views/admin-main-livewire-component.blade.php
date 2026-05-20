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
            <button wire:click="setSection('media-engine')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'media-engine' ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="font-medium text-sm">Media Control Center</span>
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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

            @if($section === 'media-engine')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- API Configuration -->
                <div class="lg:col-span-2 space-y-8">
                    <x-admin.card 
                        title="Media Metadata Engine" 
                        subtitle="Configure external data sources"
                        variant="blue"
                    >
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </x-slot:icon>
                        
                        <x-slot:headerAction>
                            <div class="flex items-center gap-2 px-3 py-1 glass rounded-full">
                                <span class="w-2 h-2 rounded-full bg-green-500 breathe"></span>
                                <span class="text-[9px] font-black text-gray-400 uppercase">Engine Active</span>
                            </div>
                        </x-slot:headerAction>

                        <form wire:submit.prevent="saveApiSettings" class="space-y-8">
                            <!-- TMDB Setup -->
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_square_2-d537fb228cf3ded904ef09b136fe3fec72548ebc1fea3fbbd1ad9e36364db38b.svg" class="w-20">
                                </div>
                                <h4 class="text-sm font-bold mb-4 flex items-center gap-2 text-blue-400">The Movie Database (TMDB)</h4>
                                <x-admin.input 
                                    label="API Key (v3 auth)" 
                                    model="tmdbApiKey" 
                                    type="password" 
                                    placeholder="Enter TMDB API Key..."
                                />
                            </div>

                            <!-- OMDb Setup -->
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-yellow-500 font-black text-4xl">OMDb</div>
                                <div class="mb-4">
                                    <x-admin.toggle 
                                        label="OMDb API (Fallback Engine)" 
                                        model="useOmdb" 
                                        variant="yellow" 
                                    />
                                </div>
                                <div class="space-y-4 {{ $useOmdb ? '' : 'opacity-40 grayscale pointer-events-none' }} transition-all">
                                    <x-admin.input 
                                        label="API Key (Required for usage)" 
                                        model="omdbApiKey" 
                                        type="password" 
                                        placeholder="Enter OMDb API Key..."
                                    />
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <p class="text-[9px] text-gray-500 max-w-xs uppercase font-bold leading-relaxed">
                                    Settings are applied instantly to background scraper jobs. Existing media will not be re-indexed unless manually triggered.
                                </p>
                                <button type="submit" class="px-10 py-4 bg-blue-600 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-105 active:scale-95">
                                    Deploy Configuration
                                </button>
                            </div>
                        </form>
                    </x-admin.card>

                    <div class="glass-card p-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-6">Engine Diagnostics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-admin.status-card 
                                label="TMDB Status" 
                                :status="$tmdbApiKey ? 'active' : 'inactive'"
                            >
                                <x-slot:icon>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </x-slot:icon>
                                {{ $tmdbApiKey ? 'Connected' : 'Missing Key' }}
                            </x-admin.status-card>

                            <x-admin.status-card 
                                label="OMDb Status" 
                                :status="$useOmdb ? ($omdbApiKey ? 'warning' : 'inactive') : 'inactive'"
                            >
                                <x-slot:icon>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </x-slot:icon>
                                {{ $useOmdb ? ($omdbApiKey ? 'Enabled' : 'Key Missing') : 'Disabled' }}
                            </x-admin.status-card>
                        </div>
                    </div>
                </div>

                <!-- System Binaries -->
                <div class="space-y-8">
                    <x-admin.card 
                        title="Portable Binaries" 
                        variant="red"
                    >
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </x-slot:icon>
                        
                        <div class="space-y-6">
                            <x-admin.input 
                                label="FFmpeg Engine" 
                                :value="config('hoa-cloud.bin.ffmpeg')" 
                                readonly 
                                icon='<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                            />

                            <x-admin.input 
                                label="FFprobe Scraper" 
                                :value="config('hoa-cloud.bin.ffprobe')" 
                                readonly 
                                icon='<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                            />

                            <div class="pt-6 border-t border-white/5 space-y-3">
                                <button class="w-full py-3 rounded-xl glass text-[10px] font-bold uppercase hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Clear Metadata Cache
                                </button>
                                <button class="w-full py-3 rounded-xl glass text-[10px] font-bold uppercase hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                                    Force Re-Index All Files
                                </button>
                            </div>
                        </div>
                    </x-admin.card>

                    <div class="glass-card p-8 border-orange-500/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold uppercase tracking-tight text-orange-400">Security Warning</h4>
                        </div>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                            Changing API keys or rotating binary paths can disrupt active stream processes. Use diagnostic utilities with caution on production nodes.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($section === 'settings')
            <div class="glass-card p-8">
                <h3 class="text-xl font-bold mb-4 text-gray-400">General System Settings</h3>
                <p class="text-sm text-gray-500">General configuration options will appear here.</p>
            </div>
            @endif

            @if(in_array($section, ['users', 'files', 'abuse']))
            <div class="py-20 flex flex-col items-center justify-center text-center opacity-50">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
                <h2 class="text-xl font-bold uppercase tracking-tighter">Under Construction</h2>
                <p class="text-sm max-w-xs mt-2 leading-relaxed">The <span class="text-red-500 font-bold uppercase">{{ $section }}</span> system is being optimized for the next deployment phase.</p>
            </div>
            @endif
        </div>
    </main>
</div>
