@extends('layouts.app')

@section('title', 'Wishlist de ' . $wishlist->person_name . ' — GiftSwipe')

@section('content')

<div class="max-w-lg mx-auto px-6 py-10 w-full">

    <div class="text-center">
        <h1 class="text-3xl font-title font-semibold text-ink">Wishlist de {{ $wishlist->person_name }}</h1>
        <p class="mt-2 text-ink-alt">Anniversaire le {{ $wishlist->birthday_date->translatedFormat('d F Y') }}</p>
    </div>

    <div class="mt-8">
        @if($wishlist->items->isEmpty())
            <div class="rounded-2xl border-2 border-dashed border-ink-alt/30 p-8 text-center">
                <p class="text-2xl">🎁</p>
                <p class="mt-2 font-medium text-ink">Pas encore d'idée dans cette wishlist.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($wishlist->items as $item)
                    <x-wishlist-item :item="$item" />
                @endforeach
            </div>
        @endif
    </div>

    @php
        $totalPrice = $wishlist->items->sum('price');
    @endphp

    @if($totalPrice > 0)
    <div class="mt-8 rounded-2xl bg-cagnotte/10 p-5" x-data="{ participants: 3 }">
        <h2 class="text-lg font-title font-semibold text-ink">Combien par personne ?</h2>
        <p class="mt-1 text-sm text-ink-alt">Total de la wishlist : <strong>{{ number_format($totalPrice, 2, ',', ' ') }} €</strong></p>

        <div class="mt-4 flex items-center gap-4">
            <label class="text-sm text-ink-alt">Participants :</label>
            <div class="flex items-center gap-2">
                <button type="button" @click="participants = Math.max(1, participants - 1)" class="w-8 h-8 rounded-full bg-white border border-ink/10 text-ink font-semibold">−</button>
                <span class="text-lg font-semibold text-ink w-8 text-center" x-text="participants"></span>
                <button type="button" @click="participants = Math.min(20, participants + 1)" class="w-8 h-8 rounded-full bg-white border border-ink/10 text-ink font-semibold">+</button>
            </div>
        </div>

        <p class="mt-3 text-xl font-title font-semibold text-ink">
            ~<span x-text="Math.ceil({{ $totalPrice }} / participants)"></span> € / personne
        </p>
    </div>
    @endif

    <div class="mt-8 rounded-2xl border border-ink/10 p-6">
        <h2 class="text-lg font-title font-semibold text-ink">Organiser un cadeau pour {{ $wishlist->person_name }}</h2>
        <p class="mt-1 text-sm text-ink-alt">Les idées de sa wishlist seront importées automatiquement.</p>

        <form method="POST" action="{{ route('wishlist.create-event', $wishlist) }}" class="mt-4 space-y-4">
            @csrf

            <x-input
                name="organizer_name"
                label="Ton prénom (organisateur)"
                type="text"
                placeholder="Comment tu t'appelles ?"
                value="{{ old('organizer_name') }}"
                :error="$errors->first('organizer_name')"
                required
            />

            <x-input
                name="birthday_date"
                label="Date de l'anniversaire"
                type="date"
                value="{{ old('birthday_date', $wishlist->birthday_date->format('Y-m-d')) }}"
                :error="$errors->first('birthday_date')"
                required
            />

            <x-button class="w-full">Créer l'événement</x-button>
        </form>
    </div>

</div>

@endsection
