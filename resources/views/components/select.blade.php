@props([
    'label' => '',
    'id' => null,
    'name' => '',
    'placeholder' => '',
    'size' => 'md',   // sm|md|lg
    'required' => false,
    'append' => null,
])

@php
    use Illuminate\Support\Str;

    $sizeClass = match($size) {
        'sm' => 'form-select-sm',
        'lg' => 'form-select-lg',
        default => '',
    };

    $isInvalid = $errors->has($name);
    $id = $id ?? Str::random(10);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group">
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            aria-invalid="{{ $isInvalid ? 'true' : 'false' }}"
            {{ $attributes->merge([
                'class' => 'form-select ' . $sizeClass . ($isInvalid ? ' is-invalid' : ''),
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled selected hidden>{{ $placeholder }}</option>
            @endif

            {{ $slot }}
        </select>

        @if(!is_null($append))
            <span class="input-group-text rounded-start-0">{{ $append }}</span>
        @endif
    </div>

    <div class="invalid-feedback"> @error($name) {{ $message }}   @enderror</div>
</div>
