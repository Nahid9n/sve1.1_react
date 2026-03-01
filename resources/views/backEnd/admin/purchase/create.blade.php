@extends('backEnd.admin.layouts.master')
@section('title')
    Create Purchase
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
@endpush
@php
    $currency_sign = $data['currency_sign'] ?? [];
    $setting = DB::table('web_settings')->first();
@endphp
@section('content')
    {{-- <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <a href="{{ route('admin.purchase') }}" class="btn btn-dark btn-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11 7l-5 5l5 5" />
                                <path d="M17 7l-5 5l5 5" />
                            </svg> Back</a>
                    </h2>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-between">
                        <h3>Create Purchase</h3>
                        <small class="float-end">
                            <a href="{{ route('admin.purchase') }}" class="btn btn-dark btn-sm">
                                <i class="ti ti-arrow-left"></i>
                                Back
                            </a>
                        </small>
                    </div>
                </div>

                <div class="row row-deck row-cards">
                    <form action="{{ route('admin.purchase.store') }}" method="POST" id="add-form"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12 col-12 mb-3">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between">
                                    <div class="card-title">
                                        Create Purchase
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="supplier_id" class="form-label">Supplier <span
                                                    class="text-danger">*</span></label>
                                            <select class="select2-client-search form-control" name="supplier_id"
                                                id="purchase_select" required>
                                                <option value="">--Select Supplier--</option>
                                                @foreach ($suppliers as $key => $supplier)
                                                    <option value="{{ $key }}">{{ $supplier }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('supplier_id'))
                                                <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="purchase_date" class="form-label">Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="purchase_date" id="purchase_date"
                                                value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" class="form-control"
                                                required>
                                            @if ($errors->has('purchase_date'))
                                                <span class="text-danger">{{ $errors->first('purchase_date') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="status" class="form-label">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="0">Pending</option>
                                                <option value="1">Ordered</option>
                                                <option value="0">Received</option>
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="memo_no" class="form-label">Memo number</label>
                                            <input type="text" name="memo_no" id="memo_no" class="form-control">
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="remarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <select class="select2-client-search form-control" name="product_id"
                                            id="product_select">
                                            <option value="">--Select Product--</option>
                                            @foreach ($products as $key => $product)
                                                <option value="{{ $key }}">{{ $product }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('product_id'))
                                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="table-responsive mb-md-3 mb-2">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="bg-primary text-white">
                                                        <th>Product Name</th>
                                                        <th>Purchase Quantity</th>
                                                        <th>Purchase Cost</th>
                                                        <th>Regular Price</th>
                                                        <th>Sell Price</th>
                                                        <th>Total</th>
                                                        {{-- <th></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody id="product-put">
                                                    <tr class="no-product">
                                                        <td colspan="12" class="text-danger text-center">No Product
                                                            Selected</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-5">
                                            <div class="row mb-1">
                                                <div class="col-md-9 text-end">
                                                    <label class="col-form-label" for="">Sub Total</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control" name="subtotal"
                                                        id="sub_total" value="0" readonly step="0.01">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-9 text-end">
                                                    <label class="col-form-label" for="">Discount</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control auto-select-number"
                                                        name="discount" id="discount" value="0" step="0.01">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-9 text-end">
                                                    <label class="col-form-label" for="">Grand Total</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control" name="total"
                                                        id="grand_total" readonly="" value="0" step="0.01">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-12 mb-3">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between">
                                    <div class="card-title">
                                        Payment
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="account_id" class="form-label">Account<span
                                                    class="text-danger">*</span></label>
                                            <select name="account_id" id="account_id" class="form-select" required>
                                                <option value="">-- Select Account --</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">
                                                        {{ $account->account_type == 1 ? 'Bank - ' . $account->bank_account_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 2 ? 'Bkash - ' . $account->bkash_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 3 ? 'Nagad - ' . $account->nagad_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : 'Rocket - ' . $account->rocket_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('account_id'))
                                                <span class="text-danger">{{ $errors->first('account_id') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="paid_amount" class="form-label">Paid amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="paid_amount" id="paid_amount" value="0"
                                                class="form-control" placeholder="0.00" step="0.01" required>
                                            @if ($errors->has('paid_amount'))
                                                <span class="text-danger">{{ $errors->first('paid_amount') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="due_amount" class="form-label">Due amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="due_amount" id="due_amount" class="form-control"
                                                value="0" step="0.01" readonly required>
                                        </div>
                                        <div class="col-md-12 col-12 mb-3">
                                            <label for="note" class="form-label">Note </label>
                                            <textarea name="note" id="note" rows="2" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <button type="submit" class="btn btn-success float-end">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        function total() {
            let subtotal = parseFloat($('#sub_total').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;
            let paid = parseFloat($('#paid_amount').val()) || 0;

            let grandTotal = subtotal - discount;
            let due = grandTotal - paid;

            $('#grand_total').val(grandTotal.toFixed(2));
            $('#due_amount').val(due.toFixed(2));
        }


        function subtotal() {
            var subtotal = 0;
            $('.single_purchase_total').each(function(index, val) {
                subtotal += parseFloat($(this).val());
            });
            $('#sub_total').val(subtotal);
        }

        $(document).on('keyup change', '.purchase_cost', function() {
            let id = $(this).data('id');
            let parent = $(this).parents('tr');
            let qty = parent.find('.purchase_quantity').val();
            $('.purchase_cost_' + id).val($(this).val());
            let single_purchase_total = parseFloat(qty * $(this).val());
            parent.find('.single_purchase_total').val(single_purchase_total);

            var itemTotal = 0;
            $('.single_purchase_item_total_' + id).each(function(index, val) {
                itemTotal += parseFloat($(this).val());
            });
            $('.single_purchase_total_' + id).val(itemTotal);

            subtotal();
            total();
        });

        $(document).on('keyup change', '.purchase_quantity', function() {
            let id = $(this).data('id');
            let parent = $(this).parents('tr');
            let purchase_cost = parent.find('.purchase_cost').val();
            $('.purchase_quantity_' + id).val($(this).val());
            let single_purchase_total = parseFloat(purchase_cost * $(this).val());
            parent.find('.single_purchase_total').val(single_purchase_total);

            var itemTotal = 0;
            $('.single_purchase_item_total_' + id).each(function(index, val) {
                itemTotal += parseFloat($(this).val());
            });
            $('.single_purchase_total_' + id).val(itemTotal);

            subtotal();
            total();
        });

        $(document).on('keyup change', '.sell_price', function() {
            let id = $(this).data('id');
            $('.sell_price_' + id).val($(this).val());
        });

        $(document).on('keyup change', '#discount', function() {
            total();
        });

        $(document).ready(function() {
            $('#product_select').select2({});
            $('#purchase_select').select2({});
        });

        $(document).on('click', '.remove_product_btn', function() {
            $(this).closest("tr").remove();
            subtotal();
            total();
            if ($('#sub_total').val() == 0) {
                $('.no-product').css('display', 'table-row');
            } else {
                $('.no-product').css('display', 'none');
            }
        });

        $(document).on('click', '.remove_product_attribute', function() {
            $(this).closest("tr").remove();
            subtotal();
            total();
            if ($('#sub_total').val() == 0) {
                $('.no-product').css('display', 'table-row');
            } else {
                $('.no-product').css('display', 'none');
            }
        });

        //paid_amount
        $(document).on('keyup change', '#paid_amount', function() {
            let grand_total = $('#grand_total').val();
            let paid_amount = $(this).val();
            let due_amount = parseFloat(grand_total - paid_amount);
            $('#due_amount').val(due_amount);
        });

        $(document).on('change', '#product_select', function() {
            var that = $(this);
            var CSRF_TOKEN = `{{ csrf_token() }}`;
            $.ajax({
                url: '{{ route('admin.ajax.get.purchase.product') }}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id: $(this).val()
                },
                success: function(data) {
                    if (data) {
                        $('.no-product').hide();
                        $('#product-put').append(data);

                        // 🔥 force recalculation
                        $('.purchase_quantity, .purchase_cost').trigger('change');

                        subtotal();
                        total();
                    }
                }

            });
        });
    </script>
@endpush
