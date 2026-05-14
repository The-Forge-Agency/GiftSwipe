@props(['totalBudget', 'giftPrice'])

@if($giftPrice > 0)
    @php
        $percentage = min(($totalBudget / $giftPrice) * 100, 100);
        $sufficient = $totalBudget >= $giftPrice;
        $shortage = max($giftPrice - $totalBudget, 0);
    @endphp

    <div class="rounded-xl bg-bg-alt p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-ink">Budget du groupe</h3>
            <span class="text-sm text-ink-alt">{{ number_format($totalBudget, 0) }}€ / {{ number_format($giftPrice, 0) }}€</span>
        </div>
        <div class="h-3 rounded-full bg-ink/5 overflow-hidden">
            <div
                class="h-full rounded-full bg-gradient-to-r from-accent to-cagnotte transition-all duration-500"
                style="width: {{ $percentage }}%"
            ></div>
        </div>
        <p class="mt-2 text-sm {{ $sufficient ? 'text-swipe-yes' : 'text-ink-alt' }}">
            @if($sufficient)
                Budget suffisant ! Le cadeau est dans la poche 💸
            @else
                Il manque {{ number_format($shortage, 0) }}€ — relance le groupe !
            @endif
        </p>
    </div>
@endif
