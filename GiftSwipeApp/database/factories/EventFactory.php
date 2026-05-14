<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'birthday_person_name' => fake()->firstName(),
            'birthday_date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
