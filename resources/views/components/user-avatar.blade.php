@props([
    'user',
    'size' => '', // Options: sm, md, lg
    'color' => 'primary', // Bootstrap background color
    'shape' => 'circle', // Options: circle, rounded, square
    'textSize' => null, // Custom text size class
    'class' => '', // Custom classes
])

@php
    $avatarUrl = $user->avatar_url ?? null;
    $initials = strtoupper(mb_substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? mb_substr(last(explode(' ', $user->name)), 0, 1) : ''));

    // Predefined size classes
    $sizeClasses = [
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-12 h-12 text-md',
        'lg' => 'w-16 h-16 text-lg',
    ];

    // Predefined shape classes
    $shapeClasses = [
        'circle' => 'rounded-circle',
        'rounded' => 'rounded',
        'square' => 'rounded-0',
    ];

    // Use custom text size if provided, otherwise use default from size
    $textClass = $textSize ?? ($sizeClasses[$size] ?? '');
@endphp

<div class="d-flex align-items-center justify-content-center overflow-hidden bg-{{ $color }} text-black {{ $sizeClasses[$size] ?? '' }} {{ $shapeClasses[$shape] ?? '' }} {{ $class }}">
    @if ($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover">
    @else
        <span class=" {{ $textClass }}">{{ $initials }}</span>
    @endif
</div>
