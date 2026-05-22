<div class="flex w-full h-full overflow-hidden">
    <!-- Sidebar -->
    <aside class="sidebar-width h-full glass-dark border-r border-white/5 flex flex-col z-20">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/40">
                <span class="text-white font-bold">H</span>
            </div>
            <span class="text-lg font-bold tracking-tight">HOA<span class="text-blue-500">CLOUD</span></span>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-4">
            <button wire:click="setSection('files')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'files' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <span class="font-medium text-sm">All Files</span>
            </button>
            <button wire:click="setSection('photos')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'photos' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="font-medium text-sm">Photos</span>
            </button>
            <button wire:click="setSection('music')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'music' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                <span class="font-medium text-sm">Music</span>
            </button>
            <button wire:click="setSection('videos')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'videos' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span class="font-medium text-sm">Videos</span>
            </button>
            <div class="pt-6 pb-2 px-4 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Personal</div>
            <button wire:click="setSection('shared')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'shared' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span class="font-medium text-sm">Shared Links</span>
            </button>
            <button wire:click="setSection('domain')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'domain' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span class="font-medium text-sm">Branding</span>
            </button>
            <button wire:click="setSection('security')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'security' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="font-medium text-sm">Security</span>
            </button>
            <button wire:click="setSection('bin')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $section === 'bin' ? 'glass bg-blue-600/10 text-blue-400' : 'text-gray-500 hover:bg-white/5 hover:text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span class="font-medium text-sm">Recycle Bin</span>
            </button>
        </nav>

        <div class="p-4 glass m-4 rounded-2xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-400">Storage</span>
                <span class="text-[10px] text-blue-400">75%</span>
            </div>
            <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.5)]" style="width: 75%"></div>
            </div>
            <div class="mt-2 text-[10px] text-gray-500 text-center">7.5 GB of 10 GB used</div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="content-calc h-full flex flex-col relative">
        <!-- Header -->
        <header class="h-16 flex items-center justify-between px-8 border-b border-white/5 z-10 glass-dark">
            <div class="flex items-center gap-4 flex-1 max-w-xl">
                <div class="relative w-full">
                    <input type="text" placeholder="Search files, music, movies..." class="w-full bg-white/5 border border-white/10 rounded-xl px-10 py-2 text-sm focus:outline-none focus:border-blue-500/50 transition-all">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="flex items-center gap-4 ml-8">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin') }}" class="px-4 py-2 bg-red-600/10 border border-red-500/30 rounded-xl text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-600/20 transition-all flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    System Control
                </a>
                @endif

                <button class="w-10 h-10 flex items-center justify-center rounded-xl glass hover:bg-white/5 transition-all relative">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-blue-600 rounded-full border-2 border-[#0a0a0a]"></span>
                </button>
                <div class="flex items-center gap-3 pl-4 border-l border-white/5">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">{{ auth()->user()->role->label() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl glass border-blue-500/30 flex items-center justify-center font-bold text-blue-500 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff" alt="Avatar">
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto custom-scroll p-8">
            @if($section === 'domain')
                @include('app.Modules.Dashboard.Views.sections.domain')
            @elseif($section === 'shared')
                @include('app.Modules.Dashboard.Views.sections.shared')
            @elseif($section === 'security')
                @include('app.Modules.Dashboard.Views.sections.security')
            @else
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if($currentFolderUuid)
                            <button wire:click="goBack" class="p-1 hover:bg-white/5 rounded-lg transition-colors text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            @endif
                            <h1 class="text-2xl font-bold tracking-tight capitalize">{{ $currentFolder ? $currentFolder->name : $section }}</h1>
                            </div>
                            <p class="text-xs text-gray-500">Manage and organize your personal storage</p>
                            </div>

                            <div class="flex items-center gap-6">
                            <!-- Media Filter -->
                            <div class="flex items-center glass p-1 rounded-xl">
                                @foreach(['all' => 'All', 'movies' => 'Movies', 'music' => 'Music', 'docs' => 'Docs'] as $key => $label)
                                <button 
                                    wire:click="$set('filter', '{{ $key }}')"
                                    class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $filter === $key ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-500 hover:text-gray-300' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-3">
                                <button @click="$dispatch('open-modal', { name: 'create-folder' })" class="px-5 py-2 glass rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-white/5 transition-all text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    New Folder
                                </button>
                                <button id="upload-files-btn" class="px-5 py-2 bg-blue-600 rounded-xl text-sm font-bold flex items-center gap-2 shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload Files
                                </button>
                            </div>
                            </div>
                            </div>
                <!-- Media Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                    <!-- Folders First -->
                    @foreach ($folders as $folder)
                    <div wire:key="folder-{{ $folder->uuid }}" class="group relative">
                        <div wire:click="openFolder('{{ $folder->uuid }}')" class="aspect-[2/3] rounded-xl overflow-hidden glass-card border-white/5 group-hover:border-blue-500/30 transition-all duration-300 transform group-hover:scale-[1.02] cursor-pointer flex flex-col items-center justify-center gap-4">
                            <div class="w-20 h-20 bg-blue-600/10 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-blue-400">Directory</div>
                        </div>
                        <div class="mt-3 px-1">
                            <div class="text-sm font-bold truncate group-hover:text-blue-400 transition-colors">{{ $folder->name }}</div>
                            <div class="text-[10px] text-gray-500 flex items-center gap-2">
                                <span>{{ $folder->children->count() }} folders</span>
                                <span>•</span>
                                <span>{{ $folder->files->count() }} files</span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Files -->
                    @foreach ($files as $file)
                    <div wire:key="file-{{ $file->uuid }}" class="group relative">
                        <a href="{{ $file->ghost_url }}" target="_blank" class="aspect-[2/3] rounded-xl overflow-hidden glass-card border-white/5 group-hover:border-blue-500/30 transition-all duration-300 transform group-hover:scale-[1.02] cursor-pointer block">
                            <div class="w-full h-full bg-white/5 relative flex items-center justify-center">
                                @if($file->poster_path)
                                    @php
                                        $posterUrl = Str::startsWith($file->poster_path, 'http') 
                                            ? $file->poster_path 
                                            : config('hoa-cloud.tmdb.image_url') . $file->poster_path;
                                    @endphp
                                    <img src="{{ $posterUrl }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                                @elseif(Str::startsWith($file->mime_type, 'image/'))
                                    <img src="/api/placeholder/400/600" class="w-full h-full object-cover opacity-50 group-hover:opacity-80 transition-opacity">
                                @else
                                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-gray-500 group-hover:text-blue-500 transition-colors">
                                        @if(Str::startsWith($file->mime_type, 'video/'))
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        @elseif(Str::startsWith($file->mime_type, 'audio/'))
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>
                                        @else
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 gap-3">
                                    <div class="w-10 h-10 rounded-full glass border-white/20 flex items-center justify-center hover:bg-white/20 transition-all">
                                        @if(Str::startsWith($file->mime_type, 'audio/'))
                                            <button wire:click.prevent="playAudio('{{ $file->uuid }}')" class="w-full h-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </button>
                                        @else
                                            <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        @endif
                                    </div>
                                    <button wire:click.prevent="generateShareLink('{{ $file->uuid }}')" class="w-10 h-10 rounded-full glass border-white/20 flex items-center justify-center hover:bg-blue-600 transition-all" title="Generate Share Link">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                    </button>
                                </div>

                                @if($file->rating)
                                <div class="absolute top-2 right-2 px-1.5 py-0.5 glass rounded text-[9px] font-black text-yellow-500 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    {{ $file->rating }}
                                </div>
                                @endif
                            </div>
                        </a>
                        <div class="mt-3 px-1">
                            <div class="text-sm font-bold truncate group-hover:text-blue-400 transition-colors">{{ $file->name }}</div>
                            <div class="text-[10px] text-gray-500 flex items-center gap-2">
                                <span>{{ Number::fileSize($file->size) }}</span>
                                <span>•</span>
                                <span>{{ $file->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($folders->isEmpty() && $files->isEmpty())
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-50">
                        <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <h2 class="text-xl font-bold">No items found</h2>
                        <p class="text-sm">Start by creating a folder or uploading files</p>
                    </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Upload Drawer (Dynamic) -->
        <div x-data="{ 
                open: false, 
                uploads: [],
                addUpload(file) {
                    this.uploads.push({
                        id: file.uniqueIdentifier,
                        name: file.fileName,
                        progress: 0,
                        status: 'uploading'
                    });
                    this.open = true;
                },
                updateProgress(id, progress) {
                    const upload = this.uploads.find(u => u.id === id);
                    if (upload) upload.progress = progress;
                },
                markSuccess(id) {
                    const upload = this.uploads.find(u => u.id === id);
                    if (upload) {
                        upload.progress = 100;
                        upload.status = 'completed';
                    }
                }
            }" 
            x-on:upload-started.window="addUpload($event.detail.file)"
            x-on:upload-progress.window="updateProgress($event.detail.id, $event.detail.progress)"
            x-on:upload-success.window="markSuccess($event.detail.id)"
            x-show="uploads.length > 0"
            :class="open ? 'translate-y-0' : 'translate-y-[calc(100%-48px)]'" 
            class="absolute bottom-6 right-8 w-80 glass-card border-blue-500/20 shadow-2xl transition-transform duration-500 z-30 overflow-hidden"
            x-cloak>
            <div @click="open = !open" class="h-12 flex items-center justify-between px-4 bg-blue-600/10 cursor-pointer hover:bg-blue-600/20 transition-colors">
                <span class="text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Uploads (<span x-text="uploads.length"></span>)
                </span>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <div class="p-4 space-y-4 max-h-60 overflow-y-auto custom-scroll">
                <template x-for="upload in uploads" :key="upload.id">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="truncate pr-4 text-gray-300" x-text="upload.name"></span>
                            <span :class="upload.status === 'completed' ? 'text-green-500' : 'text-blue-500'" class="font-bold" x-text="upload.status === 'completed' ? 'Completed' : upload.progress + '%'"></span>
                        </div>
                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                            <div :class="upload.status === 'completed' ? 'bg-green-500' : 'bg-blue-600 shimmer'" class="h-full transition-all duration-300" :style="'width: ' + upload.progress + '%'"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <!-- Create Folder Modal -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail.name === 'create-folder') show = true"
         x-on:close-modal.window="if ($event.detail.name === 'create-folder') show = false"
         x-show="show"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6"
         x-cloak>
        <div x-show="show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div x-show="show" x-transition.scale.95 class="w-full max-w-sm glass-card p-8 relative z-10 border-white/10 shadow-[0_0_50px_rgba(37,99,235,0.2)]">
            <h3 class="text-xl font-bold mb-6">Create New Directory</h3>
            
            <form wire:submit.prevent="createFolder" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Directory Name</label>
                    <input type="text" wire:model="newFolderName" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500/50 transition-all" placeholder="Enter folder name..." autofocus>
                    @error('newFolderName') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="show = false" class="flex-1 py-3 rounded-xl glass text-xs font-bold uppercase tracking-widest hover:bg-white/5 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">Initialize</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Reports Modal -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail.name === 'view-reports') show = true"
         x-on:close-modal.window="if ($event.detail.name === 'view-reports') show = false"
         x-show="show"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6"
         x-cloak>
        <div x-show="show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div x-show="show" x-transition.scale.95 class="w-full max-w-lg glass-card p-8 relative z-10 border-white/10 shadow-[0_0_50px_rgba(239,68,68,0.2)]">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-3 text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Abuse Reports
            </h3>
            
            <div class="space-y-4 max-h-96 overflow-y-auto custom-scroll pr-2">
                @forelse($shareReports as $report)
                <div class="p-4 bg-white/5 rounded-xl border border-white/5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-orange-500">{{ $report->reason }}</span>
                        <span class="text-[9px] text-gray-600">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-400 italic leading-relaxed">{{ $report->details ?: 'No additional details provided.' }}</p>
                </div>
                @empty
                <div class="py-10 text-center opacity-30 text-xs font-black uppercase tracking-widest">No reports found</div>
                @endforelse
            </div>

            <div class="mt-8">
                <button type="button" @click="show = false" class="w-full py-3 rounded-xl glass text-xs font-bold uppercase tracking-widest hover:bg-white/5 transition-all">Close</button>
            </div>
        </div>
    </div>

    <!-- Resumable.js Logic -->
    <script src="{{ asset('js/resumable.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resumable = new Resumable({
                target: '{{ route('upload') }}',
                query: {
                    _token: '{{ csrf_token() }}',
                    folder_uuid: @js($currentFolderUuid)
                },
                chunkSize: 10 * 1024 * 1024, // 10MB
                simultaneousUploads: 3,
                testChunks: true,
                throttleProgressCallbacks: 1,
            });

            const uploadBtn = document.getElementById('upload-files-btn');
            if (uploadBtn) {
                resumable.assignBrowse(uploadBtn);
            }

            resumable.on('fileAdded', function (file) {
                resumable.upload();
                window.dispatchEvent(new CustomEvent('upload-started', { detail: { file: file } }));
            });

            resumable.on('fileProgress', function (file) {
                window.dispatchEvent(new CustomEvent('upload-progress', { 
                    detail: { 
                        id: file.uniqueIdentifier, 
                        progress: Math.floor(file.progress() * 100) 
                    } 
                }));
            });

            resumable.on('fileSuccess', function (file, message) {
                window.dispatchEvent(new CustomEvent('upload-success', { detail: { id: file.uniqueIdentifier } }));
                // Refresh component to show new file
                Livewire.dispatch('refresh-files');
            });

            resumable.on('fileError', function (file, message) {
                window.dispatchEvent(new CustomEvent('upload-error', { detail: { id: file.uniqueIdentifier, message: message } }));
            });
        });
    </script>
</div>
