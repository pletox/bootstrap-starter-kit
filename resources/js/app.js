import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import DataTable from 'datatables.net-bs5';

window.DataTable = DataTable;

import Handlebars from "handlebars";

window.Handlebars = Handlebars;

import 'datatables.net-responsive-bs5';


import "./easyAjax.js"
import "./easyDelete.js"
import "./extendJquery.js"

window.escapeHtml = function (value) {
    return $('<div>').text(value ?? '').html();
};

import {createIcons, icons} from "lucide";

window.refreshLucideIcons = function () {
    createIcons({icons});
};

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/service-worker.js')
            .then((registration) => {
                if (registration.waiting) {
                    registration.waiting.postMessage({type: 'SKIP_WAITING'});
                }

                registration.update().catch(() => {});

                registration.addEventListener('updatefound', () => {
                    const worker = registration.installing;

                    worker?.addEventListener('statechange', () => {
                        if (worker.state === 'installed' && navigator.serviceWorker.controller) {
                            worker.postMessage({type: 'SKIP_WAITING'});
                        }
                    });
                });
            })
            .catch(() => {});
    });
}

window.deferredInstallPrompt = null;
window.pwaInstallPrompt = null;

window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    window.deferredInstallPrompt = event;
    window.pwaInstallPrompt = event;
    window.dispatchEvent(new CustomEvent('pwa-install-ready'));
});

window.addEventListener('appinstalled', function () {
    window.deferredInstallPrompt = null;
    window.pwaInstallPrompt = null;
    window.dispatchEvent(new CustomEvent('pwa-installed'));
});

window.promptPwaInstall = async function () {
    if (!window.pwaInstallPrompt) {
        return null;
    }

    const promptEvent = window.pwaInstallPrompt;
    promptEvent.prompt();

    const choice = await promptEvent.userChoice;
    window.deferredInstallPrompt = null;
    window.pwaInstallPrompt = null;

    return choice;
};

function urlBase64ToUint8Array(value) {
    const padding = '='.repeat((4 - value.length % 4) % 4);
    const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const output = new Uint8Array(rawData.length);

    for (let index = 0; index < rawData.length; index++) {
        output[index] = rawData.charCodeAt(index);
    }

    return output;
}

function hasPwaPushSupport() {
    return 'serviceWorker' in navigator
        && 'PushManager' in window
        && 'Notification' in window;
}

async function getPwaPushRegistration() {
    if (!hasPwaPushSupport()) {
        throw new Error('Push notifications are not supported by this browser.');
    }

    return Promise.race([
        navigator.serviceWorker.ready,
        new Promise((_, reject) => {
            window.setTimeout(() => {
                reject(new Error('Service worker is not ready yet. Refresh the page and try again.'));
            }, 5000);
        }),
    ]);
}

window.pwaPush = {
    isSupported: hasPwaPushSupport,

    async status() {
        if (!hasPwaPushSupport()) {
            return {
                supported: false,
                permission: 'unsupported',
                subscribed: false,
            };
        }

        const registration = await getPwaPushRegistration();
        const subscription = await registration.pushManager.getSubscription();

        return {
            supported: true,
            permission: Notification.permission,
            subscribed: Boolean(subscription),
        };
    },

    async subscribe() {
        const registration = await getPwaPushRegistration();
        const keyResponse = await axios.get('/pwa/push/public-key');

        if (!keyResponse.data.enabled || !keyResponse.data.publicKey) {
            throw new Error('Push notifications are not configured yet.');
        }

        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            throw new Error('Notification permission was not granted.');
        }

        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(keyResponse.data.publicKey),
            });
        }

        const payload = subscription.toJSON();
        payload.contentEncoding = PushManager.supportedContentEncodings?.includes('aes128gcm')
            ? 'aes128gcm'
            : 'aesgcm';

        await axios.post('/pwa/push/subscribe', payload);

        return subscription;
    },

    async unsubscribe() {
        const registration = await getPwaPushRegistration();
        const subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            return false;
        }

        await axios.delete('/pwa/push/subscribe', {
            data: {
                endpoint: subscription.endpoint,
            },
        });

        return subscription.unsubscribe();
    },

    async sendTest(payload = {}) {
        const response = await axios.post('/pwa/push/test', payload);

        return response.data;
    },

    async showNotification(payload = {}) {
        const registration = await getPwaPushRegistration();

        if (Notification.permission !== 'granted') {
            throw new Error('Notification permission was not granted.');
        }

        await registration.showNotification(payload.title || document.title, {
            body: payload.body || 'Open the app to view the update.',
            icon: payload.icon || '/pwa/icons/icon-192x192.png',
            badge: payload.badge || '/pwa/icons/icon-96x96.png',
            tag: payload.tag || 'pwa-test-notification',
            renotify: true,
            data: {
                url: payload.url || window.location.origin + '/home',
            },
        });
    },
};

