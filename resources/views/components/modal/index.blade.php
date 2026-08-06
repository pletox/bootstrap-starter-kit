@props([
    'id' => 'modal-' . uniqid(), // Unique modal ID
    'title' => null,
    'size' => 'md', // sm, md, lg, xl, full
    'headerHidden' => false,
    'mobileSheet' => true,
    'staticBackdrop' => false,
    'disableEscape' => false,
])

@php
    $sizes = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'full' => 'modal-full',
    ];
    $modalSizeClass = $sizes[$size] ?? '';

    $backdropAttr = $staticBackdrop ? 'static' : 'true';
    $keyboardAttr = $disableEscape ? 'false' : 'true';
@endphp

    <!-- Bootstrap Modal -->
<div @class(['modal fade', 'modal-mobile-sheet' => $mobileSheet])
     id="{{ $id }}"
     tabindex="-1"
     aria-hidden="true"
     data-bs-backdrop="{{ $backdropAttr }}"
     data-bs-keyboard="{{ $keyboardAttr }}">
    <div class="modal-dialog modal-dialog-scrollable {{ $modalSizeClass }}">
        <div class="modal-content">

            @if(!$headerHidden)
                <!-- Modal Header -->
                <div class="modal-header py-2">
                    <h6 class="modal-title">{{ $title }}</h6>
                    @unless($staticBackdrop)
                        <button type="button" class="btn-close modal-close-button" data-bs-dismiss="modal" aria-label="Close"></button>
                    @endunless
                </div>
            @endif


            <!-- Slot for modal body and footer -->
            {{ $slot }}

        </div>
    </div>
</div>
