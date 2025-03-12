@props([
    'label' => '',
    'id' => null,
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'size' => 'md',
    'required' => false
])

@php
    $sizeClass = match($size) {
        'sm' => 'form-control-sm',
        'lg' => 'form-control-lg',
        default => ''
    };

    $inInvalid = $errors->has($name);

    $id = $id ?? $name;
@endphp

<div class="form-group mb-3" @if($type === 'password') x-data="{ show: false }" @endif>
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group">
        <input
            id="{{ $id }}"
            :type="{{ $type === 'password' ? 'show ? `text` : `password`' : '`' . $type . '`' }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'form-control ' . $sizeClass . ($inInvalid ? ' is-invalid' : '')
            ]) }}
            @if($required) required @endif
        >

        @if($type === 'password')
            <button type="button"
                    class="btn btn-outline-secondary border-start-0 shadow-none {{ $inInvalid ? ' is-invalid' : '' }}"
                    @click="show = !show">
                <x-lucide-eye x-show="!show" class="icon-size"/>
                <x-lucide-eye-off x-show="show" class="icon-size"/>
            </button>
        @endif

        <div class="invalid-feedback">@error($name) {{ $message }}   @enderror</div>

    </div>


</div>

<style>
    .icon-size {
        width: 1.25rem; /* Bootstrap equivalent of w-5 (20px) */
        height: 1.25rem;
    }

    /* Fix button border inconsistency */
    .input-group .btn {
        border-color: var(--bs-border-color); /* Match input border */
    }

    /* Fixes validation message positioning */
    .input-group .invalid-feedback {
        width: 100%;
    }

    .btn.is-invalid {
        border-color: var(--bs-form-invalid-border-color);
    }
</style>
