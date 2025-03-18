@extends('layouts.app')

@section('title', 'Categories')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Categories</h3>

            <x-button data-bs-toggle="#categoryModal" id="add-category-btn" color="dark">
                <x-lucide-plus class="w-4 h-4"/>
                <span class="d-none d-sm-inline-block">Add Category</span>
            </x-button>
        </div>

        <x-card class="mt-3" body-class="px-0 pt-0 pt-sm-3">
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
        </x-card>

        <x-modal id="categoryModal" title="Create Category">
            <x-form id="categoryForm">
                <x-modal.body class="space-y-3">
                    <x-input name="name" label="Name" placeholder="Enter name"/>
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
