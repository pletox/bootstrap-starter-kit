@props([
    'id' => null,
    'url',
    'page' => 1,
    'maxHeight' => null,
    'bodyScroll' => false,
])

<div
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->class([
        'overflow-auto' => ! $bodyScroll,
        'overflow-visible' => $bodyScroll,
    ]) }}
    @style([
        "max-height: {$maxHeight}" => $maxHeight && ! $bodyScroll,
    ])
    data-async-list
    data-url="{{ $url }}"
    data-page="{{ $page }}"
    aria-busy="true"
>
    {{ $slot }}
</div>
