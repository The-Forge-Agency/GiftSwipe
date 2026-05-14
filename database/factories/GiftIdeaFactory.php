<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\GiftIdea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GiftIdea>
 */
class GiftIdeaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->words(3, true),
            'url' => fake()->optional(0.7)->url(),
            'price' => fake()->optional(0.8)->randomFloat(2, 5, 200),
            'added_by' => fake()->optional(0.5)->firstName(),
        ];
    }
}
