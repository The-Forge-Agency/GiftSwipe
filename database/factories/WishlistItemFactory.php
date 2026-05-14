<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WishlistItem>
 */
class WishlistItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'wishlist_id' => Wishlist::factory(),
            'name' => fake()->words(3, true),
            'url' => fake()->optional()->url(),
            'price' => fake()->optional()->randomFloat(2, 5, 200),
            'image_url' => null,
            'description' => fake()->optional()->sentence(),
        ];
    }
}
