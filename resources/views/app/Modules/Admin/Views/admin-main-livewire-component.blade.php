<div class="flex w-full h-full overflow-hidden">
    <style>
        .inner-shadow { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.5); }
        .breathe { animation: breathe 3s ease-in-out infinite; }
        @keyframes breathe { 0%, 100% { opacity: 0.6; transform: scale(1); } 50% { opacity: 1; transform: scale(1.1); } }
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
            @php
                $menuItems = [
                    ['id' => 'overview', 'label' => 'Overview', 'icon' => 'M4 6h16M4 12h16M4 18h7'],
                    ['id' => 'users', 'label' => 'Users & Quota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['id' => 'files', 'label' => 'File Monitoring', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['id' => 'domains', 'label' => 'Ghost Hop Domains', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                    ['id' => 'media-engine', 'label' => 'Media Control Center', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['id' => 'anti-bot', 'label' => 'Anti-Bot & Crawler', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.233-2.047-.652-2.956z'],
                    ['id' => 'shared-hosting', 'label' => 'Shared Hosting Opt.', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ];
            @endphp

            @foreach($menuItems as $item)
            <button wire:click="setSection('{{ $item['id'] }}')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === $item['id'] ? 'glass bg-red-600/10 text-red-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                <span class="font-medium text-sm">{{ $item['label'] }}</span>
            </button>
            @endforeach
            
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
                    <div class="w-10 h-10 rounded-xl glass border-red-500/30 flex items-center justify-center font-bold text-red-500">SR</div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scroll p-8">
            @switch($section)
                @case('overview')
                    @include('app.Modules.Admin.Views.sections.overview')
                    @break
                @case('users')
                    @include('app.Modules.Admin.Views.sections.users')
                    @break
                @case('files')
                    @include('app.Modules.Admin.Views.sections.files')
                    @break
                @case('domains')
                    @include('app.Modules.Admin.Views.sections.domains')
                    @break
                @case('media-engine')
                    @include('app.Modules.Admin.Views.sections.media-engine')
                    @break
                @case('anti-bot')
                    @include('app.Modules.Admin.Views.sections.anti-bot')
                    @break
                @case('shared-hosting')
                    @include('app.Modules.Admin.Views.sections.shared-hosting')
                    @break
                @default
                    @include('app.Modules.Admin.Views.sections.under-construction')
            @endswitch
        </div>
    </main>
</div>
