@props([
    'label' => '',
    'name' => '',
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

<div class="form-check {{ $sizeClass }} d-flex align-items-center gap-2">
    <input
        id="{{ $name }}"
        type="checkbox"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-check-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        @if($required) required @endif
    >

    <label class="form-check-label" for="{{ $name }}">
        {{ $label }}
    </label>

    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<style>

    .form-check-md .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
    }

    .form-check-md .form-check-label {
        display: inline-block;
        margin-top: 5px;
        font-size: 0.936rem;
    }


    .form-check-sm .form-check-input {
        width: 0.875rem;
        height: 0.875rem;
    }

    .form-check-lg .form-check-label {
        display: inline-block;
        margin-top: 4px;
        font-size: 1.2rem;
    }

    .form-check-lg .form-check-input {
        width: 1.5rem;
        height: 1.5rem;
    }
</style>
