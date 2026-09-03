<?php

use App\Models\Table;
use App\Models\User;

it('creates a reservation and reserves the selected table', function () {
    $user = User::factory()->create(['role' => 'waiter']);

    $table = Table::create([
        'table_number' => 'T-101',
        'type' => 'private',
        'min_capacity' => 2,
        'max_capacity' => 6,
        'status' => 'available',
        'location' => 'Ground Floor',
        'notes' => 'Near window',
    ]);

    $response = actingAs($user)->post(route('reservations.store'), [
        'customer_name' => 'Michael Smith',
        'phone' => '0101234567',
        'guest_count' => 4,
        'reservation_type' => 'scheduled',
        'table_type' => 'private',
        'table_id' => $table->id,
        'reservation_date' => now()->addDay()->toDateString(),
        'reservation_time' => '19:30',
        'duration_hours' => '2',
        'special_occasion' => 'Birthday',
        'notes' => 'Window seat preferred',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('reservations', [
        'customer_name' => 'Michael Smith',
        'phone' => '0101234567',
        'table_id' => $table->id,
    ]);

    $table->refresh();
    expect($table->status)->toBe('reserved');
});
