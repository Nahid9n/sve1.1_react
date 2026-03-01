@extends('backEnd.admin.layouts.master')
@section('title')
    Edit Purchase
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
@endpush
@php
    $currency_sign = $data['currency_sign'] ?? [];
    $setting = DB::table('web_settings')->first();
@endphp
@section('content')
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
                    <form action="{{ route('admin.purchase.update', $data->id) }}" method="POST" id="add-form"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12 col-12 mb-3">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between">
                                    <div class="card-title">
                                        Edit Purchase
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
                                                    <option value="{{ $key }}"
                                                        {{ $data->supplier_id == $key ? 'selected' : '' }}>
                                                        {{ $supplier }}</option>
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
                                                value="{{ date('d-m-Y', strtotime($data->purchase_date)) }}"
                                                class="form-control" required>
                                            @if ($errors->has('purchase_date'))
                                                <span class="text-danger">{{ $errors->first('purchase_date') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="status" class="form-label">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Ordered
                                                </option>
                                                <option value="0" {{ $data->status == 2 ? 'selected' : '' }}>Received
                                                </option>
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="memo_no" class="form-label">Memo number </label>
                                            <input type="text" name="memo_no" id="memo_no" class="form-control"
                                                value="{{ $data->memo_no }}">
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="remarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ $data->remarks }}</textarea>
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
                                                    </tr>
                                                </thead>
                                                <tbody id="product-put">
                                                    @if (count($data->get_purchase_items) > 0)
                                                        @foreach ($data->get_purchase_items->groupBy('product_id') as $key => $item)
                                                            {{-- @dd($item[0]) --}}
                                                            @if ($item[0]->get_product->has_variant == 1)
                                                                <tr data-id="1">
                                                                    <td colspan="5">
                                                                        <div class="col-md-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            {{-- <th></th> --}}
                                                                                            <th>Product Name</th>
                                                                                            <th>Purchase Quantity</th>
                                                                                            <th>Purchase Cost</th>
                                                                                            <th>Regular Price</th>
                                                                                            <th>Sell Price</th>
                                                                                            {{-- <th>Total</th> --}}
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($item[0]->get_product->get_purchase_items->where('purchase_id', $data->id) as $item)
                                                                                            <tr>
                                                                                                {{-- <td><a href="javascript:void(0);"
                                                                                                        class="remove_product_attribute text-danger fs-5"><svg
                                                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                                                            width="24"
                                                                                                            height="24"
                                                                                                            viewBox="0 0 24 24"
                                                                                                            fill="none"
                                                                                                            stroke="currentColor"
                                                                                                            stroke-width="2"
                                                                                                            stroke-linecap="round"
                                                                                                            stroke-linejoin="round"
                                                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                                                                            <path
                                                                                                                stroke="none"
                                                                                                                d="M0 0h24v24H0z"
                                                                                                                fill="none" />
                                                                                                            <path
                                                                                                                d="M18 6l-12 12" />
                                                                                                            <path
                                                                                                                d="M6 6l12 12" />
                                                                                                        </svg></a>
                                                                                                </td> --}}
                                                                                                <td>
                                                                                                    <span
                                                                                                        class="text-primary">{{ $item->sku }}</span><br>
                                                                                                    {{ $item->get_product->name }}
                                                                                                    <input type="hidden"
                                                                                                        name="sku[]"
                                                                                                        value="{{ $item->sku }}">
                                                                                                    <input type="hidden"
                                                                                                        name="product_id[]"
                                                                                                        value="{{ $item->product_id }}">
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="number"
                                                                                                        name="purchase_quantity[]"
                                                                                                        class="form-control purchase_quantity purchase_quantity_{{ $item->get_product->id }}"
                                                                                                        id="purchase_quantity"
                                                                                                        data-id="{{ $item->get_product->id }}"
                                                                                                        value="{{ $item->product_quantity }}"
                                                                                                        required>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="number"
                                                                                                        name="purchase_cost[]"
                                                                                                        class="form-control purchase_cost purchase_cost_{{ $item->get_product->id }}"
                                                                                                        id="purchase_cost"
                                                                                                        value="{{ formatNumber($item->purchase_cost) ?? 0 }}"
                                                                                                        step="0.01"
                                                                                                        data-id="{{ $item->get_product->id }}"
                                                                                                        required>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="number"
                                                                                                        name="regular_price[]"
                                                                                                        class="form-control regular_price regular_price_{{ $item->get_product->id }}"
                                                                                                        id="regular_price"
                                                                                                        value="{{ formatNumber($item->regular_price) ?? 0 }}"
                                                                                                        step="0.01"
                                                                                                        data-id="{{ $item->get_product->id }}"
                                                                                                        required>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="number"
                                                                                                        name="sell_price[]"
                                                                                                        class="form-control sell_price sell_price_{{ $item->get_product->id }}"
                                                                                                        id="sell_price"
                                                                                                        value="{{ formatNumber($item->sell_price) ?? 0 }}"
                                                                                                        step="0.01"
                                                                                                        data-id="{{ $item->get_product->id }}"
                                                                                                        required>

                                                                                                    <input type="hidden"
                                                                                                        class="form-control single_purchase_total  single_purchase_item_total_{{ $item->get_product->id }}"
                                                                                                        name="single_purchase_total[]"
                                                                                                        id="single_purchase_total"
                                                                                                        step="0.01"
                                                                                                        value="{{ formatNumber($item->total) ?? 0 }}"
                                                                                                        required readonly>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number"
                                                                            class="form-control single_purchase_total_{{ $item->get_product->id }}"
                                                                            id="single_purchase_total_{{ $item->get_product->id }}"
                                                                            step="0.01"
                                                                            value="{{ $item->get_product->get_purchase_items->where('purchase_id', $data->id)->sum('total') }}"
                                                                            required readonly>
                                                                    </td>
                                                                    {{-- <td class="w-1">
                                                                        <a href="javascript:void(0);"
                                                                            class="remove_product_btn text-danger fs-5"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path d="M18 6l-12 12" />
                                                                                <path d="M6 6l12 12" />
                                                                            </svg></a>
                                                                    </td> --}}
                                                                </tr>
                                                            @else
                                                                @foreach ($item as $key => $item)
                                                                    <tr data-id="1">
                                                                        <td>
                                                                            <span
                                                                                class="text-primary">{{ $item->sku }}</span><br>
                                                                            {{ $item->get_product->name }}
                                                                            <input type="hidden" name="sku[]"
                                                                                value="{{ $item->sku }}">
                                                                            <input type="hidden" name="product_id[]"
                                                                                value="{{ $item->product_id }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="number"
                                                                                name="purchase_quantity[]"
                                                                                class="form-control purchase_quantity"
                                                                                id="purchase_quantity"
                                                                                value="{{ $item->product_quantity }}"
                                                                                required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="purchase_cost[]"
                                                                                class="form-control purchase_cost"
                                                                                id="purchase_cost"
                                                                                value="{{ formatNumber($item->purchase_cost) ?? 0 }}"
                                                                                step="0.01" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="regular_price[]"
                                                                                class="form-control regular_price"
                                                                                id="regular_price"
                                                                                value="{{ formatNumber($item->regular_price) ?? 0 }}"
                                                                                step="0.01" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="sell_price[]"
                                                                                class="form-control sell_price"
                                                                                id="sell_price"
                                                                                value="{{ formatNumber($item->sell_price) ?? 0 }}"
                                                                                step="0.01" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                class="form-control single_purchase_total"
                                                                                name="single_purchase_total[]"
                                                                                id="single_purchase_total" step="0.01"
                                                                                value="{{ formatNumber($item->total) ?? 0 }}"
                                                                                required readonly>
                                                                        </td>
                                                                        {{-- <td>
                                                                            <a href="javascript:void(0);"
                                                                                class="remove_product_btn text-danger fs-5"><svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none" />
                                                                                    <path d="M18 6l-12 12" />
                                                                                    <path d="M6 6l12 12" />
                                                                                </svg></a>
                                                                        </td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                        <tr class="no-product d-none">
                                                            <td colspan="12" class="text-danger text-center">No Product
                                                                Selected
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr class="no-product">
                                                            <td colspan="12" class="text-danger text-center">No Product
                                                                Selected
                                                            </td>
                                                        </tr>
                                                    @endif
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
                                                    <input type="text" class="form-control" name="subtotal"
                                                        id="sub_total" value="{{ formatNumber($data->subtotal) ?? 0 }}"
                                                        readonly step="0.01">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-9 text-end">
                                                    <label class="col-form-label" for="">Discount</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control auto-select-number"
                                                        name="discount" id="discount"
                                                        value="{{ formatNumber($data->discount) ?? 0 }}" step="0.01">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-9 text-end">
                                                    <label class="col-form-label" for="">Grand Total</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="total"
                                                        id="grand_total" readonly=""
                                                        value="{{ formatNumber($data->total) ?? 0 }}" step="0.01">
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
                                            <label for="payment_method" class="form-label">Payment method <span
                                                    class="text-danger">*</span></label>
                                            <select name="account_id" id="account_id" class="form-select">
                                                <option value="">-- Select payment method --</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}"
                                                        {{ $data->account_id == $account->id ? 'selected' : '' }}>
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
                                            <input type="number" name="paid_amount" id="paid_amount"
                                                class="form-control" value="{{ formatNumber($data->paid_amount) }}"
                                                placeholder="00.00" step="0.01" required>
                                            @if ($errors->has('paid_amount'))
                                                <span class="text-danger">{{ $errors->first('paid_amount') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <label for="due_amount" class="form-label">Due amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="due_amount" id="due_amount"
                                                value="{{ formatNumber($data->due_amount) }}" class="form-control"
                                                placeholder="00.00" step="0.01" required>
                                        </div>
                                        <div class="col-md-12 col-12 mb-3">
                                            <label for="note" class="form-label">Note </label>
                                            <textarea name="note" id="note" rows="2" class="form-control">{{ $data->note }}</textarea>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <button type="submit" class="btn float-end btn-success">Update</button>
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
            let subtotal = $('#sub_total').val();
            let discount = $('#discount').val();
            let total = parseFloat(subtotal - discount);
            let due_amount = $('#due_amount').val(total - $('#paid_amount').val());
            $('#grand_total').val(total);
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
            $('#product_select').select2({

            });

            $('#purchase_select').select2({

            });
        });

        $(document).on('click', '.remove_product_btn', function() {
            $(this).closest("tr").remove();
            subtotal();
            total();
            if ($('#sub_total').val() == 0) {
                $('.no-product').removeClass('d-none');
            } else {
                $('.no-product').addClass('d-none');
            }
        });

        $(document).on('click', '.remove_product_attribute', function() {
            $(this).closest("tr").remove();
            subtotal();
            total();
            if ($('#sub_total').val() == 0) {
                $('.no-product').removeClass('d-none');
            } else {
                $('.no-product').addClass('d-none');
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
                        $('.no-product').css('display', 'none');
                    } else {
                        $('.no-product').css('display', 'table-row');
                    }
                    $('#product-put').append(data);
                    subtotal();
                    total();
                }
            });
        });
    </script>
@endpush
