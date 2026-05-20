@props([
    'label',
    'status' => 'active', // active, inactive, pending, warning
    'icon' => null,
])

@php
    $variants = [
        'active' => 'bg-green-500/10 text-green-500 border-green-500/20',
        'inactive' => 'bg-gray-500/10 text-gray-500 border-white/5',
        'warning' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
        'error' => 'bg-red-500/10 text-red-500 border-red-500/20',
    ];
    $v = $variants[$status] ?? $variants['active'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-3 p-5 glass rounded-2xl border {$v} transition-all duration-300"]) }}>
    <div class="w-12 h-12 rounded-xl bg-current opacity-10 flex items-center justify-center">
        @if($icon)
            <div class="opacity-100">{!! $icon !!}</div>
        @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @endif
    </div>
    <div>
        <div class="text-xs font-bold text-white">{{ $label }}</div>
        <div class="text-[10px] uppercase font-bold opacity-60">{{ $slot }}</div>
    </div>
</div>
