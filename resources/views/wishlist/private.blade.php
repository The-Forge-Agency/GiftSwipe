@extends('layouts.app')

@section('title', 'Ma wishlist — GiftSwipe')

@section('content')

<div class="max-w-lg mx-auto px-6 py-10 w-full">

    <div class="text-center">
        <h1 class="text-3xl font-title font-semibold text-ink">Ma wishlist</h1>
        <p class="mt-2 text-ink-alt">Ajoute tes envies et partage le lien public.</p>
    </div>

    {{-- Liens de partage --}}
    <div class="mt-8 space-y-3">
        <div class="rounded-2xl bg-bg-alt p-4">
            <p class="text-sm font-medium text-ink mb-2">Lien public (lecture seule)</p>
            <x-share-link :url="route('wishlist.public', $wishlist)" label="Copier le lien public" />
        </div>
        <div class="rounded-2xl bg-bg-alt p-4">
            <p class="text-sm font-medium text-ink mb-2">Lien privé (pour modifier)</p>
            <x-share-link :url="route('wishlist.private', $wishlist->private_slug)" label="Copier le lien privé" />
        </div>
    </div>

    {{-- Items --}}
    <div class="mt-8">
        <h2 class="text-xl font-title font-semibold text-ink">Mes envies</h2>

        @if($wishlist->items->isEmpty())
            <div class="mt-4 rounded-2xl border-2 border-dashed border-ink-alt/30 p-8 text-center">
                <p class="text-2xl">🎁</p>
                <p class="mt-2 font-medium text-ink">Pas encore d'envie ?</p>
                <p class="mt-1 text-sm text-ink-alt">Ajoute ton premier souhait ci-dessous.</p>
            </div>
        @else
            <div class="mt-4 space-y-3">
                @foreach($wishlist->items as $item)
                    <x-wishlist-item
                        :item="$item"
                        editable
                        :deleteUrl="route('wishlist.destroy-item', [$wishlist->private_slug, $item])"
                    />
                @endforeach
            </div>
        @endif
    </div>

    {{-- Ajouter un souhait --}}
    <div class="mt-8 rounded-2xl border border-ink/10 p-6" x-data="wishlistForm()">
        <h2 class="text-lg font-title font-semibold text-ink">Ajouter un souhait</h2>
        <p class="mt-1 text-sm text-ink-alt">Colle un lien pour auto-remplir les infos.</p>

        <form method="POST" action="{{ route('wishlist.store-item', $wishlist->private_slug) }}" class="mt-4 space-y-4">
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

            <div x-show="!imageUrl" class="space-y-1.5">
                <label class="flex items-center gap-3 cursor-pointer rounded-xl border border-dashed border-ink/20 p-3 hover:border-accent/50 transition-colors">
                    <span class="text-2xl">📷</span>
                    <span class="text-sm text-ink-alt" x-text="uploading ? 'Upload en cours...' : 'Importer une image'"></span>
                    <input type="file" accept="image/*" class="hidden" @change="uploadImage($event)">
                </label>
            </div>

            <input type="hidden" name="image_url" :value="imageUrl">

            <x-input
                name="name"
                type="text"
                placeholder="Nom du produit"
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
                placeholder="Prix (optionnel)"
                value="{{ old('price') }}"
                :error="$errors->first('price')"
                x-model="price"
                min="0"
                step="0.01"
            />

            <x-button class="w-full">Ajouter</x-button>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function wishlistForm() {
        return {
            url: '{{ old("url") }}',
            name: '{{ old("name") }}',
            price: '{{ old("price") }}',
            imageUrl: '{{ old("image_url") }}',
            description: '{{ old("description") }}',
            scraping: false,
            uploading: false,

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
                } finally {
                    this.scraping = false;
                }
            },

            async uploadImage(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.uploading = true;

                try {
                    const formData = new FormData();
                    formData.append('image', file);

                    const response = await fetch('/api/upload-image', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();
                    if (data.image_url) this.imageUrl = data.image_url;
                } catch (e) {
                } finally {
                    this.uploading = false;
                }
            },
        };
    }
</script>
@endpush
