@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    <h4 class="mb-1 text-base font-medium text-gray-700">{{ $title }}</h4>

    @if($subtitle)
        <p class="text-sm text-muted mb-0">{{ $subtitle }}</p>
    @endif
</div>
