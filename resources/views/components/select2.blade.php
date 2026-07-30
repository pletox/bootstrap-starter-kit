@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => 'Select an option',
    'multiple' => false,
    'size' => 'md', // sm, md, lg
    'error' => null,
    'containerClass' => '',
    'apiUrl' => null,
    'url' => null,
    'minimumInputLength' => 0,
    'allowClear' => true,
    'selectedOptions' => [],
    'media' => false,
])

@php
    $id = $id ?? Str::random(10); // Generate a unique ID if not provided
    $sizeClass = match($size) {
        'sm' => 'form-select-sm',
        'lg' => 'form-select-lg',
        default => 'form-select'
    };

    $wireModel = collect($attributes->whereStartsWith('wire:model'))->first();
    $ajaxUrl = $apiUrl ?? $url;

    $selectedOptions = collect($selectedOptions)->map(function ($label, $value) {
        if (is_array($label)) {
            return [
                'id' => $label['id'] ?? $value,
                'text' => $label['text'] ?? $label['name'] ?? $label['label'] ?? $label['id'] ?? $value,
                'icon' => $label['icon'] ?? null,
                'avatar' => $label['avatar'] ?? $label['initials'] ?? null,
                'avatar_url' => $label['avatar_url'] ?? $label['avatarUrl'] ?? null,
                'subtitle' => $label['subtitle'] ?? $label['description'] ?? null,
            ];
        }

        return [
            'id' => $value,
            'text' => $label,
            'icon' => null,
            'avatar' => null,
            'avatar_url' => null,
            'subtitle' => null,
        ];
    });
@endphp

<div class="form-group {{ $containerClass }}">
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="form-select {{ $sizeClass }} @error($name) is-invalid @enderror"
        @if($ajaxUrl) data-jp-select2-url="{{ $ajaxUrl }}" @endif
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes }}  {{-- Enables wire:model and x-model --}}
    >
        <option></option>
        @foreach($selectedOptions as $option)
            <option
                value="{{ $option['id'] }}"
                @if($option['icon']) data-icon="{{ $option['icon'] }}" @endif
                @if($option['avatar']) data-avatar="{{ $option['avatar'] }}" @endif
                @if($option['avatar_url']) data-avatar-src="{{ $option['avatar_url'] }}" @endif
                @if($option['subtitle']) data-subtitle="{{ $option['subtitle'] }}" @endif
                selected
            >{{ $option['text'] }}</option>
        @endforeach
        {{ $slot }}
    </select>


    <div class="invalid-feedback"> @error($name) {{ $message }}   @enderror</div>

</div>

