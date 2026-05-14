@props(['giftResults', 'maxVotes'])

<div class="space-y-3">
    <h3 class="font-title font-semibold text-ink">Classement</h3>

    @foreach($giftResults as $index => $result)
        <div class="rounded-xl bg-bg-alt p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center h-7 w-7 rounded-full bg-ink/5 text-xs font-semibold text-ink">
                        {{ $index + 1 }}
                    </span>
                    <span class="font-medium text-ink">{{ $result->gift->name }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-ink-alt">{{ $result->positive_votes }} / {{ $result->total_votes }}</span>
                    <span class="font-semibold text-accent">{{ $result->percentage }}%</span>
                </div>
            </div>
            <div class="h-2 rounded-full bg-ink/5 overflow-hidden">
                <div
                    class="h-full rounded-full bg-accent transition-all duration-500"
                    style="width: {{ $maxVotes > 0 ? ($result->positive_votes / $maxVotes) * 100 : 0 }}%"
                ></div>
            </div>
        </div>
    @endforeach
</div>
