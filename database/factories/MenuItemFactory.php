<?php

namespace Database\Factories;

use App\Models\MenuSubcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 100),
            'menu_subcategory_id' => MenuSubcategory::factory(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'has_offer' => fake()->boolean(),
            'image' => fake()->imageUrl(),
        ];
    }
}
