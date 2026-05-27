@extends('layouts.developer-docs')

@section('title', 'PWA & Push')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="Production Push Setup" subtitle="Do this once per deployed client/domain.">
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 developer-docs-step">
                    <span class="developer-docs-step-number">1</span>
                    <div class="developer-docs-step-copy">
                        <p class="fw-medium mb-1">Generate VAPID keys</p>
                        <p class="text-muted mb-0">Run this on a safe machine or the production server, then save the keys in the client's environment.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 developer-docs-step">
                    <span class="developer-docs-step-number">2</span>
                    <div class="developer-docs-step-copy">
                        <p class="fw-medium mb-1">Set production env values</p>
                        <p class="text-muted mb-0">Use a real support email or HTTPS URL for <code>VAPID_SUBJECT</code>. Keep the private key secret.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 developer-docs-step">
                    <span class="developer-docs-step-number">3</span>
                    <div class="developer-docs-step-copy">
                        <p class="fw-medium mb-1">Deploy and migrate</p>
                        <p class="text-muted mb-0">The <code>push_subscriptions</code> table stores one browser subscription per device/browser.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 developer-docs-step">
                    <span class="developer-docs-step-number">4</span>
                    <div class="developer-docs-step-copy">
                        <p class="fw-medium mb-1">Enable from the app</p>
                        <p class="text-muted mb-0">Users must grant browser/OS notification permission. The app stores the subscription after permission is granted.</p>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Server Commands" subtitle="Commands expected during production setup.">
            <div class="row g-3">
                <div class="col-12 col-xl-6">
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan app:generate-vapid-keys</code></pre>
                </div>
                <div class="col-12 col-xl-6">
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan migrate --force
php artisan config:cache</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="Required Environment" subtitle="Set these values for each production client.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>APP_URL=https://client-domain.com
VAPID_SUBJECT="mailto:support@client-domain.com"
VAPID_PUBLIC_KEY=generated_public_key
VAPID_PRIVATE_KEY=generated_private_key</code></pre>
        </x-card>

        <x-card title="Push Notification Flow" subtitle="Where each part of the implementation lives.">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Need</th>
                        <th>File</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Subscribe, unsubscribe, and test routes</td>
                        <td><code>routes/web.php</code></td>
                    </tr>
                    <tr>
                        <td>Request validation and client-safe deep links</td>
                        <td><code>app/Http/Controllers/PwaPushSubscriptionController.php</code></td>
                    </tr>
                    <tr>
                        <td>Send Web Push payloads</td>
                        <td><code>app/Services/WebPushService.php</code></td>
                    </tr>
                    <tr>
                        <td>Browser subscribe/test helper</td>
                        <td><code>resources/js/app.js</code></td>
                    </tr>
                    <tr>
                        <td>Background notification display and click deep link</td>
                        <td><code>public/service-worker.js</code></td>
                    </tr>
                    <tr>
                        <td>Reusable dashboard/install card</td>
                        <td><code>resources/views/components/pwa-push-card.blade.php</code></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card title="Client PWA Settings" subtitle="Brand these files per client before launch.">
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Manifest</h2>
                        <p class="text-muted mb-0">The manifest is served from <code>routes/web.php</code> as <code>manifest.webmanifest</code>. It uses <code>config('app.name')</code>, <code>route('home')</code>, theme colors, and icons from <code>public/pwa/icons</code>.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Icons</h2>
                        <p class="text-muted mb-0">Replace files in <code>public/pwa/icons</code>. Keep the same filenames and sizes so the manifest, Apple touch icon, and notification badge paths keep working.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Splash Screens</h2>
                        <p class="text-muted mb-0">Apple startup images live in <code>public/pwa/splash</code> and are referenced from <code>resources/views/layouts/partials/_pwa.blade.php</code>.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Offline Shell</h2>
                        <p class="text-muted mb-0"><code>public/service-worker.js</code> caches build assets, fonts, PWA assets, and <code>public/offline.html</code>. Bump <code>CACHE_NAME</code> when changing cached static behavior.</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Settings Permission Cards</h2>
                        <p class="text-muted mb-3">Control which permission cards appear in Settings from <code>config/starter-kit.php</code>. Supported values are <code>notifications</code>, <code>camera</code>, <code>microphone</code>, and <code>location</code>.</p>
                        <pre><code class="language-php">'settings' => [
    'permissions' => [
        'notifications',
        'location',
    ],
],</code></pre>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Production Checklist" subtitle="Use this when onboarding a client domain.">
            <ul class="mb-0 text-muted">
                <li>Production must run on HTTPS.</li>
                <li><code>APP_URL</code> must match the public client domain.</li>
                <li>Run migrations after deploying the push subscription migration.</li>
                <li>Run <code>config:cache</code> after setting VAPID env values.</li>
                <li>Ask users to enable notifications from the installed PWA or browser session.</li>
                <li>Test with the UI Kit push tester using a deep link such as <code>/categories</code>.</li>
                <li>If notifications do not display, check OS/browser notification permissions first.</li>
            </ul>
        </x-card>
    </div>
@endsection
