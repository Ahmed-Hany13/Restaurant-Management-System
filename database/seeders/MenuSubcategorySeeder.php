<?php

namespace Database\Seeders;

use App\Models\MenuSubcategory;
use Illuminate\Database\Seeder;

class MenuSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuSubcategory::factory(100)->create();
    }
}