async function refreshPwaPushCards() {
    const cards = document.querySelectorAll('[data-pwa-push-card]');

    if (!cards.length) {
        return;
    }

    const status = await window.pwaPush.status();

    cards.forEach((card) => {
        const statusEl = card.querySelector('[data-pwa-push-status]');
        const enableButton = card.querySelector('[data-pwa-push-enable]');
        const disableButton = card.querySelector('[data-pwa-push-disable]');
        const testButton = card.querySelector('[data-pwa-push-test]');

        if (!status.supported) {
            statusEl.textContent = 'This browser does not support push notifications.';
            enableButton?.setAttribute('disabled', 'disabled');
            disableButton?.classList.add('d-none');
            testButton?.classList.add('d-none');
            return;
        }

        if (status.permission === 'denied') {
            statusEl.textContent = 'Notifications are blocked in your browser settings.';
            enableButton?.setAttribute('disabled', 'disabled');
            disableButton?.classList.add('d-none');
            testButton?.classList.add('d-none');
            return;
        }

        statusEl.textContent = status.subscribed
            ? 'Notifications are enabled on this device.'
            : 'Enable notifications to receive important updates.';

        enableButton?.classList.toggle('d-none', status.subscribed);
        disableButton?.classList.toggle('d-none', !status.subscribed);
        testButton?.classList.toggle('d-none', !status.subscribed);
        enableButton?.removeAttribute('disabled');
    });
}

function initPwaPushCards() {
    document.querySelectorAll('[data-pwa-push-card]').forEach((card) => {
        if (card.dataset.pwaPushReady === 'true') {
            return;
        }

        card.dataset.pwaPushReady = 'true';
        card.querySelector('[data-pwa-push-enable]')?.addEventListener('click', async () => {
            try {
                await window.pwaPush.subscribe();
                toast.success('Push notifications enabled.');
            } catch (error) {
                toast.error(error.response?.data?.message || error.message || 'Unable to enable notifications.');
            } finally {
                refreshPwaPushCards();
            }
        });

        card.querySelector('[data-pwa-push-disable]')?.addEventListener('click', async () => {
            try {
                await window.pwaPush.unsubscribe();
                toast.success('Push notifications disabled.');
            } catch (error) {
                toast.error(error.response?.data?.message || error.message || 'Unable to disable notifications.');
            } finally {
                refreshPwaPushCards();
            }
        });

        card.querySelector('[data-pwa-push-test]')?.addEventListener('click', async () => {
            try {
                const response = await window.pwaPush.sendTest({
                    url: card.dataset.pwaPushUrl || window.location.origin + '/home',
                });
                await window.pwaPush.showNotification(response.notification || {});
                toast.success(response.message);
            } catch (error) {
                toast.error(error.response?.data?.message || error.message || 'Unable to send test notification.');
            }
        });
    });

    refreshPwaPushCards();
}

async function getBrowserPermissionState(permission) {
    if (!('permissions' in navigator) || typeof navigator.permissions.query !== 'function') {
        return 'prompt';
    }

    try {
        const status = await navigator.permissions.query({name: permission});

        return status.state;
    } catch (error) {
        return 'prompt';
    }
}

async function requestBrowserPermission(permission) {
    if (permission === 'geolocation') {
        if (!('geolocation' in navigator)) {
            throw new Error('Location permission is not supported in this browser.');
        }

        await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: false,
                maximumAge: 60000,
                timeout: 10000,
            });
        });

        return;
    }

    if (!navigator.mediaDevices?.getUserMedia) {
        throw new Error('Media permissions are not supported in this browser.');
    }

    const stream = await navigator.mediaDevices.getUserMedia({
        video: permission === 'camera',
        audio: permission === 'microphone',
    });

    stream.getTracks().forEach((track) => track.stop());
}

async function refreshBrowserPermissionCards() {
    const cards = document.querySelectorAll('[data-browser-permission-card]');

    await Promise.all(Array.from(cards).map(async (card) => {
        const permission = card.dataset.browserPermission;
        const statusEl = card.querySelector('[data-browser-permission-status]');
        const requestButton = card.querySelector('[data-browser-permission-request]');
        const state = await getBrowserPermissionState(permission);

        requestButton?.removeAttribute('disabled');

        if (state === 'granted') {
            statusEl.textContent = 'Allowed on this device.';
            requestButton?.classList.add('d-none');
            return;
        }

        requestButton?.classList.remove('d-none');

        if (state === 'denied') {
            statusEl.textContent = 'Blocked in your browser settings.';
            requestButton?.setAttribute('disabled', 'disabled');
            return;
        }

        statusEl.textContent = 'Ask when this app needs access.';
    }));
}

