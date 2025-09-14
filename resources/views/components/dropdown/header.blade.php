@props([
    'class' => '',
])

<li>
    <h6 {{ $attributes->merge([
        'class' => "dropdown-header $class"
    ]) }}>
        {{ $slot }}
    </h6>
</li>
