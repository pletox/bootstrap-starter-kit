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
                <div class="modal-header py-2">
                    <h6 class="modal-title">{{ $title }}</h6>
                    <button type="button" class="btn-close" style="font-size: 12px;" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif


            <!-- Slot for modal body and footer -->
            {{ $slot }}

        </div>
    </div>
</div>
