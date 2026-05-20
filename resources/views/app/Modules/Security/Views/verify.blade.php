@extends('layouts.app')

@section('title', 'Security Check - Hoa Cloud')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md glass-card p-10 border-white/10 shadow-[0_0_100px_rgba(37,99,235,0.1)] text-center relative overflow-hidden">
        <!-- Decorative element -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50"></div>
        
        <div class="mb-8">
            <div class="w-20 h-20 bg-blue-600/10 rounded-3xl flex items-center justify-center text-blue-500 mx-auto mb-6 border border-blue-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white mb-2">Human Verification</h1>
            <p class="text-sm text-gray-500">To maintain link security, please complete the challenge below to access your file.</p>
        </div>

        <div class="space-y-6">
            <div class="p-6 glass bg-white/5 rounded-2xl border border-white/5 flex flex-col items-center gap-4">
                <div class="text-[10px] font-black uppercase tracking-widest text-blue-400">Security Challenge</div>
                
                <form action="{{ route('ghost-hop.verify.process') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-blue-600 rounded-xl text-sm font-bold uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">
                        Initialize Access
                    </button>
                </form>
            </div>

            <div class="pt-4 flex flex-col items-center gap-2">
                <div class="text-[10px] text-gray-600 uppercase font-bold tracking-tighter">File Information (Masked)</div>
                <div class="text-xs font-medium text-gray-400">{{ Str::limit($file->name, 20) }} ({{ Number::fileSize($file->size) }})</div>
            </div>
        </div>
        
        <div class="mt-10 text-[10px] text-gray-700 font-bold uppercase tracking-widest">
            Protected by HOA Ghost-Hop Engine
        </div>
    </div>
</div>
@endsection
