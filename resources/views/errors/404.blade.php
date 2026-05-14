@extends('layouts.app')

@section('title', 'Page introuvable — GiftSwipe')

@section('content')

<div class="flex-1 flex items-center justify-center px-6 py-16">
    <div class="text-center">
        <p class="text-5xl">🤷</p>
        <h1 class="mt-4 text-3xl font-title font-semibold text-ink">Cette page n'existe pas</h1>
        <p class="mt-2 text-ink-alt">T'es sûr du lien ?</p>
        <div class="mt-6">
            <x-button href="/">Retour à l'accueil</x-button>
        </div>
    </div>
</div>

@endsection
