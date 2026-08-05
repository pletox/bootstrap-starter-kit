@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
    'align' => 'lg',
])

@php
    $alignClass = match ($align) {
        'xl' => 'flex-xl-row align-items-xl-center',
        default => 'flex-lg-row align-items-lg-center',
    };
@endphp

<div {{ $attributes->merge(['class' => "d-flex flex-column {$alignClass} justify-content-between gap-3 mb-3"]) }}>
    <div>
        @if($eyebrow)
            <p class="text-muted text-sm mb-1">{{ $eyebrow }}</p>
        @endif

        <x-heading class="page-header-title">{{ $title }}</x-heading>

        @if($subtitle)
            <x-text class="mb-0">{{ $subtitle }}</x-text>
        @endif
    </div>

    @isset($actions)
        <div class="d-flex flex-wrap gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