function initBrowserPermissionCards() {
    document.querySelectorAll('[data-browser-permission-card]').forEach((card) => {
        if (card.dataset.browserPermissionReady === 'true') {
            return;
        }

        card.dataset.browserPermissionReady = 'true';
        const permission = card.dataset.browserPermission;
        const statusEl = card.querySelector('[data-browser-permission-status]');
        const requestButton = card.querySelector('[data-browser-permission-request]');

        requestButton?.addEventListener('click', async () => {
            requestButton.setAttribute('disabled', 'disabled');
            statusEl.textContent = 'Waiting for browser permission...';

            try {
                await requestBrowserPermission(permission);
                toast.success('Permission allowed on this device.');
            } catch (error) {
                toast.error(error.message || 'Unable to request permission.');
            } finally {
                requestButton.removeAttribute('disabled');
                refreshBrowserPermissionCards();
            }
        });

        card.querySelector('[data-browser-permission-check]')?.addEventListener('click', refreshBrowserPermissionCards);
    });

    refreshBrowserPermissionCards();
}

function triggerTouchHaptic() {
    if (!('vibrate' in navigator)) {
        return;
    }

    navigator.vibrate(8);
}

function isMobileViewport() {
    return window.matchMedia('(max-width: 767.98px)').matches;
}

function initBottomBarInteractions() {
    document.querySelectorAll('.bottom-bar-item').forEach((item) => {
        if (item.dataset.mobileInteractionReady === 'true') {
            return;
        }

        item.dataset.mobileInteractionReady = 'true';
        item.addEventListener('pointerdown', triggerTouchHaptic, {passive: true});
    });
}

let developerDocsHighlighterPromise = null;

function loadDeveloperDocsHighlighter() {
    if (!developerDocsHighlighterPromise) {
        developerDocsHighlighterPromise = Promise.all([
            import('highlight.js/lib/common'),
            import('highlight.js/styles/github.css'),
        ]).then(([module]) => module.default);
    }

    return developerDocsHighlighterPromise;
}

function detectDocsCodeLanguage(code) {
    const value = code.trim();

    if (!value) {
        return null;
    }

    if (value.startsWith('<') || value.includes('<x-') || value.includes('</x-')) {
        return 'xml';
    }

    if (value.startsWith('{') || value.startsWith('[')) {
        return 'json';
    }

    if (value.includes('<?php') || value.includes('public function') || value.includes('Route::') || value.includes('Category::')) {
        return 'php';
    }

    if (value.startsWith('php artisan') || value.startsWith('npm run')) {
        return 'bash';
    }

    if (value.includes('$(function') || value.includes('axios.') || value.includes('const ') || value.includes('let ')) {
        return 'javascript';
    }

    return null;
}

async function copyTextToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(text);
        return;
    }

    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';

    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
}

async function initDeveloperDocsCodeBlocks() {
    const codeBlocks = document.querySelectorAll('.developer-docs-article pre > code');

    if (!codeBlocks.length) {
        return;
    }

    const hljs = await loadDeveloperDocsHighlighter();

    codeBlocks.forEach((code) => {
        const pre = code.parentElement;

        if (!pre || pre.dataset.docsCodeReady === 'true') {
            return;
        }

        const rawCode = code.textContent;
        const language = detectDocsCodeLanguage(rawCode);

        if (language && hljs.getLanguage(language)) {
            code.innerHTML = hljs.highlight(rawCode, {language}).value;
            code.classList.add('hljs', `language-${language}`);
        } else {
            code.innerHTML = hljs.highlightAuto(rawCode).value;
            code.classList.add('hljs');
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'developer-docs-code-block';
        pre.parentNode.insertBefore(wrapper, pre);
        wrapper.appendChild(pre);

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'developer-docs-code-copy';
        button.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i><span>Copy</span>';
        button.addEventListener('click', async () => {
            await copyTextToClipboard(rawCode);

            button.classList.add('copied');
            button.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i><span>Copied</span>';
            window.refreshLucideIcons();

            setTimeout(() => {
                button.classList.remove('copied');
                button.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i><span>Copy</span>';
                window.refreshLucideIcons();
            }, 1600);
        });

        wrapper.appendChild(button);
        pre.dataset.docsCodeReady = 'true';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    window.refreshLucideIcons();
    initBottomBarInteractions();
    initPwaPushCards();
    initBrowserPermissionCards();
    initDeveloperDocsCodeBlocks();
});

document.addEventListener('livewire:navigating', function () {
    if (isMobileViewport()) {
        document.documentElement.classList.add('mobile-navigating');
        document.documentElement.classList.remove('mobile-navigated');
    }

    $.fn.dataTable.tables({visible: true, api: true}).destroy();
    $('[data-jp-editor]').jpEditorDestroy();
});

document.addEventListener('livewire:navigated', function () {
    window.refreshLucideIcons();
    initBottomBarInteractions();
    initPwaPushCards();
    initBrowserPermissionCards();
    initDeveloperDocsCodeBlocks();

    if (isMobileViewport()) {
        document.documentElement.classList.remove('mobile-navigating');
        document.documentElement.classList.add('mobile-navigated');

        window.setTimeout(() => {
            document.documentElement.classList.remove('mobile-navigated');
        }, 260);
    }

    const sidebarToggle = document.querySelector("#sidebar-toggle");
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
            document.querySelector("body").classList.toggle("sidebar-collapsed");
            document.querySelector("#sidebarBackdrop").classList.toggle("show");
        });
    }

    const sidebarBackdrop = document.querySelector("#sidebarBackdrop");
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
            document.querySelector("body").classList.toggle("sidebar-collapsed");
            document.querySelector("#sidebarBackdrop").classList.toggle("show");
        });
    }

    const themeToggle = document.querySelector(".theme-toggle");
    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            toggleLocalStorage();
            toggleRootClass();
        });
    }

    function toggleRootClass() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const inverted = current == 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', inverted);
    }

    function toggleLocalStorage() {
        if (isLight()) {
            localStorage.removeItem("light");
        } else {
            localStorage.setItem("light", "set");
        }
    }

    function isLight() {
        return localStorage.getItem("light");
    }

    if (isLight()) {
        toggleRootClass();
    }
});

