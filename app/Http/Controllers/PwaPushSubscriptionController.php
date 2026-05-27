<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyPushSubscriptionRequest;
use App\Http\Requests\SendTestPushNotificationRequest;
use App\Http\Requests\StorePushSubscriptionRequest;
use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PwaPushSubscriptionController extends Controller
{
    public function publicKey(WebPushService $webPushService): JsonResponse
    {
        return response()->json([
            'enabled' => $webPushService->isConfigured(),
            'publicKey' => $webPushService->publicKey(),
        ]);
    }

    public function store(StorePushSubscriptionRequest $request, WebPushService $webPushService): JsonResponse
    {
        abort_unless($webPushService->isConfigured(), 422, 'Push notifications are not configured.');

        $data = $request->validated();

        PushSubscription::query()->updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => $request->user()->id,
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'content_encoding' => $data['contentEncoding'] ?? 'aes128gcm',
                'user_agent' => Str::limit($request->userAgent() ?? '', 255, ''),
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Push notifications enabled.',
        ]);
    }

    public function destroy(DestroyPushSubscriptionRequest $request): JsonResponse
    {
        $request->user()
            ->pushSubscriptions()
            ->where('endpoint', $request->validated('endpoint'))
            ->delete();

        return response()->json([
            'message' => 'Push notifications disabled.',
        ]);
    }

    public function test(SendTestPushNotificationRequest $request, WebPushService $webPushService): JsonResponse
    {
        $data = $request->validated();
        $notification = $webPushService->payload([
            'title' => $data['title'] ?? config('app.name'),
            'body' => $data['body'] ?? 'This is a test push notification.',
            'url' => $this->deepLinkUrl($data['url'] ?? route('home')),
            'icon' => $this->assetUrl($data['icon'] ?? asset('pwa/icons/icon-192x192.png'), asset('pwa/icons/icon-192x192.png')),
            'badge' => $this->assetUrl($data['badge'] ?? asset('pwa/icons/icon-96x96.png'), asset('pwa/icons/icon-96x96.png')),
            'tag' => $data['tag'] ?? 'pwa-test-notification',
        ]);

        $sent = $webPushService->sendToUser($request->user(), $notification);

        return response()->json([
            'message' => $sent > 0
                ? 'Test notification sent.'
                : 'No active push subscriptions were found.',
            'notification' => $notification,
            'sent' => $sent,
        ]);
    }

    private function deepLinkUrl(string $url): string
    {
        if (Str::startsWith($url, url('/'))) {
            return $url;
        }

        if (Str::startsWith($url, '/')) {
            return url($url);
        }

        return route('home');
    }

    private function assetUrl(string $url, string $fallback): string
    {
        if (Str::startsWith($url, ['https://', 'http://'])) {
            return $url;
        }

        if (Str::startsWith($url, url('/'))) {
            return $url;
        }

        if (Str::startsWith($url, '/')) {
            return url($url);
        }

        return $fallback;
    }
}
