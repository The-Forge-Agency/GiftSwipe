@extends('layouts.app')

@section('title', 'Résultats — GiftSwipe')

@section('content')

<div class="max-w-lg mx-auto px-6 py-10 w-full">

    @if(!$hasVotes)
        {{-- État vide --}}
        <div class="flex-1 flex items-center justify-center text-center py-16">
            <div>
                <p class="text-4xl">🗳️</p>
                <h2 class="mt-4 text-2xl font-title font-semibold text-ink">Personne n'a encore voté</h2>
                <p class="mt-2 text-ink-alt">Envoie le lien pour que tes amis swipent !</p>
                <div class="mt-6">
                    <x-button href="{{ route('event.show', $event) }}">
                        📤 Retour à l'événement
                    </x-button>
                </div>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-3xl font-title font-semibold text-ink">Le peuple a parlé ! 🎉</h1>
            <p class="mt-2 text-ink-alt">Cadeau pour {{ $event->birthday_person_name }}</p>
        </div>

        <div class="mt-8 space-y-6">
            @if($topGift)
                <x-top-gift :result="$topGift" />

                @if($topGift->gift->price)
                    <x-budget-bar :totalBudget="$totalBudget" :giftPrice="$topGift->gift->price" />
                @endif
            @endif

            <x-ranking :giftResults="$giftResults" :maxVotes="$maxVotes" />

            <x-participants-list :participants="$event->participants" />

            @if($currentParticipant)
                <div class="rounded-xl bg-bg-alt p-4">
                    <h3 class="font-title font-semibold text-ink mb-3">Mon budget</h3>
                    <form method="POST" action="{{ route('event.pledge', $event) }}" class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1">
                            <x-input
                                name="budget_max"
                                type="number"
                                placeholder="Combien tu mets ?"
                                value="{{ old('budget_max', $currentParticipant->budget_max) }}"
                                :error="$errors->first('budget_max')"
                                min="0"
                                step="0.01"
                            />
                        </div>
                        <x-button class="shrink-0">Mettre à jour</x-button>
                    </form>
                </div>
            @endif

            <x-button href="{{ route('event.show', $event) }}" variant="outline" class="w-full">
                ← Retour à l'événement
            </x-button>
        </div>
    @endif

</div>

@endsection
