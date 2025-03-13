@extends('layouts.app')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Products</h3>

            <a href="#" data-bs-toggle="#productModal" id="add-product-btn" class="btn btn-dark">
                <i class="fas fa-add me-1"></i> Add Product
            </a>
        </div>

        <div class="card mt-3 rounded-top-0">
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
        </div>


        <x-modal id="productModal" title="Create Product">
            <x-form id="productForm" >

                <x-modal.body class="space-y-4">
                    <input type="hidden" name="id" id="id">

                    <x-input name="name" id="name" label="Name" placeholder="Enter Name"/>

                    <x-textarea id="description" name="description" label="Enter Description"
                                placeholder="Enter Description"/>

                    <x-select id="category_id" name="category_id" label="Select Category"
                              placeholder="Select Category">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-select>


                    <x-file-input name="photo" label="Select Image" preview preview-position="left"  />

                    <x-datepicker name="date" id="date" label="Select Date" placeholder="Select Date" range/>

                    <x-datepicker name="date2" id="date2" label="Select Date 2" placeholder="Select Date 2" />

                </x-modal.body>

                <x-modal.footer>
                    <x-button color="secondary" data-bs-dismiss="modal">Cancel</x-button>
                    <x-button color="dark" type="submit">Submit</x-button>
                </x-modal.footer>
            </x-form>

        </x-modal>

    </div>

@endsection

@push('js')
    <script type="module">
        $(function () {
            let table = $('#products-table');

            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().destroy(); // Destroy existing instance
            }

            table = $('#products-table').DataTable({
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
                $('#productModal .model-title').html("Create New Product");
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
    </script>
@endpush
