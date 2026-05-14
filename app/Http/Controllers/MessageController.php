<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:30'],
            'content' => ['required', 'string', 'max:500'],
        ]);

        $token = $request->cookie('giftswipe_author_token') ?? Str::uuid()->toString();

        $event->messages()->create([
            ...$validated,
            'author_token' => $token,
        ]);

        return redirect()
            ->route('event.show', $event)
            ->withCookie(cookie('giftswipe_author_token', $token, 60 * 24 * 365, sameSite: 'Lax'));
    }
}
