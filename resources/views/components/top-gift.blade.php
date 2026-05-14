@props(['result'])

<div class="rounded-2xl bg-accent/10 p-8 text-center">
    @if($result->gift->image_url)
        <img src="{{ $result->gift->image_url }}" alt="{{ $result->gift->name }}" class="h-20 w-20 rounded-2xl object-cover mx-auto">
    @else
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-accent text-2xl text-white">
            🏆
        </div>
    @endif
    <h2 class="mt-4 text-2xl font-title font-semibold text-ink">{{ $result->gift->name }}</h2>
    @if($result->gift->price)
        <p class="mt-2 text-xl font-semibold text-accent">{{ number_format($result->gift->price, 0) }}€</p>
    @endif
    <p class="mt-2 text-ink-alt">{{ $result->positive_votes }} vote{{ $result->positive_votes > 1 ? 's' : '' }} positif{{ $result->positive_votes > 1 ? 's' : '' }}</p>
</div>
