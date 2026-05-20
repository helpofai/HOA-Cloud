<nav class="fixed top-0 left-0 w-full z-50 py-4 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between glass px-6 py-3 rounded-2xl">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/50">
                <span class="text-white font-bold">H</span>
            </div>
            <span class="text-xl font-bold tracking-tight">HOA<span class="text-blue-500">CLOUD</span></span>
        </div>
        
        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-400">
            <a href="#" class="hover:text-white transition-colors">Features</a>
            <a href="#" class="hover:text-white transition-colors">Security</a>
            <a href="#" class="hover:text-white transition-colors">Pricing</a>
            <a href="#" class="hover:text-white transition-colors">API</a>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm font-medium hover:text-white transition-colors">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transition-all">Get Started</a>
        </div>
    </div>
</nav>
