@extends('layouts.app')

@section('title', 'Create New Sale')

@section('content')

    <div class="px-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Create New Sale</h3>

            <a href="{{ route('sales.index') }}" wire:navigate
               class="btn btn-dark !tw-bg-white !tw-text-gray-800
               hover:!tw-bg-gray-800 hover:!tw-text-white">
                <i class="bi bi-x-lg me-1"></i> Cancel
            </a>
        </div>

        <form>
            <div class="card mt-3">
                <div class="card-body tw-space-y-1">
                    <div class="row mb-1">
                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="sr_no">Invoice No.</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">INV/</span>
                                <input type="text" class="form-control" placeholder="Sr. No."
                                       name="sr_no" id="sr_no"/>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label class="form-label" for="date">Date</label>

                            <input type="text" class="form-control form-datepicker"
                                   readonly value="{{ now()->format('d/m/Y') }}"
                                   placeholder="Select Date"
                                   name="date" id="date"/>

                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Customer</label>
                        <div>
                            <select class="form-control form-select" id="customer_id" name="customer_id"
                                    placeholder="Select Customer">
                                <option selected disabled>-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Invalid feedback</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex align-items-end gap-3">
                        <div class="form-group  w-50">
                            <label for="name" class="form-label">Products</label>
                            <div>
                                <select class="form-control form-select" id="product_id" name="product_id"
                                        placeholder="Select Product">
                                    <option selected disabled>-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Invalid feedback</div>
                            </div>
                        </div>
                        <button type="button" id="addItem" class="btn btn-dark ">
                            <i class="bi bi-plus-lg"></i> Add
                        </button>
                    </div>

                </div>

                <div class="table-responsive">
                    <table id="items-table" class="table border-top">
                        <thead class="table-light">
                        <tr>
                            <th class="sm:tw-w-72">Item</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Rate</th>
                            <th class="text-end">Total</th>
                            <th class="tw-w-20 text-end"></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="sm:tw-w-72"></th>
                            <th class="text-end"></th>
                            <th class="text-end"></th>
                            <th class="text-end" id="grand_total"></th>
                            <th class="tw-w-20"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')

    <script id="item-template" type="text/x-handlebars-template">
        <tr>
            <td class="tw-space-y-2">
                <input class="form-control form-control-sm" name="item_name[]" value="@{{name}}">
                <textarea class="form-control form-control-sm" name="item_description[]">@{{description}}</textarea>
            </td>
            <td class="tw-space-y-2">
                <input class="form-control form-control-sm text-end itemQty" name="item_qty[]" value="@{{qty}}">
            </td>
            <td class="tw-space-y-2">
                <input class="form-control form-control-sm text-end itemRate" name="item_rate[]" value="@{{rate}}">
            </td>
            <td class="tw-space-y-2">
                <input class="form-control form-control-sm text-end itemTotal" name="item_total[]" readonly disabled value="@{{total}}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-light text-danger !tw-border-red-400 deleteItem"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    </script>

    <script type="module">
        $(function () {

            $('#addItem').on('click', function (e) {
                e.preventDefault();

                let productId = $('#product_id').val();

                if(!productId) {
                    toastr.error('Please select a product!');
                    return;
                }

                axios.get(route('products.edit', {product: productId})).then((response) => {
                    let source = $("#item-template").html();
                    let template = Handlebars.compile(source);
                    let data = {
                        name: response.data.name,
                        description: response.data.description,
                        qty: 1,
                        rate: 100,
                        total: 100
                    };
                    let html = template(data);
                    $("#items-table tbody").append(html);
                    $('#product_id').val($("#product_id option:first").val()).trigger('change');
                    totalAmountPrice();
                });


            });

            $('body').on('click', '.deleteItem', function (e) {
                e.preventDefault();

                    $(this).closest('tr').remove();
            });

            $(document).on('keyup click','.itemRate,.itemQty', function(){
                var unit_price = $(this).closest("tr").find("input.itemRate").val();
                var qty = $(this).closest("tr").find("input.itemQty").val();
                var total = unit_price * qty;
                $(this).closest("tr").find("input.itemTotal").val(total);
                totalAmountPrice();
            });


            function totalAmountPrice(){
                var sum = 0;
                $(".itemTotal").each(function(){
                    var value = $(this).val();
                    console.log(value);
                    if(!isNaN(value) && value.length != 0){
                        sum += parseFloat(value);
                    }
                });
                $('#grand_total').html(sum);
            }

        });
    </script>
@endpush
