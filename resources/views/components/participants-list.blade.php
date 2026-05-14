@props(['participants'])

@if($participants->isNotEmpty())
    <div class="rounded-xl bg-bg-alt p-4">
        <h3 class="font-title font-semibold text-ink mb-3">Participants ({{ $participants->count() }})</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($participants as $participant)
                <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-sm font-medium text-accent">
                    {{ $participant->name }}@if($participant->budget_max) · {{ number_format($participant->budget_max, 0) }}€@endif
                </span>
            @endforeach
        </div>
    </div>
@endif
