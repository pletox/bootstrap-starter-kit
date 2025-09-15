$.fn.loadFragment = function (url, data = {}) {
    let target = this; // The div where content will be loaded

    target.html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Loading...</div>');

    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function (response) {
            target.html(response);
        },
        error: function () {
            target.html('<div class="text-danger">Failed to load content.</div>');
        }
    });

    return this;
};

(function ($) {
    $.fn.comboVal = function (value) {
        const el = this.get(0);

        if (!el || !Alpine) return null;

        const component = Alpine.closestDataStack(el)?.[0];
        if (!component) return null;

        // GET: Return selected values
        if (value === undefined) {
            return component.multiple
                ? component.selected
                : component.selected[0] || null;
        }

        // SET: Apply new selected values
        if (component.multiple) {
            component.selected = Array.isArray(value) ? value : [value];
        } else {
            component.selected = value === null || value === undefined || value === '' ? [] : [value];
        }

        component.search = '';
        component.open = false;

        // If API-backed, reload visible items so labels show
        if (component.apiUrl && typeof component.fetchMoreOptions === 'function') {
            component.page = 1;
            component.options = [];
            component.hasMore = true;

            Alpine.nextTick(() => {
                component.fetchMoreOptions();
            });
        }

        return this;
    };
})(jQuery);


(function ($) {
    $.fn.comboLabel = function () {
        const el = this.get(0);
        if (!el || !el.__x) return null;
        const selected = el.__x.data.selected;
        const slot = el.querySelector('[x-ref="optionsSlot"]');
        return selected.map(val => {
            const opt = slot.querySelector(`[data-value='${val}']`);
            return opt?.dataset?.label || val;
        });
    }
})(jQuery);

(function ($) {
    $.fn.richVal = function (value) {
        const el = this.get(0);
        if (!el || !Alpine) return null;

        const component = Alpine.closestDataStack(el)?.[0];
        if (!component) return null;

        // GET: Return current HTML content
        if (value === undefined) {
            return component.html;
        }

        // SET: Update HTML content
        component.html = value ?? '';

        Alpine.nextTick(() => {
            if (component.$refs?.editor) {
                component.$refs.editor.innerHTML = component.html;
            }
        });

        return this;
    };

})(jQuery);

(function ($) {
    $.fn.colorVal = function (value) {
        const el = this.get(0);

        if (!el || !Alpine) return null;

        const component = Alpine.closestDataStack(el)?.[0];
        if (!component) return null;

        // GET: Return selected values
        if (value === undefined) {
            return component.multiple
                ? component.selected
                : component.selected[0] || null;
        }

        // SET: Update selected colors
        if (component.multiple) {
            component.selected = Array.isArray(value) ? value : [value];
        } else {
            component.selected = value === null || value === undefined || value === '' ? [] : [value];
        }

        component.search = '';
        component.open = false;

        return this;
    };
})(jQuery);


window.setDataTableParams = function (tableId, params = {}) {
    window.datatableParams = window.datatableParams || {};
    window.datatableParams[tableId] = {
        ...(window.datatableParams[tableId] || {}),
        ...params
    };
};

window.useModal = function (modalSelector) {
    const $modal = $(modalSelector);

    return {
        open(title = '') {
            if (title) $modal.find('.modal-title').html(title);
            $modal.modal('show');
        },
        show() {
            $modal.modal('show');
        },
        close() {
            $modal.modal('hide');
        },
        setTitle(title) {
            $modal.find('.modal-title').html(title);
        },
        el() {
            return $modal;
        }
    };
};

window.useForm = function (formSelector) {
    const $form = $(formSelector);

    return {
        reset() {
            $form[0]?.reset();
            this.clearSelects();
            this.clearEditors();
            $form.find('input[type="hidden"]').not('[name="_token"]').val('');
        },
        clearSelects() {
            $form.find('select').each(function () {
                $(this).val(null).trigger('change');
            });
        },
        clearEditors() {
            $form.find('textarea').each(function () {
                if (this.jpEditor) {
                    this.jpEditor.root.innerHTML = '';
                    $(this).val('').trigger('change');
                }
            });
        },
        fill(data = {}) {
            Object.entries(data).forEach(([key, value]) => {
                const $field = $form.find(`[name="${key}"]`);
                if ($field.length) {
                    window.setInputFieldVal($field, value);
                }
            });
        },
        callUrl(type, url, options, file = true) {
            let data = new FormData($form[0]);
            $.easyAjax({
                url: url,
                container: formSelector,
                type: type,
                disableButton: true,
                blockUI: true,
                data: data,
                file: file,
                onComplete: options.onComplete
            });
        },
        post(url, options) {
            this.callUrl('POST', url, options)
        },
        put(url, options) {
            this.callUrl('PUT', url, options, false)
        },
        delete(url, options) {
            this.callUrl('DELETE', url, options)
        },
        el() {
            return $form;
        }
    };
};

