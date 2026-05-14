@extends('layouts.app')

@section('title', 'Créer ma wishlist — GiftSwipe')

@section('content')

<div class="flex-1 flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-sm">
        <div class="text-center">
            <h1 class="text-3xl font-title font-semibold text-ink">Créer ma wishlist</h1>
            <p class="mt-2 text-ink-alt">Ta liste de souhaits perso, partageable en un clic.</p>
        </div>

        <form method="POST" action="{{ route('wishlist.store') }}" class="mt-10 space-y-6">
            @csrf

            <x-input
                name="person_name"
                label="Ton prénom"
                type="text"
                placeholder="Comment tu t'appelles ?"
                value="{{ old('person_name') }}"
                :error="$errors->first('person_name')"
                required
            />

            <x-input
                name="birthday_date"
                label="Date de ton anniversaire"
                type="date"
                value="{{ old('birthday_date') }}"
                :error="$errors->first('birthday_date')"
                required
            />

            <x-button class="w-full">
                Créer ma wishlist
            </x-button>
        </form>
    </div>
</div>

@endsection
