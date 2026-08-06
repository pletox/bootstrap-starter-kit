@props([
    'type' => 'button',
    'size' => 'md',
    'color' => 'primary',
    'link' => null,
    'disabled' => false,
    'loading' => false,
    'id' => null,
    'responsive' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'iconClass' => 'w-4 h-4',
])

@php
    use Illuminate\Support\Str;

    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => ''
    };

    $colorClass = 'btn-' . $color;

    $buttonId = $id ?? Str::random(10);

    $iconRaw = $icon ? trim($icon) : null;
    $iconComponent = null;

    if ($iconRaw) {
        $iconComponent = Str::startsWith($iconRaw, 'lucide-')
            ? $iconRaw
            : 'lucide-' . $iconRaw;
    }

    $baseClasses = "btn $colorClass $sizeClass d-inline-flex align-items-center justify-content-center gap-2";

    $iconHtml = null;
    if ($iconComponent) {
        $iconHtml = view('components.dynamic-component', [
            'component' => $iconComponent,
            'attributes' => new \Illuminate\View\ComponentAttributeBag(['class' => $iconClass]),
        ])->render();
    }
@endphp

@if($link)
    <a href="{{ $link }}" id="{{ $buttonId }}"
        {{ $attributes->merge(['class' => $baseClasses . ($loading ? ' is-loading' : '')]) }}>
        @if($loading)
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        @endif

        @if($iconHtml && $iconPosition === 'left' && ! $loading)
            {!! $iconHtml !!}
        @endif

        <span class="button-text @if($responsive) d-none d-md-inline-flex @else d-inline-flex @endif align-items-center gap-1">{{ $slot }}</span>

        @if($iconHtml && $iconPosition === 'right' && ! $loading)
            {!! $iconHtml !!}
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        id="{{ $buttonId }}"
        x-data="{ loading: @json($loading) }"
        :class="{ 'is-loading': loading }"
        x-init="
          let form = $el.closest('form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    let submitButton = event.submitter;
                    if (submitButton === $el) {
                        loading = true;
                    }
                });


                form.addEventListener('reset', () => {
                    loading = false;
                });
            }

            document.addEventListener('ajaxComplete', function(event) {
                loading = false;
            });

            const syncLoadingState = (event) => {
                $nextTick(() => {
                    if (event.detail.id === '{{ $buttonId }}') {
                        loading = event.detail.state;
                    }
                });
            };

            window.addEventListener('button-loading', syncLoadingState);
            window.addEventListener('pletox:button-loading', syncLoadingState);
        "
        :disabled="loading || {{ $disabled ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => $baseClasses]) }}>

        <span x-cloak x-show="loading" class="button-loader spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>

        @if($iconHtml && $iconPosition === 'left')
            <span x-cloak x-show="!loading" class="button-icon d-inline-flex">{!! $iconHtml !!}</span>
        @endif

        <span class="button-text @if($responsive) d-none d-md-inline-flex @else d-inline-flex @endif align-items-center gap-1">{{ $slot }}</span>

        @if($iconHtml && $iconPosition === 'right')
            <span x-cloak x-show="!loading" class="button-icon d-inline-flex">{!! $iconHtml !!}</span>
        @endif
    </button>
@endif
