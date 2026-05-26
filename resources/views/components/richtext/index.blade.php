@props([
    'id' => null,
    'name' => 'content',
    'label' => null,
    'placeholder' => 'Start writing...',
    'rows' => 5,
    'containerClass' => '',
    'uploadUrl' => null,
])

@php
    $id = $id ?? 'richtext-' . Str::random(8);
    $uploadUrl = $uploadUrl ?? url('/api/editor/upload');
@endphp

<div class="form-group {{ $containerClass }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        class="form-control @error($name) is-invalid @enderror"
        {{ $attributes }}
    >{{ $slot }}</textarea>

    <div class="invalid-feedback"> @error($name) {{ $message }} @enderror</div>
</div>

<script type="module">
    onPageNavigated(() => {
        $('#{{ $id }}').jpEditor({
            placeholder: @json($placeholder),
            uploadUrl: @json($uploadUrl)
        });
    });
</script>
