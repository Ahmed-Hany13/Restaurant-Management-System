<?php

namespace Database\Factories;

use App\Models\MenuSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuCategory>
 */
class MenuCategoryFactory extends Factory
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
            'menu_section_id' => MenuSection::factory(),
            'description' => fake()->sentence(),
            'display_order' => fake()->randomNumber(2),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