<script type="module">
    onPageNavigated(() => {
        let select = $('#{{ $id }}');
        let syncingMissingOptions = false;
        let dispatchingNativeChange = false;

        if (select.data('select2')) {
            select.select2('destroy');
        }

        let dropdownParent = select.closest('.modal');

        let config = {
            dropdownParent: dropdownParent.length ? dropdownParent : $(document.body),
            theme: 'bootstrap-5',
            placeholder: @json($placeholder),
            allowClear: @json($allowClear),
            minimumInputLength: @json((int) $minimumInputLength),
            width: '100%'
        };

        function optionDataset(option) {
            let element = option.element;
            let dataset = element?.dataset || {};

            return {
                icon: option.icon || option.icon_class || dataset.icon || null,
                avatar: option.avatar || option.avatar_initials || option.initials || dataset.avatar || null,
                avatarSrc: option.avatar_url || option.avatarUrl || dataset.avatarSrc || null,
                subtitle: option.subtitle || option.description || dataset.subtitle || null,
            };
        }

        function renderIcon(icon) {
            if (!icon) return '';

            if (String(icon).startsWith('lucide-')) {
                return `<span class="jp-select2-media-icon"><i data-lucide="${escapeHtml(String(icon).replace('lucide-', ''))}" class="w-4 h-4"></i></span>`;
            }

            return `<span class="jp-select2-media-icon"><i class="${escapeHtml(icon)}"></i></span>`;
        }

        function renderAvatar(data) {
            if (data.avatarSrc) {
                return `<img src="${escapeHtml(data.avatarSrc)}" alt="" class="jp-select2-media-avatar">`;
            }

            if (data.avatar) {
                return `<span class="jp-select2-media-avatar">${escapeHtml(String(data.avatar).slice(0, 2).toUpperCase())}</span>`;
            }

            return '';
        }

        function renderMediaOption(option, compact = false) {
            if (!option.id) return escapeHtml(option.text || '');

            let data = optionDataset(option);
            let media = renderAvatar(data) || renderIcon(data.icon);

            if (!media && !data.subtitle) {
                return escapeHtml(option.text || '');
            }

            let html = `
                <div class="jp-select2-media-option ${compact ? 'is-compact' : ''}">
                    ${media}
                    <span class="jp-select2-media-copy">
                        <span class="jp-select2-media-title">${escapeHtml(option.text)}</span>
                        ${!compact && data.subtitle ? `<small class="jp-select2-media-subtitle">${escapeHtml(data.subtitle)}</small>` : ''}
                    </span>
                </div>
            `;

            setTimeout(() => window.refreshLucideIcons?.(), 0);

            return $(html);
        }

        @if($media)
            config.templateResult = function (option) {
                return renderMediaOption(option);
            };
            config.templateSelection = function (option) {
                return renderMediaOption(option, true);
            };
            config.escapeMarkup = function (markup) {
                return markup;
            };
        @endif

        @if($ajaxUrl)
            config.ajax = {
                url: @json($ajaxUrl),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    let results = data.results || data;

                    return {
                        results: results.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text || item.name || item.label,
                                disabled: item.disabled || item.is_disabled === true,
                                icon: item.icon || item.icon_class || null,
                                avatar: item.avatar || item.avatar_initials || item.initials || null,
                                avatar_url: item.avatar_url || item.avatarUrl || null,
                                subtitle: item.subtitle || item.description || null
                            };
                        }),
                        pagination: {
                            more: data.pagination?.more || false
                        }
                    };
                },
                cache: true
            };
        @endif

        select.select2(config);

        function normalizeItems(data) {
            let results = data.results || data;

            return results.map(function (item) {
                return {
                    id: item.id,
                    text: item.text || item.name || item.label || item.id,
                    disabled: item.disabled || item.is_disabled === true,
                    icon: item.icon || item.icon_class || null,
                    avatar: item.avatar || item.avatar_initials || item.initials || null,
                    avatar_url: item.avatar_url || item.avatarUrl || null,
                    subtitle: item.subtitle || item.description || null
                };
            });
        }

        function valuesArray(value) {
            if (!value) return [];

            return Array.isArray(value) ? value : [value];
        }

        function syncExternalModels() {
            let value = $(this).val();

            @if ($wireModel)
            if (window.Livewire) {
                Livewire.find('{{ $wireModel }}')?.set('{{ $wireModel }}', value);
            }
            @endif

            // Update Alpine.js model if x-model is used
            select.get(0).dispatchEvent(new Event('input', {bubbles: true}));
            dispatchingNativeChange = true;
            select.get(0).dispatchEvent(new Event('change', {bubbles: true}));
            dispatchingNativeChange = false;
        }

        function ensureSelectedOptions(rawValue = null) {
            @if(!$ajaxUrl)
                return;
            @else
                if (syncingMissingOptions) return;

                let values = valuesArray(rawValue ?? select.val());
                let missing = values.filter(function (value) {
                    return value !== null
                        && value !== undefined
                        && value !== ''
                        && !select.find('option[value="' + value + '"]').length;
                });

                if (!missing.length) return;

                syncingMissingOptions = true;

                $.ajax({
                    url: @json($ajaxUrl),
                    data: {
                        id: missing
                    },
                    success: function (data) {
                        normalizeItems(data).forEach(function (item) {
                            if (!select.find('option[value="' + item.id + '"]').length) {
                                let option = new Option(item.text, item.id, values.includes(String(item.id)) || values.includes(item.id), values.includes(String(item.id)) || values.includes(item.id));
                                option.disabled = item.disabled === true;
                                if (item.icon) option.dataset.icon = item.icon;
                                if (item.avatar) option.dataset.avatar = item.avatar;
                                if (item.avatar_url) option.dataset.avatarSrc = item.avatar_url;
                                if (item.subtitle) option.dataset.subtitle = item.subtitle;
                                select.append(option);
                            }
                        });

                        select.val(select.prop('multiple') ? values : values[0]);
                        select.trigger('change.select2');
                        syncExternalModels.call(select[0]);
                    },
                    complete: function () {
                        syncingMissingOptions = false;
                    }
                });
            @endif
        }

        select.off('change.jpSelect2Component').on('change.jpSelect2Component', function () {
            if (dispatchingNativeChange) return;

            ensureSelectedOptions();
            syncExternalModels.call(this);
        });

        select.off('select2:set-value.jpSelect2Component').on('select2:set-value.jpSelect2Component', function (event, value) {
            ensureSelectedOptions(value);
            select.val(value).trigger('change');
        });
    });
</script>
