<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => fake()->unique()->randomNumber(5),
            'table_id' => Table::factory(),
            'total_price' => fake()->randomFloat(2, 10, 100),
            'status' => fake()->randomElement(['pending', 'in preparation', 'completed']),
            'menu_item_id' => MenuItem::factory(),
            'quantity' => fake()->numberBetween(1, 10),
            'customer_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'guest_count' => fake()->numberBetween(1, 20),
            'unit_price' => fake()->randomFloat(2, 5, 50),
            'offer_applied' => fake()->boolean(),
            'discount_amount' => fake()->randomFloat(2, 0, 20),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
