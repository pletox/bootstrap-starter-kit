@props([
    'id' => null,
    'url',
    'page' => 1,
    'maxHeight' => null,
])

<div
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => 'overflow-auto']) }}
    @style([
        "max-height: {$maxHeight}" => $maxHeight,
    ])
    data-async-list
    data-url="{{ $url }}"
    data-page="{{ $page }}"
    aria-busy="true"
>
    {{ $slot }}
</div>
