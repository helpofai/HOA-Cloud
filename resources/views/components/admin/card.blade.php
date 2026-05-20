@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'variant' => 'blue', // blue, red, orange, green
])

@php
    $variants = [
        'blue' => ['border' => 'border-blue-500/10', 'shadow' => 'shadow-blue-500/5', 'text' => 'text-blue-500'],
        'red' => ['border' => 'border-red-500/10', 'shadow' => 'shadow-red-500/5', 'text' => 'text-red-500'],
        'orange' => ['border' => 'border-orange-500/10', 'shadow' => 'shadow-orange-500/5', 'text' => 'text-orange-500'],
        'green' => ['border' => 'border-green-500/10', 'shadow' => 'shadow-green-500/5', 'text' => 'text-green-500'],
    ];
    $v = $variants[$variant] ?? $variants['blue'];
@endphp

<div {{ $attributes->merge(['class' => "glass-card p-8 border {$v['border']} shadow-2xl {$v['shadow']}"]) }}>
    @if($title || $slot->isNotEmpty())
        <div class="flex items-center justify-between mb-8">
            <div>
                @if($title)
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        @if($icon)
                            <div class="{{ $v['text'] }}">{!! $icon !!}</div>
                        @endif
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerAction))
                {{ $headerAction }}
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
