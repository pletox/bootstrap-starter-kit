@props([
    'label',
    'value' => null,
    'icon' => null,
    'iconClass' => 'w-5 h-5 text-slate-600',
    'iconBg' => 'bg-gray-100',
    'valueClass' => 'h4',
    'subtitle' => null,
    'tone' => 'default',
    'loading' => false,
])

<x-card {{ $attributes->merge(['class' => 'h-100 jp-stat-card jp-stat-card-' . $tone]) }} body-class="p-0">
    <div class="jp-stat-card-grid">
        @if($icon)
            <span class="jp-stat-card-icon {{ $iconBg }}">
                <x-dynamic-component :component="$icon" class="{{ $iconClass }}"/>
            </span>
        @endif

        <span class="jp-stat-card-label">{{ $label }}</span>

        @if($loading)
            <strong class="jp-stat-card-value d-none" data-counter-value></strong>
            <div class="line-loader jp-stat-card-loader" data-counter-loader aria-hidden="true"></div>
        @else
            <strong class="jp-stat-card-value {{ $valueClass }}">{{ $value }}</strong>
        @endif

        @if($subtitle)
            <small class="jp-stat-card-subtitle">{{ $subtitle }}</small>
        @endif
    </div>
</x-card>
