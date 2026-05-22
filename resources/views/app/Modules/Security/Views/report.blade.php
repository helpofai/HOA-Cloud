@extends('layouts.app')

@section('title', 'Report Abuse - Hoa Cloud')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-lg glass-card p-10 border-white/10 shadow-[0_0_100px_rgba(239,68,68,0.1)] relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent opacity-50"></div>
        
        <div class="mb-8 text-center">
            <div class="w-20 h-20 bg-red-600/10 rounded-3xl flex items-center justify-center text-red-500 mx-auto mb-6 border border-red-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white mb-2">Report Abuse</h1>
            <p class="text-sm text-gray-500">Is this content violating our terms or legal policies? Let us know.</p>
        </div>

        <form action="{{ route('ghost-hop.report.submit') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Reason for Report</label>
                <select name="reason" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-red-500/50 outline-none transition-all text-gray-300">
                    <option value="DMCA / Copyright Infringement">DMCA / Copyright Infringement</option>
                    <option value="Adult / Explicit Content">Adult / Explicit Content</option>
                    <option value="Malware / Phishing">Malware / Phishing</option>
                    <option value="Spam / Misleading">Spam / Misleading</option>
                    <option value="Other">Other</option>
                </select>
                @error('reason') <span class="text-[10px] text-red-500 font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Additional Details</label>
                <textarea name="details" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-red-500/50 outline-none transition-all text-gray-300 placeholder-gray-600" placeholder="Please provide any additional information to help us investigate..."></textarea>
                @error('details') <span class="text-[10px] text-red-500 font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="p-4 bg-white/5 rounded-xl border border-white/5 text-[10px] text-gray-500 leading-relaxed italic">
                By submitting this report, you confirm that the information provided is accurate and that you are acting in good faith. False reporting may lead to IP blacklisting.
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="flex-1 py-4 glass text-xs font-bold uppercase tracking-widest text-center hover:bg-white/5 transition-all">Cancel</a>
                <button type="submit" class="flex-1 py-4 bg-red-600 rounded-xl text-sm font-bold uppercase tracking-widest shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all text-white">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
