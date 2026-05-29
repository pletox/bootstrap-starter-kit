<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the ui kit locally for authenticated users', function () {
    $this->app->detectEnvironment(fn () => 'local');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ui-kit'))
        ->assertOk()
        ->assertSee('UI Kit')
        ->assertSee('Buttons')
        ->assertSee('Form Controls')
        ->assertSee('Push Notification Tester')
        ->assertSee('data-push-test-status', false)
        ->assertSee('Tables')
        ->assertSee('Rich Text');
});

it('serves the web app manifest', function () {
    $this->get(route('pwa.manifest'))
        ->assertOk()
        ->assertHeader('content-type', 'application/manifest+json')
        ->assertJsonPath('display', 'standalone')
        ->assertJsonPath('theme_color', '#a6192e')
        ->assertJsonPath('icons.0.sizes', '192x192')
        ->assertJsonPath('icons.2.purpose', 'maskable');
});

it('renders the install app page', function () {
    $this
        ->get(route('install-app'))
        ->assertOk()
        ->assertSee('Install App')
        ->assertSee('Checking install support')
        ->assertSee('Add to Home Screen')
        ->assertSee('No app store needed')
        ->assertSee('Share button');
});

it('hides the ui kit outside local environments', function () {
    $this->app->detectEnvironment(fn () => 'production');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ui-kit'))
        ->assertNotFound();
});

it('renders developer docs locally', function () {
    $this->app->detectEnvironment(fn () => 'local');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('developer-docs'))
        ->assertOk()
        ->assertSee('Developer Docs')
        ->assertSee('How To Build In This Kit')
        ->assertSee('Infinite Scroll')
        ->assertSee('PWA &amp; Push', false)
        ->assertSee('Recommended Build Order');
});

it('hides developer docs outside local environments', function () {
    $this->app->detectEnvironment(fn () => 'production');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('developer-docs'))
        ->assertNotFound();
});

it('renders developer docs sub pages locally', function (string $page, string $expectedText) {
    $this->app->detectEnvironment(fn () => 'local');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('developer-docs', ['page' => $page]))
        ->assertOk()
        ->assertSee($expectedText);
})->with([
    ['components', 'Component Rules'],
    ['forms', 'AJAX CRUD Pattern'],
    ['datatables', 'Standard DataTable'],
    ['infinite-scroll', 'Page Scroll Lists'],
    ['pwa', 'Production Push Setup'],
    ['backend', 'Backend Patterns'],
    ['testing', 'Testing And Verification'],
]);
