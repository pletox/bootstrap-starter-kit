<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QuickLink;
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
        // User::factory(10)->create();
        //
        //        User::factory()->create([
        //            'name' => 'Test User',
        //            'email' => 'test@example.com',
        //        ]);

        Category::factory(50)->create();

        QuickLink::query()->updateOrCreate(
            ['title' => 'Connect to Forge DB'],
            ['url' => 'https://medium.com/@hayreddintuzel/connecting-laravel-forge-using-heidisql-via-ssh-206febea714f'],
        );

        QuickLink::query()->updateOrCreate(
            ['title' => 'To do list'],
            ['url' => 'https://example.com/product-roadmap'],
        );
    }
}
