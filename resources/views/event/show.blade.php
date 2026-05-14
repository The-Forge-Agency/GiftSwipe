@extends('layouts.app')

@section('title', 'Cadeau pour ' . $event->birthday_person_name . ' — GiftSwipe')

@section('content')

<div class="max-w-lg mx-auto px-6 py-10 w-full">

    {{-- Header --}}
    <div class="text-center">
        <h1 class="text-3xl font-title font-semibold text-ink">🎁 Cadeau pour {{ $event->birthday_person_name }}</h1>
        <p class="mt-2 text-ink-alt">Anniversaire le {{ $event->birthday_date->translatedFormat('d F Y') }}</p>
    </div>

    {{-- Lien partageable --}}
    <div class="mt-8">
        <x-share-link :slug="$event->slug" />
    </div>

    {{-- Idées cadeaux --}}
    <div class="mt-8">
        <h2 class="text-xl font-title font-semibold text-ink">Idées cadeaux</h2>

        @if($event->giftIdeas->isEmpty())
            <div class="mt-4 rounded-2xl border-2 border-dashed border-ink-alt/30 p-8 text-center">
                <p class="text-2xl">🎁</p>
                <p class="mt-2 font-medium text-ink">Pas encore d'idée ?</p>
                <p class="mt-1 text-sm text-ink-alt">C'est le moment ! Même un truc débile, ça fait voter.</p>
            </div>
        @else
            <div class="mt-4 space-y-3">
                @foreach($event->giftIdeas as $gift)
                    <x-gift-card :gift="$gift" />
                @endforeach
            </div>
        @endif
    </div>

    {{-- Ajouter une idée --}}
    <div class="mt-8 rounded-2xl border border-ink/10 p-6" x-data="giftForm()">
        <h2 class="text-lg font-title font-semibold text-ink">Ajouter une idée</h2>
        <p class="mt-1 text-sm text-ink-alt">Même un truc débile, ça fait voter.</p>

        <form method="POST" action="{{ route('event.store-gift', $event) }}" class="mt-4 space-y-4">
            @csrf

            <x-input
                name="url"
                type="url"
                placeholder="https://... (colle un lien pour auto-remplir)"
                value="{{ old('url') }}"
                :error="$errors->first('url')"
                x-model="url"
                x-on:blur="scrapeUrl()"
            />

            <div x-show="scraping" class="text-sm text-ink-alt flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Récupération des infos...
            </div>

            <template x-if="imageUrl">
                <div class="flex items-center gap-3 rounded-xl bg-bg-alt p-3">
                    <img :src="imageUrl" alt="Aperçu" class="h-16 w-16 rounded-xl object-cover shrink-0">
                    <button type="button" @click="imageUrl = ''" class="text-ink-alt hover:text-swipe-no text-sm">Retirer</button>
                </div>
            </template>

            <x-input
                name="image_url"
                type="url"
                placeholder="URL de l'image (auto-détectée ou colle la tienne)"
                :error="$errors->first('image_url')"
                x-model="imageUrl"
            />

            <x-input
                name="name"
                type="text"
                placeholder="Un truc génial (ou pas)"
                value="{{ old('name') }}"
                :error="$errors->first('name')"
                x-model="name"
                required
            />

            <x-input
                name="description"
                type="text"
                placeholder="Petite description (optionnel)"
                value="{{ old('description') }}"
                :error="$errors->first('description')"
                x-model="description"
            />

            <x-input
                name="price"
                type="number"
                placeholder="25€"
                value="{{ old('price') }}"
                :error="$errors->first('price')"
                x-model="price"
                min="0"
                step="0.01"
            />

            <x-button class="w-full">Ajouter</x-button>
        </form>
    </div>

    {{-- Discussion --}}
    <div class="mt-8">
        <h2 class="text-xl font-title font-semibold text-ink">Discussion</h2>

        @if($event->messages->isNotEmpty())
            <div class="mt-4 space-y-3">
                @foreach($event->messages->sortBy('created_at') as $message)
                    <x-message :message="$message" />
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('event.store-message', $event) }}" class="mt-4 space-y-4 rounded-2xl border border-ink/10 p-4">
            <x-input
                name="author_name"
                type="text"
                placeholder="Ton prénom"
                value="{{ old('author_name', $authorName) }}"
                :error="$errors->first('author_name')"
                required
            />

            <div class="space-y-1.5">
                <textarea
                    name="content"
                    placeholder="Ton message..."
                    required
                    rows="2"
                    class="w-full bg-white border border-ink/10 rounded-xl px-4 py-3 font-body text-ink placeholder:text-ink-alt/50 focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all duration-200 resize-none"
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-sm text-swipe-no">{{ $message }}</p>
                @enderror
            </div>

            <x-button class="w-full">Envoyer</x-button>
        </form>
    </div>

    {{-- Lien résultats --}}
    <div class="mt-8">
        <x-button href="{{ route('event.results', $event) }}" variant="outline" class="w-full">
            📊 Voir les résultats
        </x-button>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function giftForm() {
        return {
            url: '{{ old("url") }}',
            name: '{{ old("name") }}',
            price: '{{ old("price") }}',
            imageUrl: '{{ old("image_url") }}',
            description: '{{ old("description") }}',
            scraping: false,

            async scrapeUrl() {
                if (!this.url || !this.url.startsWith('http')) return;
                this.scraping = true;

                try {
                    const response = await fetch('/api/scrape-url', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ url: this.url }),
                    });

                    const data = await response.json();

                    if (data.clean_url) this.url = data.clean_url;
                    if (data.title && !this.name) this.name = data.title;
                    if (data.price && !this.price) this.price = data.price;
                    if (data.image_url) this.imageUrl = data.image_url;
                    if (data.description && !this.description) this.description = data.description;
                } catch (e) {
                    // silently fail
                } finally {
                    this.scraping = false;
                }
            },
        };
    }
</script>
@endpush
