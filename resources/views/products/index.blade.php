@extends('layouts.app')

@section('title', 'Equipments')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Equipments</h3>

            <x-button data-bs-toggle="#productModal" id="add-product-btn" color="dark">
                <x-lucide-plus class="w-4 h-4"/>
                <span class="d-none d-sm-inline-block">Add Equipment</span>
            </x-button>
        </div>

        <x-card class="mt-3" body-class="px-0 pt-0 pt-sm-3">
            <div class="table-responsive">
                <table id="products-table" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </x-card>

     @include('products._form')

    </div>

@endsection

@push('js')
    <script type="module">
        $(function () {
            new DataTable('#products-table').destroy();

            let table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'category.name', name: 'category'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: [],
            });

            $('#add-product-btn').click(function () {
                $('#id').val('');
                $('#productForm').trigger("reset");
                $('#productModal .model-title').html("Create New Equipment");
                $('.form-select').trigger('change');
                $('#productModal').modal('show');
            });

            $('#productForm').on('submit', function (e) {
                e.preventDefault();

                var data = new FormData($('#productForm')[0]);


                $.easyAjax({
                    url: "{{ route('products.storeOrUpdate') }}",
                    container: '#productForm',
                    type: "POST",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: () => {
                        $('#productModal').modal('hide');
                        $('#modelHeading').html("Create New Product");
                        $('#productForm')[0].reset();

                        table.draw(false);
                    }
                })

            });

            $('body').on('click', '.editProduct', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                axios.get(route('products.edit', {product: id})).then((response) => {
                    $('#productForm')[0].reset();
                    $('#productModal .model-title').html("Edit Product");
                    $('#productModal').modal('show');

                    var form = $('#productForm'); // Adjust the form ID as needed

                    $.each(response.data, function (key, value) {
                        var inputField = form.find('[name="' + key + '"]'); // Scope to form

                        if (inputField.length) {
                            inputField.val(value);
                            $(inputField).trigger('change')
                        }
                    });

                    // **Set the attributes array dynamically:**
                    setAttributesFromAjax(response.data.attributes || []);
                });
            });

            $('body').on('click', '.deleteProduct', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.easyDelete({
                    url: route('products.delete', {product: id}),
                    confirmationMessage: 'Do you really want to delete this product?',
                    onComplete: () => {
                        table.draw(false);
                    }
                })
            });

            $('body').on('click', '.toggleStatus', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var status = $(this).data('status');

                axios.post(route('products.toggleStatus', {product: id}), {status}).then((response) => {
                    if (status) {
                        $(this).removeClass('text-bg-danger');
                        $(this).addClass('text-bg-success');
                        $(this).html('Active');

                    } else {
                        $(this).removeClass('text-bg-success');
                        $(this).addClass('text-bg-danger');
                        $(this).html('In Active');
                    }

                    $(this).data('status', !status);

                    toastr.success(response.data.message);
                });
            });
        });

        // Initial attributes from old input or default empty
        let attributes = @json(old('attributes', [['key' => '', 'value' => '']]));

        function renderAttributes() {
            const container = $('#attributeRows');
            container.empty();

            attributes.forEach((attr, index) => {
                const row = `
                <div class="row mb-2 align-items-end attribute-row" data-index="${index}">
                    <div class="col-md-5">
                        <input type="text"
                               class="form-control"
                               name="attributes[${index}][key]"
                               value="${attr.key}"
                               placeholder="Attribute Key (e.g. Phase)"
                               required />
                    </div>
                    <div class="col-md-5">
                        <input type="text"
                               class="form-control"
                               name="attributes[${index}][value]"
                               value="${attr.value}"
                               placeholder="Attribute Value (e.g. 3-Phase)"
                               required />
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-attribute" ${attributes.length <= 1 ? 'disabled' : ''}>
                            <i class="bi bi-x-circle"></i> Remove
                        </button>
                    </div>
                </div>
            `;
                container.append(row);
            });
        }

        function addAttribute() {
            attributes.push({ key: '', value: '' });
            renderAttributes();
        }

        function removeAttribute(index) {
            attributes.splice(index, 1);
            renderAttributes();
        }

        // On page ready
        $(document).ready(function () {
            renderAttributes();

            $('#addAttributeBtn').on('click', function () {
                addAttribute();
            });

            // Delegate remove buttons
            $('#attributeWrapper').on('click', '.remove-attribute', function () {
                const index = $(this).closest('.attribute-row').data('index');
                removeAttribute(index);
            });
        });

        // To use from JS (e.g. when loading product to edit)
        function setAttributesFromAjax(newAttributes) {
            attributes = newAttributes.length > 0 ? newAttributes : [{ key: '', value: '' }];
            renderAttributes();
        }
    </script>

@endpush
