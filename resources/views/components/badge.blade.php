@props([
    'color' => 'primary',   {{-- Bootstrap color --}}
    'size' => 'md',         {{-- sm | md | lg --}}
    'soft' => false         {{-- soft version --}}
])

@php
    // Bootstrap color classes
    $baseColor = "bg-$color";
    $softColor = "bg-$color bg-opacity-10 text-$color";

    // Sizes (Bootstrap 5 friendly)
    $sizes = [
        'sm' => 'px-2 py-1 small',   // smaller font
        'md' => 'px-3 py-1',         // default
        'lg' => 'px-3 py-2 fs-6'     // larger font
    ];

    $classes = $soft
        ? $softColor
        : "$baseColor text-white";

    $classes .= " rounded-pill " . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => "badge $classes"]) }}>
    {{ $slot }}
</span>
