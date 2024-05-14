<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\menu;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // Category::factory()->create([
        //     'name' => 'Food'
        // ]);
        // Category::factory()->create([
        //     'name' => 'American0'
        // ]);
        // Category::factory()->create([
        //     'name' => 'Latte'
        // ]);
        // Category::factory()->create([
        //     'name' => 'Non Coffe'
        // ]);

        // Menu::factory(10)->create();
    }
}
