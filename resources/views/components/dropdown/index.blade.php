@props([
    'color' => 'light',          // button color (Bootstrap variant)
    'icon' => 'lucide-ellipsis', // default button icon
    'buttonClass' => 'btn-sm',   // button size
    'menuClass' => '',           // extra classes for menu
    'align' => 'start',          // start | end | center
    'direction' => 'down',       // down | up | end | start
     'text' => null
])

@php
    // Map direction
    $directionClass = match($direction) {
        'up' => 'dropup',
        'end' => 'dropend',
        'start' => 'dropstart',
        default => '', // 'down' is default
    };

    // Map alignment
    $alignmentClass = match($align) {
        'end' => 'dropdown-menu-end',
        'center' => 'dropdown-menu-center',
        default => '', // 'start' is default
    };
@endphp

<div class="dropdown {{ $directionClass }}">
    {{-- Toggle Button --}}
    <x-button
            :color="$color"
            :class="$buttonClass"
            data-bs-toggle="dropdown"
            aria-expanded="false"
    >
        <x-dynamic-component :component="$icon" class="w-3 h-3"/>
        @if($text)
            <span>{{$text}}</span>
        @endif
    </x-button>

    {{-- Dropdown Menu --}}
    <ul class="dropdown-menu dropdown-modern rounded-2 {{ $alignmentClass }} {{ $menuClass }}">
        {{ $slot }}
    </ul>
</div>


@props([
    'color' => 'light',       // button color (Bootstrap 5 variant)
    'icon' => 'lucide-ellipsis', // icon component (default: ellipsis)
    'buttonClass' => 'btn-sm', // button size class
    'menuClass' => '',  // extra classes for dropdown menu

])
