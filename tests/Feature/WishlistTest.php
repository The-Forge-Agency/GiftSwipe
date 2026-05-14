<?php

use App\Models\Wishlist;
use App\Models\WishlistItem;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows the wishlist creation page', function () {
    $this->get(route('wishlist.create'))
        ->assertOk()
        ->assertSee('Créer ma wishlist');
});

it('creates a wishlist', function () {
    $this->post(route('wishlist.store'), [
        'person_name' => 'Alice',
        'birthday_date' => '2026-12-25',
    ])->assertRedirect();

    $this->assertDatabaseHas('wishlists', ['person_name' => 'Alice']);
});

it('validates wishlist creation', function () {
    $this->post(route('wishlist.store'), [])
        ->assertSessionHasErrors(['person_name', 'birthday_date']);
});

it('shows the public wishlist', function () {
    $wishlist = Wishlist::factory()->create(['person_name' => 'Bob']);
    WishlistItem::factory()->create(['wishlist_id' => $wishlist->id, 'name' => 'Un super truc']);

    $this->get(route('wishlist.public', $wishlist))
        ->assertOk()
        ->assertSee('Wishlist de Bob')
        ->assertSee('Un super truc');
});

it('shows the private wishlist', function () {
    $wishlist = Wishlist::factory()->create();

    $this->get(route('wishlist.private', $wishlist->private_slug))
        ->assertOk()
        ->assertSee('Ma wishlist');
});

it('adds an item to a wishlist', function () {
    $wishlist = Wishlist::factory()->create();

    $this->post(route('wishlist.store-item', $wishlist->private_slug), [
        'name' => 'Nintendo Switch',
        'price' => 299.99,
    ])->assertRedirect();

    $this->assertDatabaseHas('wishlist_items', [
        'wishlist_id' => $wishlist->id,
        'name' => 'Nintendo Switch',
    ]);
});

it('deletes an item from a wishlist', function () {
    $wishlist = Wishlist::factory()->create();
    $item = WishlistItem::factory()->create(['wishlist_id' => $wishlist->id]);

    $this->delete(route('wishlist.destroy-item', [$wishlist->private_slug, $item]))
        ->assertRedirect();

    $this->assertDatabaseMissing('wishlist_items', ['id' => $item->id]);
});

it('creates an event from a wishlist', function () {
    $wishlist = Wishlist::factory()->create(['person_name' => 'Charlie']);
    WishlistItem::factory()->count(2)->create(['wishlist_id' => $wishlist->id]);

    $this->post(route('wishlist.create-event', $wishlist))
        ->assertRedirect();

    $this->assertDatabaseHas('events', [
        'birthday_person_name' => 'Charlie',
        'wishlist_id' => $wishlist->id,
    ]);

    expect($wishlist->fresh()->events->first()->giftIdeas)->toHaveCount(2);
});

it('generates unique slugs', function () {
    $w1 = Wishlist::factory()->create();
    $w2 = Wishlist::factory()->create();

    expect($w1->public_slug)->not->toBe($w2->public_slug);
    expect($w1->private_slug)->not->toBe($w2->private_slug);
    expect(strlen($w1->public_slug))->toBe(8);
    expect(strlen($w1->private_slug))->toBe(12);
});
