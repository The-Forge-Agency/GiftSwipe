<?php

use App\Models\Event;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('event page loads', function () {
    $event = Event::factory()->create();

    $this->get(route('event.show', $event))->assertStatus(200);
});

test('can add gift idea with name only', function () {
    $event = Event::factory()->create();

    $this->post(route('event.store-gift', $event), [
        'name' => 'Coffret thé',
    ])->assertRedirect(route('event.show', $event));

    expect($event->giftIdeas)->toHaveCount(1);
});

test('can add gift idea with all fields', function () {
    $event = Event::factory()->create();

    $this->post(route('event.store-gift', $event), [
        'name' => 'Polaroid',
        'url' => 'https://example.com/polaroid',
        'price' => 79.99,
    ])->assertRedirect(route('event.show', $event));

    $gift = $event->giftIdeas()->first();
    expect($gift->name)->toBe('Polaroid');
    expect($gift->url)->toBe('https://example.com/polaroid');
    expect((float) $gift->price)->toBe(79.99);
});

test('name is required for gift idea', function () {
    $event = Event::factory()->create();

    $this->post(route('event.store-gift', $event), [
        'url' => 'https://example.com',
    ])->assertSessionHasErrors('name');
});

test('invalid slug returns 404', function () {
    $this->get('/zzzzzzzz')->assertStatus(404);
});
