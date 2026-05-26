<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('applies bulk status updates and deletes selected categories', function () {
    $user = User::factory()->create();
    $categories = Category::factory()->count(2)->create(['active' => 1]);

    $this->actingAs($user)
        ->postJson(route('categories.bulk-action'), [
            'action' => 'deactivate',
            'ids' => $categories->pluck('id')->all(),
        ])
        ->assertOk()
        ->assertJson(['message' => '2 categories marked inactive.']);

    expect(Category::where('active', 0)->count())->toBe(2);

    $this->actingAs($user)
        ->postJson(route('categories.bulk-action'), [
            'action' => 'activate',
            'ids' => $categories->pluck('id')->all(),
        ])
        ->assertOk()
        ->assertJson(['message' => '2 categories marked active.']);

    expect(Category::where('active', 1)->count())->toBe(2);

    $this->actingAs($user)
        ->postJson(route('categories.bulk-action'), [
            'action' => 'delete',
            'ids' => $categories->pluck('id')->all(),
        ])
        ->assertOk()
        ->assertJson(['message' => '2 categories deleted successfully.']);

    expect(Category::count())->toBe(0);
});

it('exports selected categories as a csv download', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create([
        'name' => 'Hardware',
        'description' => 'Physical inventory',
        'active' => 1,
    ]);

    $response = $this->actingAs($user)
        ->post(route('categories.export'), [
            'ids' => [$category->id],
        ]);

    $response->assertOk();
    $response->assertDownload('categories.csv');

    expect($response->streamedContent())
        ->toContain('Hardware')
        ->toContain('Physical inventory')
        ->toContain('Active');
});
