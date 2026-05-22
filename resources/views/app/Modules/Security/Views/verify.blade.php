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
            @if(session('message'))
            <div class="p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-500 text-[10px] font-bold uppercase tracking-widest">
                {{ session('message') }}
            </div>
            @endif

            <div class="p-6 glass bg-white/5 rounded-2xl border border-white/5 flex flex-col items-center gap-4">
                <div class="text-[10px] font-black uppercase tracking-widest text-blue-400">Security Challenge</div>
                
                <form action="{{ route('ghost-hop.verify.process') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-blue-600 rounded-xl text-sm font-bold uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">
                        Initialize Access
                    </button>
                </form>
            </div>

            <div class="pt-4 flex flex-col items-center gap-4">
                <div class="flex flex-col items-center gap-2">
                    <div class="text-[10px] text-gray-600 uppercase font-bold tracking-tighter">File Information (Masked)</div>
                    <div class="text-xs font-medium text-gray-400">{{ Str::limit($file->name, 20) }} ({{ Number::fileSize($file->size) }})</div>
                </div>

                <a href="{{ route('ghost-hop.report') }}" class="text-[9px] font-black text-gray-700 uppercase tracking-widest hover:text-red-500 transition-colors flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report this link
                </a>
            </div>
        </div>
        
        <div class="mt-10 text-[10px] text-gray-700 font-bold uppercase tracking-widest">
            Protected by HOA Ghost-Hop Engine
        </div>
    </div>
</div>
@endsection
