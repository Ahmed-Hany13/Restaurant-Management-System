<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => $this->faker->sentence(3),
        'description' => $this->faker->paragraph(),
        'discount_value' => $this->faker->randomElement([10, 20, 30, 40, 50]),
        'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
        'applicable_items_count' => $this->faker->numberBetween(1, 10),
        'start_date' => $this->faker->date(),
        'end_date' => $this->faker->date(),
        'start_time' => $this->faker->time(),
        'end_time' => $this->faker->time(),
        'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
