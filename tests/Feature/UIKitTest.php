<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the ui kit for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ui-kit'))
        ->assertOk()
        ->assertSee('UI Kit')
        ->assertSee('Buttons')
        ->assertSee('Form Controls')
        ->assertSee('Tables')
        ->assertSee('Rich Text');
});
