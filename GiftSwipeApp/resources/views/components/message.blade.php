@props(['message'])

<div class="rounded-2xl bg-bg-alt p-4">
    <div class="flex items-baseline justify-between gap-2">
        <p class="font-semibold text-ink text-sm">{{ $message->author_name }}</p>
        <p class="text-xs text-ink-alt shrink-0">{{ $message->created_at->diffForHumans() }}</p>
    </div>
    <p class="mt-1 text-ink text-sm">{{ $message->content }}</p>
</div>
