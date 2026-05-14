<?php

use App\Models\Event;
use App\Models\Message;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('posts a message on an event', function () {
    $event = Event::factory()->create();

    $this->post(route('event.store-message', $event), [
        'author_name' => 'Julie',
        'content' => 'On prend le cadeau du haut !',
    ])->assertRedirect(route('event.show', $event));

    $this->assertDatabaseHas('messages', [
        'event_id' => $event->id,
        'author_name' => 'Julie',
        'content' => 'On prend le cadeau du haut !',
    ]);
});

it('validates message fields', function () {
    $event = Event::factory()->create();

    $this->post(route('event.store-message', $event), [])
        ->assertSessionHasErrors(['author_name', 'content']);
});

it('shows messages on the event page', function () {
    $event = Event::factory()->create();
    Message::factory()->create([
        'event_id' => $event->id,
        'author_name' => 'Marc',
        'content' => 'Super idée les gars',
    ]);

    $this->get(route('event.show', $event))
        ->assertOk()
        ->assertSee('Marc')
        ->assertSee('Super idée les gars');
});

it('sets author cookie on first message', function () {
    $event = Event::factory()->create();

    $response = $this->post(route('event.store-message', $event), [
        'author_name' => 'Sophie',
        'content' => 'Hello !',
    ]);

    $response->assertCookie('giftswipe_author_token');
});
