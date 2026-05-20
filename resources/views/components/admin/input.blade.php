@props([
    'label',
    'type' => 'text',
    'model' => null,
    'placeholder' => '',
    'hint' => null,
    'icon' => null,
])

<div class="space-y-2">
    @if($label)
        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">{{ $label }}</label>
    @endif
    
    <div class="relative group">
        <input 
            type="{{ $type }}" 
            @if($model) wire:model="{{ $model }}" @endif
            {{ $attributes->merge(['class' => 'w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500/50 transition-all font-mono placeholder:text-gray-700']) }}
            placeholder="{{ $placeholder }}"
        >
        @if($icon)
            <div class="absolute right-4 top-3.5 text-gray-600 group-focus-within:text-blue-500 transition-colors">
                {!! $icon !!}
            </div>
        @endif
    </div>

    @if($hint)
        <p class="text-[9px] text-gray-600 mt-2 ml-1 leading-relaxed">{{ $hint }}</p>
    @endif

    @if($model)
        @error($model) <span class="text-red-500 text-[9px] font-bold mt-1 block uppercase ml-1">{{ $message }}</span> @enderror
    @endif
</div>
