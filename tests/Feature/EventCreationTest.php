<?php

use App\Models\Event;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('landing page loads', function () {
    $this->get('/')->assertStatus(200);
});

test('create page loads', function () {
    $this->get('/create')->assertStatus(200);
});

test('can create event with valid data', function () {
    $response = $this->post('/create', [
        'birthday_person_name' => 'Léa',
        'birthday_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $event = Event::first();
    expect($event)->not->toBeNull();
    expect($event->birthday_person_name)->toBe('Léa');
    expect(strlen($event->slug))->toBe(8);

    $response->assertRedirect(route('event.show', $event));
});

test('name is required', function () {
    $this->post('/create', [
        'birthday_date' => now()->addDays(7)->format('Y-m-d'),
    ])->assertSessionHasErrors('birthday_person_name');
});

test('name max 50 characters', function () {
    $this->post('/create', [
        'birthday_person_name' => str_repeat('a', 51),
        'birthday_date' => now()->addDays(7)->format('Y-m-d'),
    ])->assertSessionHasErrors('birthday_person_name');
});

test('date is required', function () {
    $this->post('/create', [
        'birthday_person_name' => 'Léa',
    ])->assertSessionHasErrors('birthday_date');
});

test('slug is unique', function () {
    Event::factory()->count(5)->create();

    $slugs = Event::pluck('slug')->toArray();
    expect(count(array_unique($slugs)))->toBe(5);
});
