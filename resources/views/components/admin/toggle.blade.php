@props([
    'label' => null,
    'model' => null,
    'variant' => 'blue', // blue, yellow, red
])

@php
    $variants = [
        'blue' => 'peer-checked:bg-blue-600',
        'yellow' => 'peer-checked:bg-yellow-600',
        'red' => 'peer-checked:bg-red-600',
    ];
    $v = $variants[$variant] ?? $variants['blue'];
@endphp

<div class="flex items-center justify-between">
    @if($label)
        <span class="text-sm font-bold text-gray-300">{{ $label }}</span>
    @endif
    <label class="relative inline-flex items-center cursor-pointer scale-90">
        <input type="checkbox" @if($model) wire:model="{{ $model }}" @endif class="sr-only peer">
        <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all {{ $v }}"></div>
    </label>
</div>
