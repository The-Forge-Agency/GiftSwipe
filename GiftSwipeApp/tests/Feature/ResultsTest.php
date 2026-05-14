<?php

use App\Models\Event;
use App\Models\GiftIdea;
use App\Models\Participant;
use App\Models\Swipe;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('results page loads', function () {
    $event = Event::factory()->create();

    $this->get(route('event.results', $event))->assertStatus(200);
});

test('shows empty state when no votes', function () {
    $event = Event::factory()->create();

    $this->get(route('event.results', $event))
        ->assertSee('Personne n', false);
});

test('top gift is the one with most positive votes', function () {
    $event = Event::factory()->create();
    $gift1 = GiftIdea::factory()->create(['event_id' => $event->id, 'name' => 'Polaroid']);
    $gift2 = GiftIdea::factory()->create(['event_id' => $event->id, 'name' => 'Coffret thé']);

    $p1 = Participant::factory()->create(['event_id' => $event->id]);
    $p2 = Participant::factory()->create(['event_id' => $event->id]);

    Swipe::create(['participant_id' => $p1->id, 'gift_idea_id' => $gift1->id, 'liked' => true]);
    Swipe::create(['participant_id' => $p2->id, 'gift_idea_id' => $gift1->id, 'liked' => true]);
    Swipe::create(['participant_id' => $p1->id, 'gift_idea_id' => $gift2->id, 'liked' => false]);
    Swipe::create(['participant_id' => $p2->id, 'gift_idea_id' => $gift2->id, 'liked' => true]);

    $this->get(route('event.results', $event))
        ->assertSee('Le peuple a parlé')
        ->assertSee('Polaroid');
});

test('budget total is sum of participant budgets', function () {
    $event = Event::factory()->create();
    $gift = GiftIdea::factory()->create(['event_id' => $event->id, 'price' => 50]);

    $p1 = Participant::factory()->create(['event_id' => $event->id, 'budget_max' => 20]);
    $p2 = Participant::factory()->create(['event_id' => $event->id, 'budget_max' => 30]);

    Swipe::create(['participant_id' => $p1->id, 'gift_idea_id' => $gift->id, 'liked' => true]);

    $this->get(route('event.results', $event))
        ->assertSee('50€ / 50€');
});
