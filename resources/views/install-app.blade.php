@extends('layouts.auth')

@section('title', 'Install App')

@section('content')
    <x-card title="Install {{ config('app.name') }}" subtitle="Open the app from your home screen, just like a regular app.">
        <div class="d-grid gap-3 pwa-install-content">
            <div class="text-center">
                <img
                    src="{{ asset('pwa/icons/icon-192x192.png') }}"
                    alt="{{ config('app.name') }} app icon"
                    width="76"
                    height="76"
                    class="rounded-4 border shadow-sm mb-3"
                >
                <p class="text-muted mb-0 text-sm">No app store needed. It installs from your browser in a few seconds.</p>
            </div>

            <div class="pwa-install-status" data-install-status>
                Checking install support...
            </div>

            <x-button color="dark" class="w-100 justify-content-center" type="button" data-install-button disabled>
                <x-lucide-download class="w-4 h-4"/>
                <span>Install app</span>
            </x-button>

            <div class="pwa-install-ios d-none" data-ios-instructions>
                <div class="d-flex gap-3 align-items-start">
                    <span class="pwa-install-step">1</span>
                    <p class="mb-0">
                        Tap the
                        <span class="pwa-install-inline-icon">
                            <x-lucide-share class="w-4 h-4"/>
                        </span>
                        Share button in the browser toolbar.
                    </p>
                </div>
                <div class="d-flex gap-3 align-items-start">
                    <span class="pwa-install-step">2</span>
                    <p class="mb-0">Choose <strong>Add to Home Screen</strong>, then tap <strong>Add</strong>.</p>
                </div>
            </div>

            <x-button color="light" class="w-100 justify-content-center" link="{{ route('home') }}">
                <x-lucide-arrow-left class="w-4 h-4"/>
                <span>Back to app</span>
            </x-button>
        </div>
    </x-card>
@endsection

@push('js')
    <script type="module">
        const installButton = document.querySelector('[data-install-button]');
        const installStatus = document.querySelector('[data-install-status]');
        const iosInstructions = document.querySelector('[data-ios-instructions]');
        const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        function updateInstallState() {
            if (isStandalone) {
                installStatus.textContent = 'This app is already installed.';
                installButton.disabled = true;
                return;
            }

            if (window.deferredInstallPrompt) {
                installStatus.textContent = 'Your browser supports one-tap installation.';
                installButton.disabled = false;
                return;
            }

            if (isIos) {
                installStatus.textContent = 'Use the Share button, then add this app to your Home Screen.';
                iosInstructions.classList.remove('d-none');
                installButton.classList.add('d-none');
                return;
            }

            installStatus.textContent = 'If your browser supports installation, use its menu and choose Install app.';
            installButton.disabled = true;
        }

        installButton.addEventListener('click', async () => {
            if (!window.deferredInstallPrompt) {
                return;
            }

            const promptEvent = window.deferredInstallPrompt;
            window.deferredInstallPrompt = null;
            window.pwaInstallPrompt = null;
            promptEvent.prompt();

            const choice = await promptEvent.userChoice;
            installStatus.textContent = choice.outcome === 'accepted'
                ? 'Install started.'
                : 'Install was dismissed. You can try again from this page.';
            installButton.disabled = true;
        });

        window.addEventListener('pwa-install-ready', updateInstallState);
        window.addEventListener('appinstalled', () => {
            installStatus.textContent = 'App installed successfully.';
            installButton.disabled = true;
        });

        updateInstallState();
    </script>
@endpush
