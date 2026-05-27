<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use RuntimeException;

class WebPushService
{
    public function isConfigured(): bool
    {
        return filled(config('services.webpush.subject'))
            && filled(config('services.webpush.public_key'))
            && filled(config('services.webpush.private_key'));
    }

    public function publicKey(): ?string
    {
        return config('services.webpush.public_key');
    }

    /**
     * @param  array{title?: string, body?: string, url?: string, icon?: string, badge?: string, tag?: string}  $message
     */
    public function sendToUser(User $user, array $message): int
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Web Push VAPID keys are not configured.');
        }

        $sent = 0;
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.webpush.subject'),
                'publicKey' => config('services.webpush.public_key'),
                'privateKey' => config('services.webpush.private_key'),
            ],
        ]);

        $user->pushSubscriptions()->each(function (PushSubscription $pushSubscription) use ($message, $webPush, &$sent): void {
            $report = $webPush->sendOneNotification(
                Subscription::create($pushSubscription->web_push_payload),
                json_encode($this->payload($message), JSON_THROW_ON_ERROR)
            );

            if ($report->isSuccess()) {
                $pushSubscription->forceFill(['last_used_at' => now()])->save();
                $sent++;

                return;
            }

            if ($report->isSubscriptionExpired()) {
                $pushSubscription->delete();

                return;
            }

            Log::warning('Web push notification failed.', [
                'endpoint' => $pushSubscription->endpoint,
                'reason' => $report->getReason(),
            ]);
        });

        return $sent;
    }

    /**
     * @param  array{title?: string, body?: string, url?: string, icon?: string, badge?: string, tag?: string}  $message
     * @return array{title: string, body: string, url: string, icon: string, badge: string, tag: string, timestamp: int}
     */
    public function payload(array $message): array
    {
        return [
            'title' => $message['title'] ?? config('app.name'),
            'body' => $message['body'] ?? 'Open the app to view the update.',
            'url' => $message['url'] ?? route('home'),
            'icon' => $message['icon'] ?? asset('pwa/icons/icon-192x192.png'),
            'badge' => $message['badge'] ?? asset('pwa/icons/icon-96x96.png'),
            'tag' => $message['tag'] ?? 'pwa-push-notification',
            'timestamp' => now()->timestamp,
        ];
    }
}
