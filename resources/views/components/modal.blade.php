@props([
    'id' => 'modal-' . uniqid(), // Unique modal ID
    'title' => null,
    'size' => 'md', // sm, md, lg
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

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                {{ $slot }}
            </div>

            <!-- Modal Footer (Optional) -->
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
