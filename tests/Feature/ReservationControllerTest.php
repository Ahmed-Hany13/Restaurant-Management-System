<?php

use App\Models\Table;
use App\Models\User;

test('scheduled reservations accept string date values', function () {
    $user = User::factory()->create();
    $table = Table::factory()->create([
        'status' => 'available',
        'min_capacity' => 1,
        'max_capacity' => 4,
        'type' => 'public',
    ]);

    $response = $this->actingAs($user)->post(route('reservations.store'), [
        'customer_name' => 'Jane Doe',
        'phone' => '1234567890',
        'guest_count' => 2,
        'reservation_type' => 'scheduled',
        'table_id' => $table->id,
        'reservation_date' => '2026-07-20',
        'reservation_time' => '19:00',
        'duration_hours' => '1',
        'special_occasion' => 'None',
        'notes' => 'Test reservation',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('reservations', [
        'customer_name' => 'Jane Doe',
        'table_id' => $table->id,
    ]);
});
