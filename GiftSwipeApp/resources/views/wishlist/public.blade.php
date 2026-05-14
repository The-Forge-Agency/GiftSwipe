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

    <div class="mt-8">
        <form method="POST" action="{{ route('wishlist.create-event', $wishlist) }}">
            @csrf
            <x-button class="w-full">
                Organiser un cadeau pour {{ $wishlist->person_name }}
            </x-button>
        </form>
    </div>

</div>

@endsection
