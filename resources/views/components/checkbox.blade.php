@props([
    'label' => '',
    'name' => '',
    'id' => null,
    'size' => 'md',
    'required' => false
])

@php
    $sizeClass = match($size) {
        'sm' => 'form-check-sm',
        'lg' => 'form-check-lg',
        default => 'form-check-md'
    };
@endphp

@php
    $id = $id ?? $name;
@endphp

<div class="form-group form-check {{ $sizeClass }} d-flex align-items-center gap-2">
    <input
        id="{{ $id }}"
        type="checkbox"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-check-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        @if($required) required @endif
    >

    <label class="form-check-label" for="{{ $id }}">
        {{ $label }}
    </label>

    <div class="invalid-feedback"> @error($name) {{ $message }}   @enderror</div>
</div>
