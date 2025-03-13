@props([
    'id' => Str::random(10),
    'name' => '',
    'label' => '',
    'preview' => false,
    'previewPosition' => 'right',
    'accepted' => '',
    'placeholder' => 'https://placehold.co/80',
    'value' => '',
])

<div class="mb-3" x-data="{
    previewUrl: '{{ $value ?: $placeholder }}',
    updatePreview(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = (e) => this.previewUrl = e.target.result;
            reader.readAsDataURL(file);
        }
    },
    checkInputValue() {
        let input = document.getElementById('{{ $id }}');
        if (input) {
            this.previewUrl = input.value || this.previewUrl;
        }
    }
}" x-init="checkInputValue()">

    @if($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <div
        class="d-flex  {{ $previewPosition === 'left' || $previewPosition === 'right' ? 'flex-row' : 'flex-column' }} align-items-{{ $previewPosition === 'top' || $previewPosition === 'bottom' ? 'center' : 'start' }}">

        @if($preview && $previewPosition === 'left')
            <div class="me-3 w-24 h-24">
                <img :src="previewUrl" alt="Preview" class="img-thumbnail"
                     style="width: 80px; height: 80px; object-fit: cover;">
            </div>
        @endif

        <input
            type="file"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            {{ $accepted ? "accept=$accepted" : '' }}
            x-on:change="updatePreview($event)"
        >

        @if($preview && $previewPosition === 'right')
            <div class="ms-3 w-24 h-24">
                <img :src="previewUrl" alt="Preview" class="img-thumbnail"
                     style="width: 80px; height: 80px; object-fit: cover;">
            </div>
        @endif
    </div>

    @if($preview && $previewPosition === 'top')
        <div class="mt-2 mb-2 w-24 h-24">
            <img :src="previewUrl" alt="Preview" class="img-thumbnail"
                 style="width: 80px; height: 80px; object-fit: cover;">
        </div>
    @endif

    @if($preview && $previewPosition === 'bottom')
        <div class="mt-2 w-24 h-24">
            <img :src="previewUrl" alt="Preview" class="img-thumbnail"
                 style="width: 80px; height: 80px; object-fit: cover;">
        </div>
    @endif


    <div class="invalid-feedback">@error($name) {{ $message }}  @enderror</div>

</div>