window.getInputFieldVal = function (inputField) {
    const $input = $(inputField);

    // --- File input (return FileList or null) ---
    if ($input.attr('type') === 'file') {
        return $input[0].files.length ? $input[0].files : null;
    }

    // --- jpEditor (Quill wrapper) ---
    if ($input[0] && $input[0].jpEditor) {
        const quill = $input[0].jpEditor;
        return quill.root.innerHTML;
    }

    // --- Checkbox ---
    if ($input.is(':checkbox')) {
        return $input.is(':checked');
    }

    // --- Radio group ---
    if ($input.attr('type') === 'radio') {
        let name = $input.attr('name');
        if (name) {
            return $('input[type="radio"][name="' + name + '"]:checked').val() || null;
        }
        return $input.is(':checked') ? $input.val() : null;
    }

    // --- Flatpickr Datepicker ---
    if ($input[0] && $input[0]._flatpickr) {
        const fp = $input[0]._flatpickr;
        return fp.selectedDates.length ? fp.input.value : null;
    }

    // --- Select2 ---
    if ($input.hasClass('select2-hidden-accessible')) {
        return $input.val();
    }

    // --- Default input fallback ---
    return $input.val();
};


window.setInputFieldVal = function (inputField, value) {
    const $input = $(inputField);

    // --- File input (skip) ---
    if ($input.attr('type') === 'file') {
        return;
    }

    // --- ComboBox, RichEditor, ColorPicker handling ---
    var comboWrapper = $input.closest('[x-data^="comboBox"]');
    var richWrapper = $input.closest('[x-data^="richTextEditor"]');
    var colorWrapper = $input.closest('[x-data^="colorSelector"]');

    if (comboWrapper.length && typeof comboWrapper[0].comboVal === 'function') {
        comboWrapper[0].comboVal(value);
        return;
    }

    if (richWrapper.length && typeof richWrapper[0].richVal === 'function') {
        richWrapper[0].richVal(value);
        return;
    }

    if (colorWrapper.length && typeof colorWrapper[0].colorVal === 'function') {
        colorWrapper[0].colorVal(value);
        return;
    }

    // --- jpEditor (Quill wrapper) ---
    if ($input[0] && $input[0].jpEditor) {
        const quill = $input[0].jpEditor;
        if (value) {
            quill.root.innerHTML = value;
        } else {
            quill.root.innerHTML = '';
        }
        $input.val(quill.root.innerHTML).trigger('change');
        return;
    }

    // --- Checkbox ---
    if ($input.is(':checkbox')) {
        $input.prop('checked', !!value).trigger('change');
        return;
    }

    // --- Radio group ---
    if ($input.attr('type') === 'radio') {
        let name = $input.attr('name');
        if (name) {
            $('input[type="radio"][name="' + name + '"]').each(function () {
                $(this).prop('checked', $(this).val() == value);
            }).trigger('change');
        } else {
            $input.prop('checked', $input.val() == value).trigger('change');
        }
        return;
    }

    // --- Flatpickr Datepicker ---
    if ($input[0] && $input[0]._flatpickr) {
        const fp = $input[0]._flatpickr;
        if (value) {
            fp.setDate(value, true);
        } else {
            fp.clear();
        }
        return;
    }

    // --- Select2 ---
    if ($input.hasClass('select2-hidden-accessible')) {
        const isMultiple = $input.prop('multiple');
        const selectedVals = Array.isArray(value) ? value : [value];
        const existing = $input.find('option').map((_, o) => $(o).val()).get();
        const missing = selectedVals.filter(v => !existing.includes(v));
        const url = $input.attr('data-jp-select2-url');

        const applyValue = () => {
            $input.val(isMultiple ? selectedVals : selectedVals[0]).trigger('change');
        };

        if (!url || missing.length === 0) {
            applyValue();
        } else {
            $.ajax({
                url: url,
                data: {id: missing},
                success: function (data) {
                    const results = data.results || data;
                    results.forEach(item => {
                        const id = item.id;
                        const text = item.text || item.name || item.label;
                        if (!$input.find(`option[value="${id}"]`).length) {
                            $input.append(`<option selected value="${id}">${text}</option>`);
                        }
                    });
                    applyValue();
                }
            });
        }

        return;
    }

    // --- Default input fallback ---
    $input.val(value).trigger('change');
};

