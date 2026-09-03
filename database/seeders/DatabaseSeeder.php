<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123Aa'),
        ]);
        $this->call([
            MenuSectionSeeder::class,
            MenuCategorySeeder::class,
            MenuSubcategorySeeder::class,
            MenuItemSeeder::class,
            OfferSeeder::class,
            TableSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
