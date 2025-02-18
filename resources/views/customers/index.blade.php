@extends('layouts.app')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Customers</h3>

            <a href="#" data-bs-toggle="#customerModal" id="add-customer-btn" class="btn btn-dark">
                <i class="fas fa-add me-1"></i> Add Customer
            </a>
        </div>

        <div class="card mt-3 rounded-top-0">
            <div class="table-responsive">
                <table id="customers-table" class="table">
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


        <div class="modal fade" id="customerModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="modelHeading"></h6>
                    </div>
                    <form id="customerForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <div class="modal-body tw-space-y-3">


                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter Name"
                                           value="">
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">Phone</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="Enter Phone"
                                           value="">
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">Address</label>
                                <div class="col-sm-12">
                                    <textarea type="email" class="form-control" id="address" name="address"
                                           placeholder="Enter Address"
                                           value=""></textarea>
                                    <div class="invalid-feedback">Invalid feedback</div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer bg-light d-flex justify-content-end py-1">
                            <button type="submit" class="btn btn-dark" id="save">Save Customer
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

            var table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

            $('#add-customer-btn').click(function () {
                $('#id').val('');
                $('#customerForm').trigger("reset");
                $('#modelHeading').html("Create New Customer");
                $('#customerModal').modal('show');
            });

            $('#customerForm').on('submit', function (e) {
                e.preventDefault();

                var data = new FormData($('#customerForm')[0]);


                $.easyAjax({
                    url: "{{ route('customers.storeOrUpdate') }}",
                    container: '#customerForm',
                    type: "POST",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: () => {
                        $('#customerModal').modal('hide');
                        $('#modelHeading').html("Create New Customer");
                        $('#customerForm').trigger("reset");
                        table.draw(false);
                    }
                })

            });

            $('body').on('click', '.editCustomer', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                axios.get(route('customers.edit', {customer: id})).then((response) => {
                    $('#modelHeading').html("Edit Customer");
                    $('#customerModal').modal('show');

                    var form = $('#customerForm'); // Adjust the form ID as needed

                    $.each(response.data, function (key, value) {
                        var inputField = form.find('[name="' + key + '"]'); // Scope to form

                        if (inputField.length) {
                            inputField.val(value);
                            $(inputField).trigger('change')
                        }
                    });

                });
            });

            $('body').on('click', '.deleteCustomer', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.easyDelete({
                    url: route('customers.delete', {customer: id}),
                    confirmationMessage: 'Do you really want to delete this customer?',
                    onComplete: () => {
                        table.draw(false);
                    }
                })
            });

        });
    </script>
@endpush
