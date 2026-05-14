<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\GiftIdea;
use App\Models\Participant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $event = Event::create([
            'slug' => 'demo1234',
            'birthday_person_name' => 'Léa',
            'birthday_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        GiftIdea::create([
            'event_id' => $event->id,
            'name' => 'Coffret dégustation de thés',
            'url' => 'https://example.com/coffret-the',
            'price' => 35.00,
        ]);

        GiftIdea::create([
            'event_id' => $event->id,
            'name' => 'Polaroid Go Gen 2',
            'url' => 'https://example.com/polaroid',
            'price' => 79.99,
        ]);

        GiftIdea::create([
            'event_id' => $event->id,
            'name' => 'Cours de poterie',
            'price' => 45.00,
        ]);

        Participant::create([
            'event_id' => $event->id,
            'name' => 'Marie',
            'budget_max' => 30.00,
        ]);

        Participant::create([
            'event_id' => $event->id,
            'name' => 'Hugo',
            'budget_max' => 25.00,
        ]);
    }
}
