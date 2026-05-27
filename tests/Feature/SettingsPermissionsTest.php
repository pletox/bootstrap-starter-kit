<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the settings permissions page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.profile.permissions'))
        ->assertOk()
        ->assertSee('Permissions')
        ->assertSee('App notifications')
        ->assertSee('Camera access')
        ->assertSee('Microphone access')
        ->assertSee('Location access')
        ->assertSee('data-pwa-push-card', false)
        ->assertSee('data-browser-permission="camera"', false)
        ->assertSee('data-browser-permission="microphone"', false)
        ->assertSee('data-browser-permission="geolocation"', false);
});

it('renders only permissions enabled in config', function () {
    config()->set('starter-kit.settings.permissions', [
        'notifications',
        'location',
    ]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.profile.permissions'))
        ->assertOk()
        ->assertSee('App notifications')
        ->assertSee('Location access')
        ->assertSee('data-pwa-push-card', false)
        ->assertSee('data-browser-permission="geolocation"', false)
        ->assertDontSee('Camera access')
        ->assertDontSee('Microphone access')
        ->assertDontSee('data-browser-permission="camera"', false)
        ->assertDontSee('data-browser-permission="microphone"', false);
});