window.hideInputField = function (inputField) {
    // Check if it's inside a ComboBox
    var comboWrapper = inputField.closest('[x-data^="comboBox"]');
    var richWrapper = inputField.closest('[x-data^="richTextEditor"]');
    var colorWrapper = inputField.closest('[x-data^="colorSelector"]');
    var inputWrapper = inputField.closest('.form-group');

    inputField.attr('disabled', true);

    if (comboWrapper.length && typeof comboWrapper.comboVal === 'function') {
        // Use custom comboVal setter
        comboWrapper.hide();
    } else if (richWrapper.length && typeof richWrapper.richVal === 'function') {
        richWrapper.hide();
    } else if (colorWrapper.length && typeof colorWrapper.colorVal === 'function') {

        colorWrapper.hide();
    } else {
        // Regular input
        inputWrapper.hide();
    }
}

window.showInputField = function (inputField) {
    // Check if it's inside a ComboBox
    var comboWrapper = inputField.closest('[x-data^="comboBox"]');
    var richWrapper = inputField.closest('[x-data^="richTextEditor"]');
    var colorWrapper = inputField.closest('[x-data^="colorSelector"]');
    var inputWrapper = inputField.closest('.form-group');

    inputField.attr('disabled', false);

    if (comboWrapper.length && typeof comboWrapper.comboVal === 'function') {
        // Use custom comboVal setter
        comboWrapper.show();
    } else if (richWrapper.length && typeof richWrapper.richVal === 'function') {
        richWrapper.show();
    } else if (colorWrapper.length && typeof colorWrapper.colorVal === 'function') {

        colorWrapper.show();
    } else {
        // Regular input
        inputWrapper.show();
    }
}

window.buildParamsFromFilters = function (filters) {
    const params = {};
    $(filters.join(',')).each(function () {
        const $el = $(this);
        let key = $el.attr('name') || $el.attr('id'); // Prefer name over id
        if (!key) return; // skip if no id/name

        // Remove "filters[...]" brackets for cleaner param keys if needed
        // key = key.replace(/\[(.*?)\]/g, '.$1'); // filters[end_date] => filters.end_date

        params[key] = $el.val();
    });
    return params;
}

window.jpLoadSelect2Values = function (id, rawValues, callback = null) {
    const $select = $('#' + id);
    const select2Data = $select.data('select2') || $select.select2('data');

    console.log(select2Data);


    if (!select2Data || rawValues == null) return;


    const ajaxOptions = select2Data.options.options.ajax;
    if (!ajaxOptions || !ajaxOptions.url) return;

    const url = ajaxOptions.url;
    const values = Array.isArray(rawValues) ? rawValues : [rawValues];

    const existing = $select.find('option').map(function () {
        return $(this).val();
    }).get();

    const missing = values.filter(v => !existing.includes(v));

    const setValue = () => {
        $select.val(Array.isArray(rawValues) ? rawValues : [rawValues]).trigger('change');
        if (callback) callback();
    };

    if (missing.length === 0) {
        setValue();
        return;
    }


    $.ajax({
        url: url,
        data: {id: missing},
        success: function (data) {
            const items = data.results || data;
            items.forEach(item => {
                const id = item.id;
                const text = item.text || item.name || item.label;
                if (!$select.find(`option[value="${id}"]`).length) {
                    $select.append(`<option selected value="${id}">${text}</option>`);
                }
            });
            setValue();
        }
    });
};

