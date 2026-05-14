@props(['variant' => 'primary', 'type' => 'submit', 'href' => null])

@php
$base = 'inline-flex items-center justify-center gap-2 font-body font-semibold rounded-xl transition-all duration-200 ease-out select-none';
$sizes = 'h-11 px-6 text-base';
$variants = match($variant) {
    'primary' => 'bg-accent text-white hover:bg-accent-hover active:scale-[0.98]',
    'outline' => 'border-2 border-accent text-accent hover:bg-accent/5 active:scale-[0.98]',
    'ghost' => 'text-accent hover:underline',
    default => 'bg-accent text-white hover:bg-accent-hover',
};
$classes = "$base $sizes $variants";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
