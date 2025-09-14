@props([
    'icon' => null,          // lucide icon name
    'href' => null,          // if present, renders <a>
    'as' => null,            // force element type (optional)
])

@php
    // Detect element type
    $isLink = $href || $as === 'a';
    $isButton = !$isLink;

    $baseClasses = "dropdown-item d-flex align-items-center gap-2 px-3 py-1.5 rounded-1";
@endphp

@if($isLink)
    <a href="{{ $href ?? 'javascript:void(0)' }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4"/>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4"/>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
