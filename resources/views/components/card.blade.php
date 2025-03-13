@props([
    'title' => '',
    'subtitle' => '',
    'img' => '', // Optional image at the top
    'imgAlt' => 'Card Image',
    'bordered' => false,
    'shadow' => false,
    'rounded' => true,
    'class' => '',
    'bodyClass' => ''
])

<div {{ $attributes->merge(['class' => 'card ' . ($bordered ? 'border ' : '') . ($shadow ? 'shadow ' : '') . ($rounded ? 'rounded ' : '') . $class]) }}>

    @if($img)
        <img src="{{ $img }}" class="card-img-top" alt="{{ $imgAlt }}">
    @endif

    {{-- Custom Header Slot --}}
    @isset($header)
        <div class="card-header">
            {{ $header }}
        </div>
    @elseif($title)
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            @if($subtitle)
                <small class="text-muted">{{ $subtitle }}</small>
            @endif
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    {{-- Custom Footer Slot --}}
    @isset($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif

</div>
