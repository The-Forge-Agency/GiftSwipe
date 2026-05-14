<?php

namespace Database\Factories;

use App\Models\GiftIdea;
use App\Models\Participant;
use App\Models\Swipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Swipe>
 */
class SwipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'participant_id' => Participant::factory(),
            'gift_idea_id' => GiftIdea::factory(),
            'liked' => fake()->boolean(60),
        ];
    }
}
