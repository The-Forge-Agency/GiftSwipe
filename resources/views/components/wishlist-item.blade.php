@props(['item', 'editable' => false, 'deleteUrl' => null])

<div class="flex items-center gap-4 rounded-2xl bg-bg-alt p-4">
    @if($item->image_url)
        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="h-10 w-10 rounded-xl object-cover shrink-0">
    @else
        <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-accent/10 text-lg shrink-0">
            🎁
        </div>
    @endif
    <div class="flex-1 min-w-0">
        <p class="font-medium text-ink truncate">{{ $item->name }}</p>
        @if($item->url)
            <a href="{{ $item->url }}" target="_blank" rel="noopener" class="text-sm text-accent hover:underline inline-flex items-center gap-1">
                Voir le produit ↗
            </a>
        @endif
    </div>
    @if($item->price)
        <span class="font-semibold text-accent shrink-0">{{ number_format($item->price, 0) }}€</span>
    @endif
    @if($editable && $deleteUrl)
        <form method="POST" action="{{ $deleteUrl }}" class="shrink-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-ink-alt hover:text-swipe-no transition-colors text-lg">✕</button>
        </form>
    @endif
</div>
