@extends('layouts.app')

@section('title', 'Swipe — GiftSwipe')

@section('content')

<div class="max-w-sm mx-auto px-6 py-10 w-full flex-1 flex flex-col">

    @if(!$participant)
        {{-- Formulaire participant --}}
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full">
                <div class="text-center">
                    <h1 class="text-3xl font-title font-semibold text-ink">🎁 Cadeau pour {{ $event->birthday_person_name }}</h1>
                    <p class="mt-2 text-ink-alt">Dis-nous qui tu es pour commencer à voter.</p>
                </div>

                <form method="POST" action="{{ route('swipe.join', $event) }}" class="mt-8 space-y-6">
                    @csrf

                    <x-input
                        name="name"
                        label="Ton prénom"
                        type="text"
                        placeholder="Comment tu t'appelles ?"
                        value="{{ old('name') }}"
                        :error="$errors->first('name')"
                        required
                        autofocus
                    />

                    <x-input
                        name="budget_max"
                        label="Ton budget max (optionnel)"
                        type="number"
                        placeholder="25€"
                        value="{{ old('budget_max') }}"
                        :error="$errors->first('budget_max')"
                        min="0"
                        step="0.01"
                    />

                    <x-button class="w-full">C'est parti !</x-button>
                </form>
            </div>
        </div>

    @elseif($remainingGifts->isEmpty())
        {{-- Plus de cadeaux à swiper --}}
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center">
                <p class="text-4xl">🎉</p>
                <h2 class="mt-4 text-2xl font-title font-semibold text-ink">T'as voté !</h2>
                <p class="mt-2 text-ink-alt">Plus qu'à attendre les autres.</p>
                <div class="mt-6">
                    <x-button href="{{ route('event.results', $event) }}">Voir les résultats</x-button>
                </div>
            </div>
        </div>

    @else
        {{-- Swipe deck --}}
        <div
            x-data="swipeManager()"
            x-cloak
            class="flex-1 flex flex-col items-center justify-center"
        >
            {{-- Counter --}}
            <p class="text-sm text-ink-alt mb-4">
                <span x-text="currentIndex + 1"></span> / <span x-text="gifts.length"></span>
            </p>

            {{-- Card zone --}}
            <div class="relative w-full h-80 sm:h-96">
                <template x-for="(gift, index) in gifts" :key="gift.id">
                    <div
                        x-show="index === currentIndex"
                        x-transition:enter="transition ease-out duration-200"
                        class="absolute inset-0 rounded-2xl bg-bg-alt shadow-lg p-8 flex flex-col items-center justify-center cursor-grab active:cursor-grabbing select-none touch-none"
                        :style="`transform: translateX(${index === currentIndex ? offsetX : 0}px) rotate(${index === currentIndex ? offsetX / 15 : 0}deg); transition: ${isDragging ? 'none' : 'transform 0.3s ease-out'}`"
                        @mousedown="onStart($event)"
                        @mousemove.window="onMove($event)"
                        @mouseup.window="onEnd($event)"
                        @touchstart.passive="onStart($event)"
                        @touchmove.passive="onMove($event)"
                        @touchend="onEnd($event)"
                    >
                        {{-- Yes overlay --}}
                        <div
                            class="absolute inset-0 rounded-2xl flex items-center justify-center pointer-events-none"
                            :style="`background: rgba(74, 222, 128, ${Math.min(Math.max(offsetX / 100, 0), 1) * 0.3})`"
                        >
                            <div
                                class="w-16 h-16 rounded-full bg-swipe-yes flex items-center justify-center text-white text-2xl font-bold"
                                :style="`opacity: ${Math.min(Math.max(offsetX / 100, 0), 1)}`"
                            >✓</div>
                        </div>

                        {{-- No overlay --}}
                        <div
                            class="absolute inset-0 rounded-2xl flex items-center justify-center pointer-events-none"
                            :style="`background: rgba(248, 113, 113, ${Math.min(Math.max(-offsetX / 100, 0), 1) * 0.3})`"
                        >
                            <div
                                class="w-16 h-16 rounded-full bg-swipe-no flex items-center justify-center text-white text-2xl font-bold"
                                :style="`opacity: ${Math.min(Math.max(-offsetX / 100, 0), 1)}`"
                            >✕</div>
                        </div>

                        {{-- Content --}}
                        <div class="relative z-10 text-center">
                            <template x-if="gift.image_url">
                                <img :src="gift.image_url" :alt="gift.name" class="h-32 w-32 rounded-2xl object-cover mx-auto">
                            </template>
                            <template x-if="!gift.image_url">
                                <p class="text-5xl">🎁</p>
                            </template>
                            <h3 class="mt-4 text-2xl font-title font-semibold text-ink" x-text="gift.name"></h3>
                            <p x-show="gift.price" class="mt-2 text-xl font-semibold text-accent" x-text="gift.price + '€'"></p>
                            <a
                                x-show="gift.url"
                                :href="gift.url"
                                target="_blank"
                                rel="noopener"
                                class="mt-3 inline-flex items-center gap-1 text-sm text-accent hover:underline"
                                @click.stop
                            >Voir le produit ↗</a>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Controls --}}
            <div class="mt-6 flex items-center gap-8">
                <button
                    @click="swipe(false)"
                    class="h-14 w-14 rounded-full border-2 border-swipe-no text-swipe-no flex items-center justify-center text-2xl hover:bg-swipe-no/10 transition-all duration-200 active:scale-95"
                >✕</button>
                <button
                    @click="swipe(true)"
                    class="h-14 w-14 rounded-full border-2 border-swipe-yes text-swipe-yes flex items-center justify-center text-2xl hover:bg-swipe-yes/10 transition-all duration-200 active:scale-95"
                >✓</button>
            </div>

            <p class="mt-3 text-xs text-ink-alt">← Non merci · J'aime →</p>
        </div>
    @endif

</div>

@if($participant && $remainingGifts->isNotEmpty())
@push('scripts')
<script>
    function swipeManager() {
        return {
            gifts: @json($giftsJson),
            currentIndex: 0,
            offsetX: 0,
            startX: 0,
            isDragging: false,
            isAnimating: false,

            onStart(e) {
                if (this.isAnimating) return;
                this.isDragging = true;
                this.startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            },

            onMove(e) {
                if (!this.isDragging) return;
                const clientX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                this.offsetX = clientX - this.startX;
            },

            onEnd(e) {
                if (!this.isDragging) return;
                this.isDragging = false;

                if (Math.abs(this.offsetX) > 100) {
                    this.swipe(this.offsetX > 0);
                } else {
                    this.offsetX = 0;
                }
            },

            async swipe(liked) {
                if (this.isAnimating) return;
                this.isAnimating = true;

                this.offsetX = liked ? 500 : -500;

                const gift = this.gifts[this.currentIndex];

                try {
                    const response = await fetch('{{ route("swipe.store", $event) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            gift_idea_id: gift.id,
                            liked: liked,
                        }),
                    });

                    const data = await response.json();

                    setTimeout(() => {
                        this.offsetX = 0;
                        this.currentIndex++;
                        this.isAnimating = false;

                        if (data.finished) {
                            window.location.href = data.redirect;
                        }
                    }, 300);
                } catch (err) {
                    this.offsetX = 0;
                    this.isAnimating = false;
                }
            },
        };
    }
</script>
@endpush
@endif

@endsection
