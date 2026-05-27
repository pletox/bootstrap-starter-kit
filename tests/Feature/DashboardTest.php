<?php

use App\Models\Category;
use App\Models\QuickLink;
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
        ->assertSee('data-home-counters', false)
        ->assertSee('data-home-category-list', false)
        ->assertSee('data-quick-link-list', false)
        ->assertSee('App notifications')
        ->assertSee('data-pwa-push-card', false)
        ->assertSee('Starter Kit Includes');
});

it('loads dashboard counters after page load', function () {
    $user = User::factory()->create();

    Category::factory()->count(2)->create(['active' => 1]);
    Category::factory()->create(['active' => 0]);

    $this->actingAs($user)
        ->postJson(route('home.counters'))
        ->assertOk()
        ->assertJsonPath('counters.total', 3)
        ->assertJsonPath('counters.active', 2)
        ->assertJsonPath('counters.inactive', 1);
});

it('loads recent categories as a paginated dashboard list', function () {
    $user = User::factory()->create();

    Category::factory()->count(7)->sequence(
        ['name' => 'Category 1', 'active' => 1],
        ['name' => 'Category 2', 'active' => 0],
        ['name' => 'Category 3', 'active' => 1],
        ['name' => 'Category 4', 'active' => 0],
        ['name' => 'Category 5', 'active' => 1],
        ['name' => 'Category 6', 'active' => 0],
        ['name' => 'Category 7', 'active' => 1],
    )->create();

    $this->actingAs($user)
        ->postJson(route('home.recent-categories'), ['page' => 1])
        ->assertOk()
        ->assertJsonCount(6, 'items')
        ->assertJsonPath('pagination.current_page', 1)
        ->assertJsonPath('pagination.next_page', 2)
        ->assertJsonPath('pagination.has_more', true);

    $this->actingAs($user)
        ->postJson(route('home.recent-categories'), ['page' => 2])
        ->assertOk()
        ->assertJsonCount(1, 'items')
        ->assertJsonPath('pagination.current_page', 2)
        ->assertJsonPath('pagination.next_page', null)
        ->assertJsonPath('pagination.has_more', false);
});

it('manages dashboard quick links through ajax crud endpoints', function () {
    $user = User::factory()->create();

    QuickLink::factory()->count(6)->create();

    $this->actingAs($user)
        ->postJson(route('quick-links.index'), ['page' => 1])
        ->assertOk()
        ->assertJsonCount(5, 'items')
        ->assertJsonPath('pagination.next_page', 2)
        ->assertJsonPath('pagination.has_more', true);

    $this->actingAs($user)
        ->postJson(route('quick-links.storeOrUpdate'), [
            'title' => 'Connect to Forge DB',
            'url' => 'https://medium.com/example',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Quick link created successfully.')
        ->assertJsonPath('item.title', 'Connect to Forge DB')
        ->assertJsonPath('item.url', 'https://medium.com/example');

    $quickLink = QuickLink::where('title', 'Connect to Forge DB')->first();

    expect($quickLink)->not->toBeNull();

    $this->actingAs($user)
        ->getJson(route('quick-links.edit', $quickLink))
        ->assertOk()
        ->assertJsonPath('title', 'Connect to Forge DB');

    $this->actingAs($user)
        ->postJson(route('quick-links.storeOrUpdate'), [
            'id' => $quickLink->id,
            'title' => 'Updated Forge DB',
            'url' => 'https://example.com/forge',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Quick link updated successfully.')
        ->assertJsonPath('item.id', $quickLink->id)
        ->assertJsonPath('item.title', 'Updated Forge DB')
        ->assertJsonPath('item.url', 'https://example.com/forge');

    $this->actingAs($user)
        ->deleteJson(route('quick-links.delete', $quickLink))
        ->assertOk()
        ->assertJsonPath('message', 'Quick link deleted successfully.');

    $this->assertDatabaseMissing('quick_links', [
        'id' => $quickLink->id,
    ]);
});
