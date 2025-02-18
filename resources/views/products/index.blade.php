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


        <div class="modal fade" id="productModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <form id="productForm" class="form-horizontal">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <div>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter Name"
                                           value="">
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Description</label>
                                <div>
                                <textarea type="text" class="form-control" id="description" name="description"
                                          placeholder="Enter Description"></textarea>
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Category</label>
                                <div>
                                    <select class="form-control form-select" id="description" name="category_id"
                                            placeholder="Select Category">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer bg-light d-flex justify-content-end py-1">
                            <button type="submit" class="btn btn-dark" id="save">Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script type="module">
        $(function () {
            var table = $('#products-table').DataTable({
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
                $('#modelHeading').html("Create New Product");
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
                        $('#productForm').trigger("reset");

                        table.draw(false);
                    }
                })

            });

            $('body').on('click', '.editProduct', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                axios.get(route('products.edit', {product: id})).then((response) => {
                    $('#modelHeading').html("Edit Product");
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

                axios.post(route('products.toggleStatus', {product:id}), {status}).then((response) => {
                    if(status) {
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
