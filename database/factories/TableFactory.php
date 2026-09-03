<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_number' => fake()->unique()->randomNumber(3),
            'status' => fake()->randomElement(['available', 'reserved', 'occupied']),
            'type' => fake()->randomElement(['public', 'private']),
            'max_capacity' => fake()->numberBetween(2, 10),
            'min_capacity' => fake()->numberBetween(1, 5),
            'location' => fake()->randomElement(['Ground Floor', 'First Floor','Garden']),
            'notes' => fake()->sentence(),
            'unique_token' => fake()->uuid(),
        ];
    }
}

