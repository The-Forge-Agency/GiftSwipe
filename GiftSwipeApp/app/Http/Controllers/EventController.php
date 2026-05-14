<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\StoreGiftIdeaRequest;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('landing');
    }

    public function create(): View
    {
        return view('create');
    }

    public function store(StoreEventRequest $request)
    {
        $event = Event::create($request->validated());

        return redirect()->route('event.show', $event);
    }

    public function show(Request $request, Event $event): View
    {
        $event->load(['giftIdeas', 'messages']);

        $authorToken = $request->cookie('giftswipe_author_token');
        $authorName = null;
        if ($authorToken) {
            $lastMessage = $event->messages->where('author_token', $authorToken)->last();
            $authorName = $lastMessage?->author_name;
        }

        return view('event.show', compact('event', 'authorName'));
    }

    public function storeGift(StoreGiftIdeaRequest $request, Event $event)
    {
        $event->giftIdeas()->create($request->validated());

        return redirect()->route('event.show', $event);
    }

    public function updatePledge(Request $request, Event $event)
    {
        $request->validate([
            'budget_max' => ['required', 'numeric', 'min:0'],
        ]);

        $participantId = $request->session()->get("participant_{$event->id}");
        $participant = Participant::findOrFail($participantId);
        $participant->update(['budget_max' => $request->input('budget_max')]);

        return redirect()->route('event.results', $event);
    }

    public function results(Request $request, Event $event): View
    {
        $event->load(['giftIdeas.swipes', 'participants']);

        $participantId = $request->session()->get("participant_{$event->id}");
        $currentParticipant = $participantId ? Participant::find($participantId) : null;

        $totalBudget = $event->participants->sum('budget_max');

        $giftResults = $event->giftIdeas->map(function ($gift) {
            $positiveVotes = $gift->swipes->where('liked', true)->count();
            $totalVotes = $gift->swipes->count();

            return (object) [
                'gift' => $gift,
                'positive_votes' => $positiveVotes,
                'total_votes' => $totalVotes,
                'percentage' => $totalVotes > 0 ? round(($positiveVotes / $totalVotes) * 100) : 0,
            ];
        })->sortByDesc('positive_votes')->values();

        $topGift = $giftResults->first();
        $maxVotes = $giftResults->max('positive_votes');
        $hasVotes = $giftResults->sum('total_votes') > 0;

        return view('results', compact('event', 'giftResults', 'topGift', 'totalBudget', 'maxVotes', 'hasVotes', 'currentParticipant'));
    }
}