$.fn.jpDataTable = function (options = {}) {
    let tableInstance = null;

    this.each(function () {
        const $el = $(this);

        // Allow filters to be passed in options OR from data attribute
        const filterSelectors = options.filters
            || ($el.data('filters') ? $el.data('filters').split(',') : []);

        const ajaxUrl = options.ajax?.url || $el.data('url');

        // --- Bulk options (new) ---
        // default markup conventions:
        // - row checkbox selector e.g. '.row-select' placed inside each <tr>
        // - master checkbox selector e.g. '#select-all' (can be anywhere)
        // - actions container selector e.g. '#bulk-actions' which contains buttons with data-action attr
        const bulkOpts = $.extend({
            enabled: false,                 // enable bulk behaviour
            rowSelector: '.row-select',     // checkbox inside each row
            masterSelector: '#select-all',  // master checkbox to toggle visible rows
            actionsSelector: '#bulk-actions', // container for bulk action buttons (will be shown/hidden)
            paramName: 'ids',               // parameter name to send to server
            ajaxUrl: null,                  // if provided, a POST to this url will be made for actions
            ajaxMethod: 'POST',
            ajaxHeaders: {},                // extra headers if needed
            onBulkAction: null              // callback(action, ids, done) if provided, wont do ajax
        }, options.bulk || {});

        const defaultOptions = {
            processing: true,
            serverSide: true,
            responsive: true,
            stateSave: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            columnDefs: [
                {
                    targets: 0,       // first column = checkbox
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'desc']], // make second column default sort
            ajax: {
                url: ajaxUrl,
                data: function (d) {
                    filterSelectors.forEach(selector => {
                        const $input = $(selector.trim());
                        const name = $input.attr('name') || $input.attr('id');
                        if (name) {
                            d[name] = $input.val();
                        }
                    });
                }
            }
        };

        const mergedOptions = $.extend(true, {}, defaultOptions, options);

        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable($el)) {
            $el.DataTable().destroy();
            $el.empty(); // Optional: Clear headers/rows
            console.log('destroy');
        }

        // Initialize and store instance
        tableInstance = $el.DataTable(mergedOptions);

        filterSelectors.forEach(selector => {
            $(selector.trim()).on('change', function () {
                console.log('here');
                tableInstance.draw();
                if (options.onFiltersChange)
                    options.onFiltersChange();
            });
        });

        // Store instance on the element for later access if needed
        $el.data('jp-datatable-instance', tableInstance);

        // ----------------------------
        // Bulk actions implementation
        // ----------------------------
        if (bulkOpts.enabled) {
            // selected ids set
            const selected = new Set();

            // utility: read ids from currently checked row selectors (only visible rows)
            function collectVisibleSelectedIds() {
                const ids = [];
                $el.find('tbody').find(bulkOpts.rowSelector).each(function () {
                    const $cb = $(this);
                    if ($cb.is(':checked')) {
                        const id = $cb.val() ?? $cb.data('id') ?? $cb.attr('value');
                        if (id !== undefined && id !== null) ids.push(id);
                    }
                });
                return ids;
            }

            // update the visual bulk actions container (show/hide) and master checkbox state
            function refreshBulkUI() {
                const ids = collectVisibleSelectedIds();

                // Show/hide actions
                const $actions = $(bulkOpts.actionsSelector);
                if ($actions.length) {
                    if (ids.length > 0) $actions.show();
                    else $actions.hide();
                }

                // Master checkbox sync
                const $master = $(bulkOpts.masterSelector);
                if ($master.length) {
                    const $visibleCbs = $el.find('tbody').find(bulkOpts.rowSelector);
                    const total = $visibleCbs.length;
                    const checked = $visibleCbs.filter(':checked').length;
                    $master.prop('checked', total > 0 && checked === total);
                    $master.prop('indeterminate', checked > 0 && checked < total);
                }

                // 🔹 Call selection change handler if defined
                if (typeof bulkOpts.onSelectionChange === 'function') {
                    bulkOpts.onSelectionChange(ids, ids.length);
                }
            }


            // bind checkbox events for visible rows
            function bindRowCheckboxes() {
                // unbind first to avoid duplicate handlers
                $el.find('tbody').off('change.jpRowSelect', bulkOpts.rowSelector);
                $el.find('tbody').on('change.jpRowSelect', bulkOpts.rowSelector, function () {
                    const $cb = $(this);
                    const id = $cb.val() ?? $cb.data('id') ?? $cb.attr('value');
                    if ($cb.is(':checked')) selected.add(String(id));
                    else selected.delete(String(id));
                    refreshBulkUI();
                });
            }

            // master checkbox behavior
            function bindMasterCheckbox() {
                $(bulkOpts.masterSelector).off('change.jpMasterSelect').on('change.jpMasterSelect', function () {
                    const $master = $(this);
                    const checked = $master.is(':checked');
                    $el.find('tbody').find(bulkOpts.rowSelector).each(function () {
                        $(this).prop('checked', checked).trigger('change');
                    });
                });
            }

            // handle action clicks
            function bindActionButtons() {
                const $actions = $(bulkOpts.actionsSelector);
                $actions.off('click.jpBulkAction', '[data-action]').on('click.jpBulkAction', '[data-action]', function (e) {
                    e.preventDefault();
                    const action = $(this).data('action');
                    const customUrl = $(this).data('url'); // <--- custom url support
                    const ids = collectVisibleSelectedIds();
                    if (!ids.length) return;

                    // If user provided callback
                    if (typeof bulkOpts.onBulkAction === 'function') {
                        const done = () => tableInstance.draw(false);
                        bulkOpts.onBulkAction(action, ids, done, customUrl);
                        return;
                    }

                    // Determine url: prefer button data-url, else global bulk.ajaxUrl
                    const url = customUrl || bulkOpts.ajaxUrl;
                    if (!url) return;

                    const payload = {};
                    payload[bulkOpts.paramName] = ids;

                    $.ajax({
                        url: url,
                        method: bulkOpts.ajaxMethod || 'POST',
                        headers: bulkOpts.ajaxHeaders || {},
                        data: payload,
                        traditional: true,
                        success: function (resp) {
                            if (typeof options.onBulkSuccess === 'function') {
                                options.onBulkSuccess(resp, action, ids, url);
                            } else {
                                tableInstance.draw(false);
                            }
                        },
                        error: function (xhr) {
                            if (typeof options.onBulkError === 'function') {
                                options.onBulkError(xhr, action, ids, url);
                            } else {
                                console.error('Bulk action failed', xhr);
                            }
                        }
                    });
                });
            }


            // initial binds + redraw hook to rebind after table draw/paging
            bindRowCheckboxes();
            bindMasterCheckbox();
            bindActionButtons();
            refreshBulkUI();

            // Rebind on table draw (paging/search/ordering)
            tableInstance.on('draw', function () {
                bindRowCheckboxes();
                refreshBulkUI();
            });

            // Optionally expose selected ids on element
            $el.data('jp-datatable-selected-ids', function () {
                return collectVisibleSelectedIds();
            });
        }

    });

    return tableInstance;
};


