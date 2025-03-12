@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => 'Select a date',
    'size' => 'md', // sm, md, lg
    'format' => 'd/m/Y', // Default date format
    'enableTime' => false, // Enable time picker
    'timeOnly' => false, // If true, only show time picker
     'range' => false, // If true, enable range mode
])

@php
    $id = $id ?? Str::random(10);
    $sizeClass = match($size) {
        'sm' => 'form-control-sm',
        'lg' => 'form-control-lg',
        default => 'form-control'
    };

    $wireModel = collect($attributes->whereStartsWith('wire:model'))->first();

    if($enableTime) $format = $format . ' H:i';
@endphp

<div class="form-group mb-3">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <input
        type="text"
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="form-control {{ $sizeClass }} @error($name) is-invalid @enderror"
        {{ $attributes }}  {{-- Enables wire:model and x-model --}}
    />


    <div class="invalid-feedback">@error($name) {{ $message }} @enderror</div>

</div>

<script>
    document.addEventListener('livewire:navigated', function () {
        let dateInput = document.getElementById('{{ $id }}');

        if (dateInput) {
            let flatpickrInstance = flatpickr(dateInput, {
                mode: '{{ $range ? "range" : "single" }}',
                dateFormat: '{{ $timeOnly ? "H:i" : $format }}',
                enableTime: {{ $enableTime || $timeOnly ? 'true' : 'false' }},
                noCalendar: {{ $timeOnly ? 'true' : 'false' }},
                allowInput: false,
                onChange: function (selectedDates, dateStr) {
                    // Update Livewire model only if wire:model is present
                    @if ($wireModel)
                    if (window.Livewire) {
                        Livewire.find('{{ $wireModel }}')?.set('{{ $wireModel }}', dateStr);
                    }
                    @endif

                    // Update Alpine.js model if x-model is used
                    dateInput.dispatchEvent(new Event('input', {bubbles: true}));
                }
            });
        }
    });
</script>
