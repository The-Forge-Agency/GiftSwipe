<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->firstName(),
            'budget_max' => fake()->optional(0.8)->randomFloat(2, 10, 100),
            'has_finished_swiping' => false,
        ];
    }
}
