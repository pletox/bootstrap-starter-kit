@props([
    'url' => route('home'),
])

<div {{ $attributes->merge(['class' => 'bg-white border rounded-3 p-3 d-grid gap-3']) }} data-pwa-push-card data-pwa-push-url="{{ $url }}">
    <div class="d-flex gap-3 align-items-start">
        <span class="pwa-install-inline-icon">
            <x-lucide-bell-ring class="w-4 h-4"/>
        </span>
        <div>
            <h6 class="mb-1">App notifications</h6>
            <p class="text-muted text-sm mb-0" data-pwa-push-status>Checking notification support...</p>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <x-button color="primary" type="button" data-pwa-push-enable>
            <x-lucide-bell class="w-4 h-4"/>
            <span>Enable</span>
        </x-button>
        <x-button color="light" type="button" class="d-none" data-pwa-push-test>
            <x-lucide-send class="w-4 h-4"/>
            <span>Send test</span>
        </x-button>
        <x-button color="light" type="button" class="d-none" data-pwa-push-disable>
            <x-lucide-bell-off class="w-4 h-4"/>
            <span>Disable</span>
        </x-button>
    </div>
</div>
