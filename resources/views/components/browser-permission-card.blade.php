@props([
    'type',
    'title',
    'description',
    'icon' => 'lucide-shield-check',
    'button' => 'Allow',
])

<div {{ $attributes->merge(['class' => 'bg-white border rounded-3 p-3 d-grid gap-3']) }} data-browser-permission-card data-browser-permission="{{ $type }}">
    <div class="d-flex gap-3 align-items-start">
        <span class="pwa-install-inline-icon">
            <x-dynamic-component :component="$icon" class="w-4 h-4"/>
        </span>
        <div>
            <h6 class="mb-1">{{ $title }}</h6>
            <p class="text-muted text-sm mb-0">{{ $description }}</p>
            <p class="text-muted text-sm mb-0 mt-1" data-browser-permission-status>Checking permission...</p>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <x-button color="primary" type="button" data-browser-permission-request>
            <x-dynamic-component :component="$icon" class="w-4 h-4"/>
            <span>{{ $button }}</span>
        </x-button>
        <x-button color="light" type="button" data-browser-permission-check>
            <x-lucide-refresh-cw class="w-4 h-4"/>
            <span>Check again</span>
        </x-button>
    </div>
</div>
