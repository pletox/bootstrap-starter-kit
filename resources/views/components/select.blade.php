@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => 'Select an option',
    'multiple' => false,
    'size' => 'md', // sm, md, lg
    'error' => null,
])

@php
    $id = $id ?? Str::random(10); // Generate a unique ID if not provided
    $sizeClass = match($size) {
        'sm' => 'form-select-sm',
        'lg' => 'form-select-lg',
        default => 'form-select'
    };

     $wireModel = collect($attributes->whereStartsWith('wire:model'))->first();
@endphp

<div class="form-group mb-3 w-100">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="form-select {{ $sizeClass }} @error($name) is-invalid @enderror"
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes }}  {{-- Enables wire:model and x-model --}}
    >
        <option></option>
        {{ $slot }}
    </select>


    <div class="invalid-feedback"> @error($name) {{ $message }}   @enderror</div>

</div>

<script>
    document.addEventListener('livewire:navigated', function () {
        let select = $('#{{ $id }}').select2({
            dropdownParent: $('#{{ $id }}').parent(),
            theme: 'bootstrap-5',
            placeholder: '{{ $placeholder }}',
            allowClear: true
        });

        select.on('change', function () {
            let value = $(this).val();

            @if ($wireModel)
            if (window.Livewire) {
                Livewire.find('{{ $wireModel }}')?.set('{{ $wireModel }}', value);
            }
            @endif

            // Update Alpine.js model if x-model is used
            select.get(0).dispatchEvent(new Event('input', {bubbles: true}));
        });
    });
</script>
