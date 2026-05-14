@props(['name', 'label' => null, 'error' => null])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full h-11 bg-white border border-ink/10 rounded-xl px-4 py-3 font-body text-ink placeholder:text-ink-alt/50 focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all duration-200']) }}
    >
    @if($error)
        <p class="text-sm text-swipe-no">{{ $error }}</p>
    @endif
</div>
