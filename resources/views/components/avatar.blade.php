@props([
    'src' => null,           // Image URL
    'letters' => null,       // Fallback initials/letters
    'size' => 'md',          // sm | md | lg
    'shape' => 'rounded',    // rounded | semi | square
    'color' => 'secondary',  // bootstrap color
    'soft' => false          // soft color mode
])

@php
    // Sizes
    $sizes = [
        'sm' => 'avatar-sm',
        'md' => 'avatar-md',
        'lg' => 'avatar-lg'
    ];

    // Shapes
    $shapes = [
        'rounded' => 'rounded-circle',
        'semi' => 'rounded',
        'square' => 'rounded-0'
    ];

    // Colors
    $colorClass = $soft
        ? "bg-$color bg-opacity-10 text-$color"
        : "bg-$color text-white";

    if($color == 'light')
        $colorClass = "bg-$color text-muted";

    // Final classes
    $classes = ($sizes[$size] ?? $sizes['md']) . ' ' . ($shapes[$shape] ?? $shapes['rounded']);
@endphp

@if ($src)
    {{-- Image Avatar (ignore color) --}}
    <img src="{{ $src }}" alt="avatar" class="avatar {{ $classes }}">
@else
    {{-- Letters Avatar --}}
    <div class="avatar border d-flex align-items-center justify-content-center fw-semibold {{ $classes }} {{ $colorClass }}">
        {{ strtoupper($letters ?? '?') }}
    </div>
@endif
