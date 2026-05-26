<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the starter dashboard for authenticated users', function () {
    $user = User::factory()->create();

    Category::factory()->create([
        'name' => 'Sample Category',
        'active' => 1,
    ]);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Starter Dashboard')
        ->assertSee('Sample Category')
        ->assertSee('Starter Kit Includes');
});
