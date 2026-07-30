@props([
    'label' => '',
    'id' => null,
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'size' => 'md',
    'required' => false,
    'sublabel' => '',
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

<div class="form-group" @if($type === 'password') x-data="{ show: false }" @endif>
    @if($label)
        <label for="{{ $id }}" class="form-label mb-1">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
            @if($sublabel)
                <small class="text-muted ms-1">[{{ $sublabel }}]</small>
            @endif
        </label>
    @endif

    <div class="input-group {{ $type === 'password' ? 'app-password-field' : '' }}">
        <input
            id="{{ $id }}"
            :type="{{ $type === 'password' ? 'show ? `text` : `password`' : '`' . $type . '`' }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
               'class' => 'form-control text-sm p-2 rounded ' . $sizeClass . ($type === 'password' ? ' app-password-input' : '') . ($inInvalid ? ' is-invalid' : '')
           ]) }}
            @if($required) required @endif
        >

        @if($type === 'password')
            <button type="button"
                    class="app-password-toggle {{ $inInvalid ? ' is-invalid' : '' }}"
                    @click="show = !show"
                    :aria-label="show ? 'Hide password' : 'Show password'"
                    :aria-pressed="show">
                <x-lucide-eye x-show="!show" class="app-password-icon"/>
                <x-lucide-eye-off x-cloak x-show="show" class="app-password-icon"/>
            </button>
        @endif

        <div class="invalid-feedback">@error($name) {{ $message }}   @enderror</div>

    </div>


</div>
