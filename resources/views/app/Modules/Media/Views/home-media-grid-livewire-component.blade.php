<section class="py-24 bg-[#0a0a0a] relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
            <div class="max-w-xl">
                <h2 class="text-4xl font-black tracking-tighter mb-4 uppercase">
                    Trending <span class="text-blue-500">Global</span> Media
                </h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Explore the latest encrypted media shared across the HOA Cloud network. 
                    Purely evasive, 100% non-traceable.
                </p>
            </div>

            <div class="flex items-center glass p-1 rounded-2xl">
                @foreach(['all' => 'All', 'movies' => 'Movies', 'music' => 'Music', 'docs' => 'Docs'] as $key => $label)
                <button 
                    wire:click="setFilter('{{ $key }}')"
                    class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filter === $key ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/40' : 'text-gray-500 hover:text-gray-300' }}"
                >
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($files as $file)
            <div wire:key="home-file-{{ $file->uuid }}" class="group cursor-pointer">
                <div class="aspect-[2/3] rounded-2xl overflow-hidden glass-card border-white/5 relative transform group-hover:scale-[1.05] group-hover:-translate-y-2 transition-all duration-500 shadow-2xl">
                    <img 
                        src="{{ Str::startsWith($file->poster_path, 'http') ? $file->poster_path : config('hoa-cloud.tmdb.image_url') . $file->poster_path }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        alt="{{ $file->name }}"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-end p-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 glass rounded text-[8px] font-black uppercase tracking-tighter text-blue-400">
                                {{ Str::replace('video/', '', $file->mime_type) }}
                            </span>
                            @if($file->rating)
                            <span class="flex items-center gap-1 text-[9px] font-black text-yellow-500">
                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                {{ $file->rating }}
                            </span>
                            @endif
                        </div>
                        <h3 class="text-xs font-bold text-white truncate shadow-black" title="{{ $file->name }}">{{ $file->name }}</h3>
                    </div>

                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="w-8 h-8 rounded-full glass border-white/20 flex items-center justify-center text-blue-500 shadow-xl">
                            <svg class="w-4 h-4 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center glass rounded-3xl border-dashed border-white/5">
                <div class="text-xs font-black uppercase tracking-widest text-gray-600">No media available in this category</div>
            </div>
            @endforelse
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-white text-black font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-xl shadow-white/5">
                Explore More encrypted Content
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>

    <!-- Background Accents -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-red-600/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/2"></div>
</section>
