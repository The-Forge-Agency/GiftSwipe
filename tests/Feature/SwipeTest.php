<?php

use App\Models\Event;
use App\Models\GiftIdea;
use App\Models\Participant;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('swipe page loads', function () {
    $event = Event::factory()->create();

    $this->get(route('swipe.index', $event))->assertStatus(200);
});

test('can join event', function () {
    $event = Event::factory()->create();

    $this->post(route('swipe.join', $event), [
        'name' => 'Marie',
        'budget_max' => 25,
    ])->assertRedirect(route('swipe.index', $event));

    expect($event->participants)->toHaveCount(1);
    expect($event->participants->first()->name)->toBe('Marie');
});

test('can swipe a gift', function () {
    $event = Event::factory()->create();
    $gift = GiftIdea::factory()->create(['event_id' => $event->id]);
    $participant = Participant::factory()->create(['event_id' => $event->id]);

    $this->withSession(["participant_{$event->id}" => $participant->id])
        ->postJson(route('swipe.store', $event), [
            'gift_idea_id' => $gift->id,
            'liked' => true,
        ])->assertOk();

    expect($participant->swipes)->toHaveCount(1);
    expect($participant->swipes->first()->liked)->toBeTrue();
});

test('last swipe marks participant as finished', function () {
    $event = Event::factory()->create();
    $gift = GiftIdea::factory()->create(['event_id' => $event->id]);
    $participant = Participant::factory()->create(['event_id' => $event->id]);

    $response = $this->withSession(["participant_{$event->id}" => $participant->id])
        ->postJson(route('swipe.store', $event), [
            'gift_idea_id' => $gift->id,
            'liked' => false,
        ])->assertOk();

    expect($response->json('finished'))->toBeTrue();
    expect($participant->fresh()->has_finished_swiping)->toBeTrue();
});
