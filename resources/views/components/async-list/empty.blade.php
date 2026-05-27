@props([
    'icon' => null,
])

<x-empty-state
    {{ $attributes->merge([
        'class' => 'd-none',
        'data-async-list-empty' => true,
    ]) }}
    :icon="$icon"
    :title="trim($slot)"
/>
