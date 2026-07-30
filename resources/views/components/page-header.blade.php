@props([
    'title' => null,
    'subtitle' => null,
    'eyebrow' => null,
])

<div {{ $attributes->merge(['class' => 'd-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3']) }}>
    <div>
        @if($eyebrow)
            <p class="text-muted text-sm mb-1">{{ $eyebrow }}</p>
        @endif

        @if($title)
            <x-heading class="page-header-title">{{ $title }}</x-heading>

            @if($subtitle)
                <x-text class="mb-0">{{ $subtitle }}</x-text>
            @endif
        @else
            {{ $slot }}
        @endif
    </div>

    @if(isset($actions))
        <div class="d-flex flex-wrap align-items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