// Requires jQuery and Bootstrap 5
(function ($) {
    // Unique id generator
    function uid() {
        return Math.random().toString(36).slice(2, 9);
    }

    // On show: move menu to body and position absolutely
    $(document).on('show.bs.dropdown', '.dropdown', function (e) {
        const $dropdown = $(this);
        const $menu = $dropdown.find('.dropdown-menu').first();
        if (!$menu.length) return;

        // avoid double-appending
        if ($menu.data('appended-to-body')) return;

        const id = uid();
        $dropdown.attr('data-dropdown-id', id);
        $menu.attr('data-dropdown-id', id);

        // store original parent & next sibling so we can restore later
        $menu.data('original-parent', $menu.parent());
        $menu.data('original-next', $menu.next().get(0) || null);

        // append to body
        $('body').append($menu);

        // temporarily make it visible & absolutely positioned for accurate measurement
        $menu.css({
            position: 'absolute',
            display: 'block',
            visibility: 'hidden',   // keep invisible during measurement to avoid flicker
            left: 0,
            top: 0
        });

        // compute geometry
        const toggleEl = $dropdown.find('[data-bs-toggle="dropdown"], .dropdown-toggle').get(0);
        const toggleRect = toggleEl.getBoundingClientRect();
        const menuRect = $menu.get(0).getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        // default below the toggle
        let top = toggleRect.bottom + scrollTop;
        let left = toggleRect.left + scrollLeft;

        // keep inside viewport horizontally
        if (left + menuRect.width > scrollLeft + window.innerWidth) {
            left = Math.max(scrollLeft + 8, scrollLeft + window.innerWidth - menuRect.width - 8);
        }
        // if it would go outside bottom of viewport, try placing above
        if (top + menuRect.height > scrollTop + window.innerHeight) {
            top = toggleRect.top + scrollTop - menuRect.height;
            // if still doesn't fit, clamp inside viewport
            if (top < scrollTop) top = scrollTop + 8;
        }

        // apply final positioning and restore visibility
        $menu.css({
            left: `${left}px`,
            top: `${top}px`,
            visibility: '',
            display: ''   // let bootstrap handle the display class (.show etc.)
        });

        $menu.data('appended-to-body', true);
        $menu.css('z-index', 1060); // above DataTables layers
    });

    // On hide: return menu back to original position and clear styles
    $(document).on('hidden.bs.dropdown', '.dropdown', function (e) {
        const $dropdown = $(this);
        const id = $dropdown.attr('data-dropdown-id');
        if (!id) return;

        const $menu = $(`.dropdown-menu[data-dropdown-id="${id}"]`);
        if (!$menu.length) return;

        const $origParent = $menu.data('original-parent');
        const origNext = $menu.data('original-next');

        // reset inline styles we set
        $menu.css({
            left: '',
            top: '',
            position: '',
            zIndex: '',
            visibility: '',
            display: ''
        });

        // restore into original parent / position
        if ($origParent && $origParent.length) {
            if (origNext) {
                $origParent.get(0).insertBefore($menu.get(0), origNext);
            } else {
                $origParent.append($menu);
            }
        }

        // cleanup
        $menu.removeAttr('data-dropdown-id');
        $dropdown.removeAttr('data-dropdown-id');
        $menu.removeData('original-parent original-next appended-to-body');
    });
})(jQuery);
