@extends('layouts.app')

@section('title', 'Categories')

@section('content')

    <div class="px-2">
        <x-page-header
            title="Manage Categories"
            subtitle="This is the standard text component for body copy and general content throughout your application."
        >
            <x-slot:actions>
                <x-button
                    color="light"
                    data-bs-toggle="collapse"
                    data-bs-target="#categoryFilters"
                    aria-expanded="true"
                    aria-controls="categoryFilters"
                >
                    <x-lucide-sliders-horizontal class="w-4 h-4"/>
                    <span class="d-none d-sm-inline-block">Filters</span>
                </x-button>

                <x-button data-bs-toggle="#categoryModal" id="add-category-btn" color="primary">
                    <x-lucide-plus class="w-4 h-4"/>
                    <span class="d-none d-sm-inline-block">Add Category</span>
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <x-card class="mt-2" id="bulk-actions" style="display:none">

            <div>You have selected <span class="fw-bold" id="selected-count"></span> entries</div>

            <div class="mt-1 d-flex flex-wrap gap-2">
                <x-button data-action="delete" color="danger" size="sm">
                    <x-lucide-trash-2 class="w-4 h-4"/>
                    <span>Delete Selected</span>
                </x-button>

                <x-button data-action="activate" color="success" size="sm">
                    <x-lucide-check class="w-4 h-4"/>
                    <span>Mark Active</span>
                </x-button>

                <x-button data-action="deactivate" color="warning" size="sm">
                    <x-lucide-pause class="w-4 h-4"/>
                    <span>Mark Inactive</span>
                </x-button>

                <x-button data-action="export" color="secondary" size="sm">
                    <x-lucide-download class="w-4 h-4"/>
                    <span>Export CSV</span>
                </x-button>
            </div>
        </x-card>

        <div id="categoryFilters" class="collapse">
            <x-card class="mt-3">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-4">
                        <x-input id="categorySearch" name="q" label="Search" placeholder="Search name or description"/>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <x-select2 id="categoryStatusFilter" name="active" label="Status" placeholder="All statuses">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </x-select2>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <x-datepicker id="categoryCreatedFrom" name="created_from" label="Created from" format="Y-m-d"/>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <x-datepicker id="categoryCreatedTo" name="created_to" label="Created to" format="Y-m-d"/>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <div class="d-flex gap-2">
                            <x-button id="applyCategoryFilters" type="button" color="primary" class="flex-fill">
                                <x-lucide-search class="w-4 h-4"/>
                                <span>Apply</span>
                            </x-button>
                            <x-button id="resetCategoryFilters" type="button" color="light">
                                <x-lucide-rotate-ccw class="w-4 h-4"/>
                            </x-button>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <x-card class="mt-3" body-class="px-0 pt-0 pb-1">
            <div class="table-responsive">
                <x-table id="categories-table" class="table table-borderless master-index-table categories-index-table">
                    <thead>
                    <x-table.row>
                        <x-table.header><input type="checkbox" id="select-all"></x-table.header>
                        <x-table.header>#</x-table.header>
                        <x-table.header>Name</x-table.header>
                        <x-table.header>Status</x-table.header>
                        <x-table.header>Description</x-table.header>
                        <x-table.header>Actions</x-table.header>
                    </x-table.row>
                    </thead>
                    <tbody>

                    </tbody>
                </x-table>
            </div>
        </x-card>

        @include('categories._form')

    </div>

@endsection

@push('js')
    <script type="module">
        $(function () {
            let form = useForm('#categoryForm');
            let modal = useModal('#categoryModal');

            let table = useDataTable('#categories-table', {
                url: route('categories.index'),
                columns: [
                    {data: 'select', name: 'select', orderable: false, searchable: false},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name_display', name: 'name'},
                    {data: 'status', name: 'active'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                filters: ['#categorySearch', '#categoryStatusFilter', '#categoryCreatedFrom', '#categoryCreatedTo'],
                mobileCards: {
                    enabled: true,
                    breakpoint: 768,
                    pageLength: 8,
                    renderCard: function (row) {
                        return `
                            <div class="jp-mobile-card" data-row-id="${row.id}">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="d-flex align-items-start gap-2 min-w-0">
                                        <input type="checkbox" class="form-check-input row-select mt-1" value="${row.id}">
                                        <div class="min-w-0">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <div class="fw-semibold text-truncate">${row.name}</div>
                                                ${row.status}
                                            </div>
                                            <div class="text-muted text-sm mt-1">${row.description || '<span class="fst-italic">No description</span>'}</div>
                                        </div>
                                    </div>

                                    ${row.action}
                                </div>
                            </div>
                        `;
                    }
                },
                bulk: {
                    enabled: true,
                    rowSelector: '.row-select',
                    masterSelector: '#select-all',
                    actionsSelector: '#bulk-actions',
                    paramName: 'ids',
                    onSelectionChange: function (ids, count) {
                        $('#selected-count').text(count);
                    },
                    onBulkAction: function (action, ids, done) {
                        if (action === 'export') {
                            exportSelectedCategories(ids);
                            return;
                        }

                        if (action === 'delete') {
                            Swal.fire({
                                title: 'Delete selected categories?',
                                text: 'This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Yes, delete them'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    runBulkAction(action, ids, done);
                                }
                            });
                            return;
                        }

                        runBulkAction(action, ids, done);
                    }
                }
            })

            $('#applyCategoryFilters').on('click', function () {
                table.draw();
            });

            $('#categorySearch').on('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    table.draw();
                }
            });

            $('#resetCategoryFilters').on('click', function () {
                $('#categorySearch, #categoryCreatedFrom, #categoryCreatedTo').val('');
                $('#categoryStatusFilter').val(null).trigger('change');
                table.draw();
            });

            function runBulkAction(action, ids, done) {
                axios.post(route('categories.bulk-action'), {
                    action: action,
                    ids: ids
                }).then((response) => {
                    toast.success(response.data.message);
                    $('#select-all').prop('checked', false).prop('indeterminate', false);
                    done();
                }).catch(() => {
                    toast.error('Bulk action failed. Please reload and try again.');
                });
            }

            function exportSelectedCategories(ids) {
                axios.post(route('categories.export'), {
                    ids: ids
                }, {
                    responseType: 'blob'
                }).then((response) => {
                    downloadBlob(response.data, 'categories.csv');
                    toast.success('Selected categories exported.');
                }).catch(() => {
                    toast.error('Export failed. Please reload and try again.');
                });
            }

            $('#add-category-btn').click(function () {
                form.reset();
                modal.open('Create New Category');
            });

            $('#categoryForm').on('submit', function (e) {
                e.preventDefault();

                form.post("{{ route('categories.storeOrUpdate') }}", {
                    onComplete: (response) => {
                        modal.close();
                        form.reset();
                        table.upsertRow(response.data.item, {mode: 'prepend'});
                    }
                });

            });

            $('body').on('click', '.editCategory', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                axios.get(route('categories.edit', {category: id})).then((response) => {
                    form.fill(response.data);
                    modal.open('Edit Category');
                });
            });

            $('body').on('click', '.deleteCategory', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.easyDelete({
                    url: route('categories.delete', {category: id}),
                    confirmationMessage: 'Do you really want to delete this category?',
                    onComplete: () => {
                        table.removeRow(id);
                    }
                })
            });

        });
    </script>
@endpush
