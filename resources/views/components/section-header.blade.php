@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'section-header mb-3']) }}>
    <h4 class="section-header-title mb-1">{{ $title }}</h4>

    @if($subtitle)
        <p class="text-sm text-muted mb-0">{{ $subtitle }}</p>
    @endif
</div>
