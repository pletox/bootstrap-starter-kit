@props([
    'type' => 'button',
    'size' => 'md',
    'color' => 'primary',
    'link' => null,
    'disabled' => false,
    'loading' => false,
     'id' => null,
])

@php
    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => ''
    };

    $colorClass = 'btn-' . $color;

      $buttonId = $id ?? Str::random(10);
@endphp

@if($link)
    <a href="{{ $link }}" id="{{ $buttonId }}"
        {{ $attributes->merge(['class' => "btn $colorClass $sizeClass d-flex align-items-center justify-content-center gap-2"]) }}>
        @if($loading)
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        @endif
        <span class="button-text d-flex align-items-center gap-1">{{ $slot }}</span>
    </a>
@else
    <button
        type="{{ $type }}"
        id="{{ $buttonId }}"
        x-data="{ loading: @json($loading) }"
        x-init="
          let form = $el.closest('form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    let submitButton = event.submitter; // Gets the clicked submit button
                    if (submitButton === $el) {
                        loading = true; // Apply loading only to this button
                    }
                });


                form.addEventListener('reset', () => {
                    loading = false;
                });
            }

            // Detect AJAX request completion (for jQuery-based AJAX forms)
            document.addEventListener('ajaxComplete', function(event) {
                loading = false;
            });

          // Ensure each button listens for updates
            window.addEventListener('button-loading', (event) => {
                $nextTick(() => {
                    if (event.detail.id === '{{ $buttonId }}') {
                        loading = event.detail.state;
                    }
                });
            });

            // Detect Livewire request completion
            document.addEventListener('livewire:load', () => {
                Livewire.hook('message.processed', () => loading = false);
            });
        "
        :disabled="loading || {{ $disabled ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => "btn $colorClass $sizeClass d-flex align-items-center justify-content-center gap-2"]) }}>

        <span x-cloak x-show="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span class="button-text d-flex align-items-center gap-1">{{ $slot }}</span>
    </button>
@endif
