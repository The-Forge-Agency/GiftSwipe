<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'author_name' => fake()->firstName(),
            'content' => fake()->sentence(),
            'author_token' => Str::uuid()->toString(),
        ];
    }
}
