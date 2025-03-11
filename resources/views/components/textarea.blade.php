@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => 'Enter text...',
    'size' => 'md', // sm, md, lg
    'autoHeight' => false, // Enable auto height
    'rows' => 3, // Default rows
])

@php
    $id = $id ?? Str::random(10);
    $sizeClass = match($size) {
        'sm' => 'form-control-sm',
        'lg' => 'form-control-lg',
        default => 'form-control'
    };
@endphp

<div class="form-group mb-3">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        class="form-control {{ $sizeClass }} @error($name) is-invalid @enderror"
        {{ $attributes }}  {{-- Enables wire:model and x-model --}}
    ></textarea>

    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@if ($autoHeight)
    <script>
        document.addEventListener('livewire:navigated', function () {
            let textarea = document.getElementById('{{ $id }}');

            function adjustHeight() {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }

            textarea.addEventListener('input', adjustHeight);
            adjustHeight(); // Adjust on load in case of pre-filled content
        });
    </script>
@endif
