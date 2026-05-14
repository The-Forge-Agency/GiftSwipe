<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function create(): View
    {
        return view('wishlist.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'person_name' => ['required', 'string', 'max:50'],
            'birthday_date' => ['required', 'date'],
        ]);

        $wishlist = Wishlist::create($validated);

        return redirect()->route('wishlist.private', $wishlist->private_slug);
    }

    public function showPublic(Wishlist $wishlist): View
    {
        $wishlist->load('items');

        return view('wishlist.public', compact('wishlist'));
    }

    public function showPrivate(string $privateSlug): View
    {
        $wishlist = Wishlist::where('private_slug', $privateSlug)->firstOrFail();
        $wishlist->load('items');

        return view('wishlist.private', compact('wishlist'));
    }

    public function storeItem(Request $request, string $privateSlug): RedirectResponse
    {
        $wishlist = Wishlist::where('private_slug', $privateSlug)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $wishlist->items()->create($validated);

        return redirect()->route('wishlist.private', $privateSlug);
    }

    public function destroyItem(string $privateSlug, WishlistItem $item): RedirectResponse
    {
        $wishlist = Wishlist::where('private_slug', $privateSlug)->firstOrFail();

        if ($item->wishlist_id !== $wishlist->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('wishlist.private', $privateSlug);
    }

    public function createEvent(Wishlist $wishlist): RedirectResponse
    {
        $event = Event::create([
            'birthday_person_name' => $wishlist->person_name,
            'birthday_date' => $wishlist->birthday_date,
            'wishlist_id' => $wishlist->id,
        ]);

        foreach ($wishlist->items as $item) {
            $event->giftIdeas()->create([
                'name' => $item->name,
                'url' => $item->url,
                'price' => $item->price,
                'image_url' => $item->image_url,
            ]);
        }

        return redirect()->route('event.show', $event);
    }
}
