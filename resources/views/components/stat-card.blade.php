@props([
    'label',
    'value' => null,
    'icon' => null,
    'iconClass' => 'w-5 h-5 text-slate-600',
    'iconBg' => 'bg-gray-100',
    'valueClass' => 'h4',
    'loading' => false,
])

<x-card {{ $attributes->merge(['class' => 'h-100']) }}>
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p class="text-muted text-sm mb-1">{{ $label }}</p>

            @if($loading)
                <h3 class="mb-0 d-none" data-counter-value></h3>
                <div class="line-loader mt-3" data-counter-loader aria-hidden="true"></div>
            @else
                <h4 class="{{ $valueClass }} mb-0">{{ $value }}</h4>
            @endif
        </div>

        @if($icon)
            <div class="d-flex align-items-center justify-content-center rounded {{ $iconBg }} w-10 h-10">
                <x-dynamic-component :component="$icon" class="{{ $iconClass }}"/>
            </div>
        @endif
    </div>
</x-card>
