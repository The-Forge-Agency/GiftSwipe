@extends('layouts.app')

@section('title', 'Créer un événement — GiftSwipe')

@section('content')

<div class="flex-1 flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-sm">
        <div class="text-center">
            <h1 class="text-3xl font-title font-semibold text-ink">Créer un événement</h1>
            <p class="mt-2 text-ink-alt">2 champs, 10 secondes, zéro prise de tête.</p>
        </div>

        <form method="POST" action="{{ route('event.store') }}" class="mt-10 space-y-6">
            @csrf

            <x-input
                name="birthday_person_name"
                label="C'est l'anniv de qui ?"
                type="text"
                placeholder="Comment s'appelle l'heureux/heureuse ?"
                value="{{ old('birthday_person_name') }}"
                :error="$errors->first('birthday_person_name')"
                required
            />

            <x-input
                name="birthday_date"
                label="Date de l'anniversaire"
                type="date"
                value="{{ old('birthday_date') }}"
                :error="$errors->first('birthday_date')"
                required
            />

            <x-button class="w-full">
                Créer l'événement
            </x-button>
        </form>
    </div>
</div>

@endsection
