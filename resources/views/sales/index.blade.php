@extends('layouts.app')

@section('title', 'All Sales')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Manage Sales</h3>

            <a href="{{ route('sales.create') }}" wire:navigate class="btn btn-dark">
                <i class="fas fa-add me-1"></i> Add Sale
            </a>
        </div>

        <div class="card mt-3 rounded-top-0">
            <div class="table-responsive">
                <table id="sales-table" class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Sr. No.</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>


    </div>

@endsection

@push('js')
    <script type="module">
        $(function () {

            var table = $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'sr_no', name: 'sr_no'},
                    {data: 'customer.name', name: 'customer.name'},
                    {data: 'date', name: 'date'},
                    {data: 'grand_total', name: 'amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

            $('body').on('click', '.deleteSale', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.easyDelete({
                    url: route('sales.delete', {sale: id}),
                    confirmationMessage: 'Do you really want to delete this sale?',
                    onComplete: () => {
                        table.draw(false);
                    }
                })
            });

        });
    </script>
@endpush
