@extends('layouts.app')

@section('title', 'Mes espaces — GiftSwipe')

@section('content')

<div class="flex-1 px-6 py-12">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-title font-semibold text-ink text-center">Mes espaces</h1>
        <p class="mt-2 text-ink-alt text-center">Retrouve tes wishlists et événements.</p>

        {{-- Wishlists --}}
        <section class="mt-10">
            <h2 class="text-xl font-title font-semibold text-ink flex items-center gap-2">
                <span class="text-2xl">🎁</span> Mes wishlists
            </h2>

            @if($wishlists->isEmpty())
                <p class="mt-4 text-ink-alt text-sm">Aucune wishlist pour le moment.</p>
                <x-button href="{{ route('wishlist.create') }}" variant="outline" class="mt-4">
                    Créer ma wishlist
                </x-button>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($wishlists as $wishlist)
                        <a href="{{ route('wishlist.private', $wishlist->private_slug) }}"
                           class="block rounded-2xl bg-white p-4 hover:ring-2 hover:ring-accent/30 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-ink">{{ $wishlist->person_name }}</p>
                                    <p class="text-sm text-ink-alt">
                                        {{ $wishlist->birthday_date->translatedFormat('d F Y') }}
                                        &middot; {{ $wishlist->items_count ?? $wishlist->items()->count() }} souhait(s)
                                    </p>
                                </div>
                                <span class="text-ink-alt text-sm">&rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Events --}}
        <section class="mt-10">
            <h2 class="text-xl font-title font-semibold text-ink flex items-center gap-2">
                <span class="text-2xl">🎉</span> Mes événements
            </h2>

            @if($events->isEmpty())
                <p class="mt-4 text-ink-alt text-sm">Aucun événement pour le moment.</p>
                <x-button href="{{ route('event.create') }}" variant="outline" class="mt-4">
                    Organiser un cadeau
                </x-button>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($events as $event)
                        <a href="{{ route('event.show', $event) }}"
                           class="block rounded-2xl bg-white p-4 hover:ring-2 hover:ring-accent/30 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-ink">{{ $event->birthday_person_name }}</p>
                                    <p class="text-sm text-ink-alt">
                                        {{ $event->birthday_date->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                                <span class="text-ink-alt text-sm">&rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <div class="mt-12 text-center">
            <a href="{{ route('landing') }}" class="text-sm text-accent hover:underline">&larr; Retour à l'accueil</a>
        </div>
    </div>
</div>

@endsection
