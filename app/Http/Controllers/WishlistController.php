<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Services\UrlScraperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $token = $request->cookie('giftswipe_owner_token') ?? Str::uuid()->toString();

        $wishlist = Wishlist::create([...$validated, 'owner_token' => $token]);

        return redirect()
            ->route('wishlist.private', $wishlist->private_slug)
            ->withCookie(cookie('giftswipe_owner_token', $token, 60 * 24 * 365, sameSite: 'Lax'));
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

        if ($request->url) {
            $request->merge(['url' => UrlScraperService::cleanUrl($request->url)]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:2048'],
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

    public function createEvent(Request $request, Wishlist $wishlist): RedirectResponse
    {
        $validated = $request->validate([
            'organizer_name' => ['required', 'string', 'max:50'],
            'birthday_date' => ['required', 'date'],
        ]);

        $token = $request->cookie('giftswipe_owner_token') ?? Str::uuid()->toString();

        $event = Event::create([
            'birthday_person_name' => $wishlist->person_name,
            'birthday_date' => $validated['birthday_date'],
            'wishlist_id' => $wishlist->id,
            'owner_token' => $token,
        ]);

        foreach ($wishlist->items as $item) {
            $event->giftIdeas()->create([
                'name' => $item->name,
                'url' => $item->url,
                'price' => $item->price,
                'image_url' => $item->image_url,
                'description' => $item->description,
            ]);
        }

        return redirect()
            ->route('event.show', $event)
            ->withCookie(cookie('giftswipe_owner_token', $token, 60 * 24 * 365, sameSite: 'Lax'));
    }

    public function mySpaces(Request $request): View
    {
        $token = $request->cookie('giftswipe_owner_token');

        $wishlists = $token ? Wishlist::where('owner_token', $token)->latest()->get() : collect();
        $events = $token ? Event::where('owner_token', $token)->latest()->get() : collect();

        return view('wishlist.my-spaces', compact('wishlists', 'events'));
    }
}