$.fn.jpSelect2 = function (options = {}) {
    return this.each(function () {
        const $el = $(this);

        // If it's a method call like 'reload'
        if (options.reload) {
            const {reload, ...newOptions} = options;

            $el.select2('destroy');
            $el.removeData('jp-select2-initialized');

            setTimeout(() => {
                $el.jpSelect2(newOptions);
            }, 10);

            return;
        }

        if ($el.data('jp-select2-initialized')) return;
        $el.data('jp-select2-initialized', true);

        const dataUrl = $el.attr('data-url');
        const placeholder = $el.attr('data-placeholder') || $el.attr('placeholder');
        const isMultiple = $el.prop('multiple');
        const url = options.url || dataUrl || false;

        if (!isMultiple && !$el.find('option[value=""]').length) {
            $el.prepend('<option value=""></option>');
        }

        // Store the URL for later use
        if (url) {
            $el.attr('data-jp-select2-url', url);
        }


        const config = {
            placeholder: placeholder || 'Select option',
            closeOnSelect: !isMultiple,
            allowClear: !isMultiple,
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $el.closest('.modal').length ? $el.closest('.modal') : $(document.body),
            templateResult: function (data) {
                if (data.loading) return data.text;
                let $result = $('<span>' + data.text + '</span>');
                if (data.disabled) {
                    $result.css('opacity', '0.5');
                }
                return $result;
            },
            ...options
        };

        if (url) {
            config.ajax = {
                url: url,
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term,
                    page: params.page || 1
                }),
                processResults: data => {
                    const results = data.results || data;
                    return {
                        results: results.map(item => ({
                            id: item.id,
                            text: item.text || item.name || item.label,
                            disabled: item.is_disabled === true // 👈 boolean flag
                        })),
                        pagination: {
                            more: data.pagination?.more || false
                        }
                    };
                },
                cache: true
            };
        }

        $el.select2(config);

        // MutationObserver for .val + trigger('change') calls
        const observer = new MutationObserver(() => {
            const newVal = $el.val();
            if (!newVal) return;

            const values = Array.isArray(newVal) ? newVal : [newVal];
            const missing = values.filter(v => !$el.find(`option[value="${v}"]`).length);

            if (missing.length && url) {
                $.ajax({
                    url: url,
                    data: {id: missing},
                    success: function (data) {
                        const results = data.results || data;
                        results.forEach(item => {
                            const id = item.id;
                            const text = item.text || item.name || item.label;
                            if (!$el.find(`option[value="${id}"]`).length) {
                                $el.append(`<option selected value="${id}">${text}</option>`);
                            }
                        });
                        $el.trigger('change.select2');
                    }
                });
            }
        });

        observer.observe($el[0], {
            attributes: true,
            attributeFilter: ['value']
        });

        // Fallback: manual onchange listener for developer use
        $el.on('change', function () {
            const value = $(this).val();
            const missing = Array.isArray(value) ? value : [value];
            const notFound = missing.filter(v => !$el.find(`option[value="${v}"]`).length);

            if (notFound.length && url) {
                $.ajax({
                    url: url,
                    data: {id: notFound},
                    success: function (data) {
                        const results = data.results || data;
                        results.forEach(item => {
                            const id = item.id;
                            const text = item.text || item.name || item.label;
                            if (!$el.find(`option[value="${id}"]`).length) {
                                $el.append(`<option selected value="${id}">${text}</option>`);
                            }
                        });
                        $el.trigger('change.select2');
                    }
                });
            }
        });
    });
};

