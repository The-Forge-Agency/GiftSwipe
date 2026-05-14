@extends('layouts.app')

@section('title', 'GiftSwipe — Swipe, vote, offrez.')

@section('content')

{{-- Hero --}}
<section class="text-center px-6 pt-8 pb-16">
    <h1 class="text-4xl sm:text-5xl font-title font-semibold text-ink leading-tight">
        Swipe, vote, offrez.
    </h1>
    <p class="mt-4 text-lg text-ink-alt max-w-md mx-auto">
        Le cadeau de groupe parfait — sans la galère.
    </p>
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
        <x-button href="{{ route('event.create') }}">
            Organiser un cadeau
        </x-button>
        <x-button href="{{ route('wishlist.create') }}" variant="outline">
            Créer ma wishlist
        </x-button>
    </div>
</section>

{{-- 2 façons --}}
<section class="bg-bg-alt py-16 px-6">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-title font-semibold text-ink text-center">
            2 façons d'utiliser GiftSwipe
        </h2>
        <div class="mt-10 grid sm:grid-cols-2 gap-8">
            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent/10 text-2xl">
                    🎉
                </div>
                <h3 class="mt-4 text-lg font-title font-semibold text-ink">Tu organises</h3>
                <ul class="mt-3 space-y-2 text-sm text-ink-alt text-left">
                    <li>1. Crée un événement en 10 secondes</li>
                    <li>2. Ajoute des idées cadeaux</li>
                    <li>3. Partage le lien, tout le monde swipe</li>
                </ul>
                <div class="mt-6">
                    <x-button href="{{ route('event.create') }}" class="w-full">
                        Organiser un cadeau
                    </x-button>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent/10 text-2xl">
                    🎁
                </div>
                <h3 class="mt-4 text-lg font-title font-semibold text-ink">Tu reçois</h3>
                <ul class="mt-3 space-y-2 text-sm text-ink-alt text-left">
                    <li>1. Crée ta wishlist perso</li>
                    <li>2. Ajoute tes envies (auto-remplissage URL)</li>
                    <li>3. Partage le lien à tes proches</li>
                </ul>
                <div class="mt-6">
                    <x-button href="{{ route('wishlist.create') }}" variant="outline" class="w-full">
                        Créer ma wishlist
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Problème --}}
<section class="py-16 px-6">
    <div class="max-w-md mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-title font-semibold text-ink">
            On connaît tous ce scénario
        </h2>
        <div class="mt-8 space-y-3 text-left">
            <p class="text-ink-alt">1 groupe Messenger.</p>
            <p class="text-ink-alt">12 liens Amazon.</p>
            <p class="text-ink-alt">4 avis différents.</p>
            <p class="text-ink font-semibold">0 décision.</p>
            <p class="text-ink-alt">Et l'anniv c'est samedi. Classique.</p>
        </div>
    </div>
</section>

{{-- Comment ça marche --}}
<section class="bg-bg-alt py-16 px-6">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-title font-semibold text-ink text-center">
            Comment ça marche
        </h2>
        <div class="mt-10 grid sm:grid-cols-2 gap-8">
            @foreach([
                ['icon' => '🔗', 'title' => 'Crée un lien', 'desc' => 'En 10 secondes, sans inscription.'],
                ['icon' => '🎁', 'title' => 'Ajoute des idées', 'desc' => 'Nom, lien, prix — même un truc débile.'],
                ['icon' => '📤', 'title' => 'Partage au groupe', 'desc' => "Un lien, c'est tout. Pas d'app à installer."],
                ['icon' => '🏆', 'title' => 'Le top cadeau sort', 'desc' => 'Tout le monde swipe, le favori gagne.'],
            ] as $step)
                <div class="text-center sm:text-left">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent/10 text-2xl">
                        {{ $step['icon'] }}
                    </div>
                    <h3 class="mt-3 text-lg font-medium text-ink">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm text-ink-alt">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 px-6">
    <div class="max-w-md mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-title font-semibold text-ink">
            Fini le mug "Best Friend" acheté la veille à 23h
        </h2>
        <p class="mt-4 text-ink-alt">
            Crée ton événement ou ta wishlist maintenant. C'est gratuit, pour toujours.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <x-button href="{{ route('event.create') }}">
                Organiser un cadeau
            </x-button>
            <x-button href="{{ route('wishlist.create') }}" variant="outline">
                Créer ma wishlist
            </x-button>
        </div>
    </div>
</section>

@endsection
