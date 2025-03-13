@props([
    'id' => 'modal-' . uniqid(), // Unique modal ID
    'title' => null,
    'size' => 'md', // sm, md, lg
    'headerHidden' => false,
])

@php
    $sizes = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
    ];
    $modalSizeClass = $sizes[$size] ?? '';
@endphp

    <!-- Bootstrap Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $modalSizeClass }}">
        <div class="modal-content">

            @if(!$headerHidden)
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif


            <!-- Slot for modal body and footer -->
            {{ $slot }}

        </div>
    </div>
</div>