$.fn.jpDatepicker = function (options = {}) {
    const defaults = {
        mask: false,
        disableTyping: true,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050',
        showAnim: 'fadeIn',
        showClearButton: true,
        icon: 'bi bi-calendar'
    };

    const settings = {...defaults, ...options};

    return this.each(function () {
        const $original = $(this);
        if ($original.data('jpDatepickerInited')) return;
        $original.data('jpDatepickerInited', true);

        $original.attr('autocomplete', 'off');

        // Build wrapper
        const $wrapper = $('<div class="position-relative w-100"></div>');
        const $icon = $(`<i class="${settings.icon} position-absolute" style="left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #495057;"></i>`);
        const $clearBtn = $('<span class="jp-datepicker-clear position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-weight: bold; display: none;">&times;</span>');

        // Style input
        $original[0].style.setProperty('padding-left', '2.0rem', 'important');
        $original.wrap($wrapper);
        $original.before($icon);
        $original.after($clearBtn);

        // Input masking
        if (settings.mask) {
            $original.on('input', function () {
                let v = $(this).val().replace(/\D/g, '').substring(0, 8);
                if (v.length >= 5) v = v.replace(/(\d{2})(\d{2})(\d{0,4})/, '$1/$2/$3');
                else if (v.length >= 3) v = v.replace(/(\d{2})(\d{0,2})/, '$1/$2');
                $(this).val(v);
            });
        }

        // Disable typing
        if (settings.disableTyping) {
            $original.on('keydown', function (e) {
                e.preventDefault();
            });
        }

        // Init datepicker
        $original.datepicker({
            ...settings,
            onSelect: function () {
                $original.trigger('change');
                $clearBtn.show();
            }
        });

        // Clear button
        $clearBtn.on('click', function () {
            $original.val('');
            $original.datepicker('setDate', null);
            $original.trigger('change');
            $clearBtn.hide();
        });

        // Watch for manual input change
        $original.on('change', function () {
            if ($(this).val()) $clearBtn.show();
            else $clearBtn.hide();
        });

        // Refresh method
        $original.on('refresh:clear', function () {
            if ($original.val()) $clearBtn.show();
            else $clearBtn.hide();
        });

        // Initial state
        if ($original.val()) $clearBtn.show();
    });
};

