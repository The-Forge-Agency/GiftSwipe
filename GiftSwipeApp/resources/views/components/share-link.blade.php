@props(['slug' => null, 'url' => null, 'label' => 'Lien à partager'])

@php
    $shareUrl = $url ?? url($slug . '/swipe');
@endphp

<div x-data="{ copied: false }">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📤</span>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-ink-alt">{{ $label }}</p>
            <p class="text-sm font-medium text-ink truncate" x-ref="linkText">{{ $shareUrl }}</p>
        </div>
        <button
            type="button"
            class="shrink-0 h-9 px-4 text-sm font-semibold rounded-xl bg-accent text-white hover:bg-accent-hover transition-all duration-200"
            @click="navigator.clipboard.writeText($refs.linkText.textContent.trim()); copied = true; setTimeout(() => copied = false, 2000)"
        >
            <span x-show="!copied">Copier</span>
            <span x-show="copied" x-cloak>Copié !</span>
        </button>
    </div>
</div>
