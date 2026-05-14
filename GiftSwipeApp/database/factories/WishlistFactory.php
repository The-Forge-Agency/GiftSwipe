<?php

namespace Database\Factories;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wishlist>
 */
class WishlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'person_name' => fake()->firstName(),
            'birthday_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
