@props([
    'icon' => null,
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'text-center text-muted py-5']) }}>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-5 h-5 text-muted"/>
    @endif

    <p class="mb-0 mt-2">{{ $title }}</p>

    @if($description)
        <p class="mb-0 mt-1 text-sm">{{ $description }}</p>
    @endif
</div>
