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

it('returns category datatable rows for mobile card requests with sparse ordering', function () {
    $user = User::factory()->create();
    Category::factory()->create(['name' => 'Hardware']);

    $this->actingAs($user)
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('categories.index', [
            'draw' => 1,
            'start' => 0,
            'length' => 8,
            'columns' => [
                ['data' => 'select', 'name' => 'select', 'orderable' => 'false', 'searchable' => 'false'],
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'name', 'name' => 'name', 'orderable' => 'true', 'searchable' => 'true'],
            ],
            'order' => [
                ['column' => null, 'dir' => 'desc'],
            ],
        ]))
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Hardware');
});

it('filters category datatable rows', function () {
    $user = User::factory()->create();

    Category::factory()->create([
        'name' => 'Hardware',
        'description' => 'Physical inventory',
        'active' => 1,
        'created_at' => now()->subDays(2),
    ]);

    Category::factory()->create([
        'name' => 'Software',
        'description' => 'Digital licenses',
        'active' => 0,
        'created_at' => now()->subDays(10),
    ]);

    $this->actingAs($user)
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('categories.index', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'q' => 'Hard',
            'active' => 1,
            'created_from' => now()->subDays(3)->toDateString(),
            'created_to' => now()->toDateString(),
            'columns' => [
                ['data' => 'select', 'name' => 'select', 'orderable' => 'false', 'searchable' => 'false'],
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'true', 'searchable' => 'true'],
                ['data' => 'name', 'name' => 'name', 'orderable' => 'true', 'searchable' => 'true'],
            ],
            'order' => [
                ['column' => null, 'dir' => 'desc'],
            ],
        ]))
        ->assertOk()
        ->assertJsonPath('recordsFiltered', 1)
        ->assertJsonPath('data.0.name', 'Hardware');
});

it('returns a rendered category row after create and update', function () {
    $user = User::factory()->create();

    $createResponse = $this->actingAs($user)
        ->postJson(route('categories.storeOrUpdate'), [
            'name' => 'Hardware',
            'description' => 'Physical inventory',
            'active' => 1,
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Category Created Successfully!')
        ->assertJsonPath('item.name', 'Hardware');

    expect($createResponse->json('item.status'))->toContain('Active');
    expect($createResponse->json('item.description'))->toContain('Physical inventory');

    $category = Category::where('name', 'Hardware')->firstOrFail();

    $updateResponse = $this->actingAs($user)
        ->postJson(route('categories.storeOrUpdate'), [
            'id' => $category->id,
            'name' => 'Hardware Updated',
            'description' => 'Updated inventory',
            'active' => 0,
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Category Updated Successfully!')
        ->assertJsonPath('item.id', $category->id)
        ->assertJsonPath('item.name', 'Hardware Updated');

    expect($updateResponse->json('item.status'))->toContain('Inactive');
});

it('returns categories as select2 api options', function () {
    $user = User::factory()->create();
    Category::factory()->create(['name' => 'Hardware']);
    Category::factory()->create(['name' => 'Software']);

    $this->actingAs($user)
        ->getJson(route('categories.options', ['q' => 'Hard']))
        ->assertOk()
        ->assertJsonPath('results.0.text', 'Hardware')
        ->assertJsonPath('pagination.more', false);
});
