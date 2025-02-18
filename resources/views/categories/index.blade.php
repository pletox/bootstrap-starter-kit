@extends('layouts.app')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Categories</h3>

            <a href="#" data-bs-toggle="#categoryModal" id="add-category-btn" class="btn btn-dark">
                <i class="fas fa-add me-1"></i> Add Category
            </a>
        </div>

        <div class="card mt-3 rounded-top-0">
            <div class="table-responsive">
                <table id="categories-table" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade" id="categoryModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="modelHeading"></h6>
                    </div>
                    <form id="categoryForm" class="form-horizontal">
                        <div class="modal-body">

                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter Name"
                                           value="">
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>



                        </div>

                        <div class="modal-footer bg-light d-flex justify-content-end py-1">
                            <button type="submit" class="btn btn-dark" id="save">Save Category
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

            var table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

            $('#add-category-btn').click(function () {
                $('#id').val('');
                $('#categoryForm').trigger("reset");
                $('#modelHeading').html("Create New Category");
                $('#categoryModal').modal('show');
            });

            $('#categoryForm').on('submit', function (e) {
                e.preventDefault();

                var data = new FormData($('#categoryForm')[0]);


                $.easyAjax({
                    url: "{{ route('categories.storeOrUpdate') }}",
                    container: '#categoryForm',
                    type: "POST",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: () => {
                        $('#categoryModal').modal('hide');
                        $('#modelHeading').html("Create New Category");
                        $('#categoryForm').trigger("reset");
                        table.draw(false);
                    }
                })

            });

            $('body').on('click', '.editCategory', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                axios.get(route('categories.edit', {category: id})).then((response) => {
                    $('#modelHeading').html("Edit Category");
                    $('#categoryModal').modal('show');

                    var form = $('#categoryForm'); // Adjust the form ID as needed

                    $.each(response.data, function (key, value) {
                        var inputField = form.find('[name="' + key + '"]'); // Scope to form

                        if (inputField.length) {
                            inputField.val(value);
                            $(inputField).trigger('change')
                        }
                    });

                });
            });

            $('body').on('click', '.deleteCategory', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.easyDelete({
                    url: route('categories.delete', {category: id}),
                    confirmationMessage: 'Do you really want to delete this category?',
                    onComplete: () => {
                        table.draw(false);
                    }
                })
            });

        });
    </script>
@endpush
