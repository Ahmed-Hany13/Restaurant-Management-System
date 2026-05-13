<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuSubcategory>
 */
class MenuSubcategoryFactory extends Factory
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
            'menu_category_id' => MenuCategory::factory(),
            'item_count' => fake()->randomNumber(2),
            'display_order' => fake()->randomNumber(2),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
