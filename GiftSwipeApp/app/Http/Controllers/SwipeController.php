<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinEventRequest;
use App\Http\Requests\StoreSwipeRequest;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SwipeController extends Controller
{
    public function index(Request $request, Event $event): View|\Illuminate\Http\RedirectResponse
    {
        $participantId = $request->session()->get("participant_{$event->id}");
        $participant = $participantId ? Participant::find($participantId) : null;

        if ($participant && $participant->has_finished_swiping) {
            return redirect()->route('event.results', $event);
        }

        $remainingGifts = collect();
        $giftsJson = [];

        if ($participant) {
            $swipedIds = $participant->swipes()->pluck('gift_idea_id');
            $remainingGifts = $event->giftIdeas()->whereNotIn('id', $swipedIds)->get();
            $giftsJson = $remainingGifts->map(function ($g) {
                return [
                    'id' => $g->id,
                    'name' => $g->name,
                    'url' => $g->url,
                    'image_url' => $g->image_url,
                    'price' => $g->price ? number_format((float) $g->price, 0, '.', '') : null,
                ];
            })->values()->all();
        }

        return view('swipe.index', compact('event', 'participant', 'remainingGifts', 'giftsJson'));
    }

    public function join(JoinEventRequest $request, Event $event)
    {
        $participant = $event->participants()->create($request->validated());

        $request->session()->put("participant_{$event->id}", $participant->id);

        return redirect()->route('swipe.index', $event);
    }

    public function store(StoreSwipeRequest $request, Event $event): JsonResponse
    {
        $participantId = $request->session()->get("participant_{$event->id}");
        $participant = Participant::findOrFail($participantId);

        $participant->swipes()->create($request->validated());

        $totalGifts = $event->giftIdeas()->count();
        $swipedCount = $participant->swipes()->count();

        $finished = $swipedCount >= $totalGifts;

        if ($finished) {
            $participant->update(['has_finished_swiping' => true]);
        }

        return response()->json([
            'finished' => $finished,
            'redirect' => $finished ? route('event.results', $event) : null,
        ]);
    }
}