(function ($) {
    $.fn.jpTabs = function (options) {
        // Default options
        let settings = $.extend({
            ajax: {},          // { tabId: "url" }
            reload: false,     // reload AJAX every time tab is shown
            onTabChange: null  // callback(tabId, relatedTarget)
        }, options);

        return this.each(function () {
            let $tabs = $(this);

            // Listen for tab shown event
            $tabs.on('shown.bs.tab', function (e) {
                let $tab = $(e.target);
                let tabId = $tab.attr('id');
                let targetId = $tab.attr('data-bs-target');
                let $pane = $(targetId);

                // Fire callback
                if (typeof settings.onTabChange === "function") {
                    settings.onTabChange(tabId, e.relatedTarget);
                }

                // Load AJAX if URL is defined
                if (settings.ajax[tabId]) {
                    if (settings.reload || $pane.is(':empty')) {
                        // Bootstrap spinner
                        $pane.html(`
                            <div class="d-flex justify-content-center align-items-center p-3">
                                <div class="spinner-border text-secondary me-2" role="status" style="width:1.5rem;height:1.5rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="text-muted small">Loading...</span>
                            </div>
                        `);

                        $.get(settings.ajax[tabId], function (data) {
                            $pane.html(data);
                        }).fail(function () {
                            $pane.html('<div class="p-3 text-danger small">⚠ Failed to load data.</div>');
                        });
                    }
                }
            });
        });
    };
})(jQuery);

(function ($) {
    $.fn.jpEditor = function (options) {
        const settings = $.extend({
            theme: 'snow',
            placeholder: 'Start writing...',
            uploadUrl: '/api/editor/upload', // Laravel API route
            csrfToken: $('meta[name="csrf-token"]').attr('content'),
            modules: {
                toolbar: {
                    container: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'blockquote', 'code-block'],
                        [{ 'align': [] }],
                        ['image']
                    ],
                    handlers: {
                        image: function () {
                            let fileInput = this.container.querySelector('input.ql-image[type=file]');
                            if (fileInput == null) {
                                fileInput = document.createElement('input');
                                fileInput.setAttribute('type', 'file');
                                fileInput.setAttribute('accept', 'image/*');
                                fileInput.classList.add('ql-image');
                                fileInput.style.display = 'none';

                                fileInput.addEventListener('change', () => {
                                    const file = fileInput.files[0];
                                    if (file != null) {
                                        let formData = new FormData();
                                        formData.append('image', file);

                                        fetch(settings.uploadUrl, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': settings.csrfToken
                                            },
                                            body: formData
                                        })
                                            .then(res => res.json())
                                            .then(data => {
                                                if (data.url) {
                                                    const range = this.quill.getSelection();
                                                    this.quill.insertEmbed(range.index, 'image', data.url);
                                                }
                                            })
                                            .catch(err => console.error(err));
                                    }
                                });

                                this.container.appendChild(fileInput);
                            }
                            fileInput.click();
                        }
                    }
                }
            }
        }, options);

        return this.each(function () {
            let $textarea = $(this);

            // prevent duplicate init
            if ($textarea.attr("data-jp-editor")) return;

            let quillId = 'jp-editor-' + Math.random().toString(36).substring(2, 9);
            let $editorContainer = $('<div/>').attr('id', quillId).insertAfter($textarea);
            $textarea.hide().attr("data-jp-editor", "true");

            let quill = new Quill(`#${quillId}`, settings);

            quill.root.innerHTML = $textarea.val();
            quill.on('text-change', function () {
                $textarea.val(quill.root.innerHTML).trigger('change');
            });

            $textarea[0].jpEditor = quill;
        });
    };

    // --- Destroy Method ---
    $.fn.jpEditorDestroy = function () {
        return this.each(function () {
            let $textarea = $(this);
            let quill = $textarea[0].jpEditor;

            if (quill) {
                $(quill.root).closest('.ql-container').remove();
                $textarea.show().removeAttr("data-jp-editor");
                delete $textarea[0].jpEditor;
            }
        });
    };
})(jQuery);



// Auto-init on a page load
$(document).on('click', '[data-toggle-block]', function (e) {
    e.preventDefault();
    const target = $(this).data('toggle-block');
    if (target) $(target).toggle();
});


window.onPageNavigated = function (callback) {
    document.addEventListener('livewire:navigated', callback);
};
