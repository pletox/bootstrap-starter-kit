const CACHE_NAME = 'pletox-starter-offline-v2';
const OFFLINE_URL = '/offline.html';
const APP_SHELL = [
    OFFLINE_URL,
    '/pwa/icons/icon-192x192.png',
    '/pwa/icons/icon-512x512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(APP_SHELL))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const requestUrl = new URL(request.url);

    if (request.method !== 'GET' || requestUrl.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match(OFFLINE_URL))
        );

        return;
    }

    if (!isCacheableAsset(requestUrl.pathname)) {
        return;
    }

    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(request).then((response) => {
                if (response.ok) {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                }

                return response;
            });
        })
    );
});

self.addEventListener('push', (event) => {
    let data = {};

    try {
        data = event.data ? event.data.json() : {};
    } catch (error) {
        data = {
            title: 'PletoxStarter',
            body: event.data?.text() || 'Open the app to view the update.',
            url: '/home',
        };
    }

    const title = data.title || 'PletoxStarter';
    const url = data.url || '/home';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: data.body || 'Open the app to view the update.',
            icon: data.icon || '/pwa/icons/icon-192x192.png',
            badge: data.badge || '/pwa/icons/icon-96x96.png',
            data: {
                url,
            },
            tag: data.tag || 'pwa-push-notification',
            renotify: Boolean(data.tag),
            timestamp: data.timestamp ? data.timestamp * 1000 : Date.now(),
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = new URL(event.notification.data?.url || '/home', self.location.origin).href;

    event.waitUntil(
        self.clients.matchAll({type: 'window', includeUncontrolled: true}).then((clientList) => {
            const matchingClient = clientList.find((client) => client.url === targetUrl);

            if (matchingClient) {
                return matchingClient.focus();
            }

            return self.clients.openWindow(targetUrl);
        })
    );
});

function isCacheableAsset(pathname) {
    return pathname === OFFLINE_URL
        || pathname.startsWith('/build/')
        || pathname.startsWith('/fonts/')
        || pathname.startsWith('/pwa/');
}
