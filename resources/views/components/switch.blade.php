@props([
    'label' => '',
    'name' => '',
    'size' => 'md', // Default size
    'required' => false
])

@php
    $sizeClass = match($size) {
        'sm' => 'switch-sm',
        'lg' => 'switch-lg',
        default => 'switch-md'
    };
@endphp

<div class="form-check form-switch {{ $sizeClass }}">
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


    .switch-md .form-check-input {
        width: 1.7rem;
        height: 0.975rem;
    }

    .switch-md .form-check-label {
        display: inline-block;
        font-size: 0.936rem;
    }


    .switch-sm .form-check-input {
        width: 1.5rem;
        height: 0.875rem;
    }

    .switch-lg .form-check-label {
        margin-left: 7px;
        display: inline-block;
        margin-top: 2px;
        font-size: 1.2rem;
    }

    .switch-lg .form-check-input {
        width: 2.5rem;
        height: 1.5rem;
    }
</style>
