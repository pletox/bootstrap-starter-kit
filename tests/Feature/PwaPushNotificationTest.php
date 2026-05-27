<?php

use App\Models\PushSubscription;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'services.webpush.subject' => 'mailto:test@example.com',
        'services.webpush.public_key' => 'public-key',
        'services.webpush.private_key' => 'private-key',
    ]);
});

it('returns the configured public vapid key', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('pwa.push.public-key'))
        ->assertOk()
        ->assertJson([
            'enabled' => true,
            'publicKey' => 'public-key',
        ]);
});

it('stores a push subscription for the current user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('pwa.push.subscribe'), pushSubscriptionPayload())
        ->assertOk()
        ->assertJsonPath('message', 'Push notifications enabled.');

    expect($user->pushSubscriptions()->first())
        ->endpoint->toBe('https://push.example.com/send/123')
        ->public_key->toBe('client-public-key')
        ->auth_token->toBe('client-auth-token')
        ->content_encoding->toBe('aes128gcm');
});

it('moves an existing browser subscription to the current user', function () {
    $oldUser = User::factory()->create();
    $newUser = User::factory()->create();

    PushSubscription::query()->create([
        'user_id' => $oldUser->id,
        'endpoint' => 'https://push.example.com/send/123',
        'public_key' => 'old-client-public-key',
        'auth_token' => 'old-client-auth-token',
        'content_encoding' => 'aesgcm',
    ]);

    $this->actingAs($newUser)
        ->postJson(route('pwa.push.subscribe'), pushSubscriptionPayload())
        ->assertOk();

    expect(PushSubscription::query()->count())->toBe(1)
        ->and(PushSubscription::query()->first())
        ->user_id->toBe($newUser->id)
        ->public_key->toBe('client-public-key');
});

it('removes the current users push subscription', function () {
    $user = User::factory()->create();
    PushSubscription::query()->create([
        'user_id' => $user->id,
        'endpoint' => 'https://push.example.com/send/123',
        'public_key' => 'client-public-key',
        'auth_token' => 'client-auth-token',
        'content_encoding' => 'aes128gcm',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('pwa.push.unsubscribe'), [
            'endpoint' => 'https://push.example.com/send/123',
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Push notifications disabled.');

    expect($user->pushSubscriptions()->count())->toBe(0);
});

it('sends a test push notification with a deep link', function () {
    $user = User::factory()->create();

    mock(WebPushService::class, function (MockInterface $mock) {
        $mock->shouldReceive('payload')
            ->once()
            ->andReturnUsing(fn (array $message): array => [
                'title' => $message['title'],
                'body' => $message['body'],
                'url' => $message['url'],
                'icon' => $message['icon'],
                'badge' => $message['badge'],
                'tag' => $message['tag'],
                'timestamp' => now()->timestamp,
            ]);

        $mock->shouldReceive('sendToUser')
            ->once()
            ->with(Mockery::type(User::class), Mockery::on(fn (array $payload): bool => $payload['url'] === url('/categories')
                && $payload['title'] === 'Custom title'
                && $payload['icon'] === url('/pwa/icons/icon-512x512.png')
                && $payload['badge'] === url('/pwa/icons/icon-96x96.png')
                && $payload['tag'] === 'custom-tag'))
            ->andReturn(1);
    });

    $this->actingAs($user)
        ->postJson(route('pwa.push.test'), [
            'title' => 'Custom title',
            'body' => 'Custom body',
            'url' => '/categories',
            'icon' => '/pwa/icons/icon-512x512.png',
            'badge' => '/pwa/icons/icon-96x96.png',
            'tag' => 'custom-tag',
        ])
        ->assertOk()
        ->assertJson([
            'message' => 'Test notification sent.',
            'sent' => 1,
        ])
        ->assertJsonPath('notification.url', url('/categories'))
        ->assertJsonPath('notification.tag', 'custom-tag');
});

function pushSubscriptionPayload(): array
{
    return [
        'endpoint' => 'https://push.example.com/send/123',
        'keys' => [
            'p256dh' => 'client-public-key',
            'auth' => 'client-auth-token',
        ],
        'contentEncoding' => 'aes128gcm',
    ];
}
