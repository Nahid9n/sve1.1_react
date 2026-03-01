@extends('backEnd.admin.layouts.master')
@section('title')
    Create Order
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
@endpush
@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                {{-- <div class="row mb-2 mt-3">
                    <div class="col-12">
                        <a href="{{ route('admin.orders') }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-angle-double-left"></i>
                            Back
                        </a>
                    </div>
                </div> --}}
                <div class="row  mb-3 mt-2 ">
                    <div class="col-12">
                        <h3>
                            Create Order
                            <small class="float-end">
                                <a href="{{ route('admin.orders') }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-angle-double-left"></i>
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>

                    </div>
                </div>
                <div class="row row-deck row-cards">
                    <!-- Page Header Close -->
                    <form action="{{ route('admin.orders.store') }}" method="POST" id="add-form" class="m-0"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Product and Summary
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <select id="product-select" class="form-select select" required>
                                                <option value="">--Select Product--</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-variant="{{ $product->has_variant }}"
                                                        data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url({{ asset($product->get_top_image ? $product->get_top_image->file_url : '') }})&quot;&gt;&lt;/span&gt;">
                                                        {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="table-responsive mb-md-3 mb-2">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SKU</th>
                                                        <th>Product(s)</th>
                                                        <th>Qty</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="prod_row">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <h3 class="shipping_method_title mb-3">Shipping Method</h3>
                                                @foreach ($shipping_methods as $key => $shipping_method)
                                                    <label class="form-check form-check-inline form-label">
                                                        <input type="hidden" class="shipping_amount"
                                                            value="{{ $shipping_method->amount }}">
                                                        <input class="form-check-input shipping_method"
                                                            value="{{ $shipping_method->id }}" name="shipping_method_id"
                                                            type="radio" {{ $key == 0 ? 'checked' : '' }}>
                                                        <span class="form-check-label">{{ $shipping_method->text }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Sub Total</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="sub_total"
                                                            id="sub_total" value="0.00" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Delivery Cost</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control auto-select-number"
                                                            name="delivery_cost" id="delivery_cost" value="0.00" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Discount</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                            class="form-control auto-select-number discount" name="discount"
                                                            id="discount" value="0.00">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Grand Total</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="grand_total"
                                                            id="grand_total" readonly="" value="0.00">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Paid</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="paid_amount"
                                                            id="paid_amount" value="0.00">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Due</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="due_amount"
                                                            id="due_amount" readonly="" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5">
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Order & Customer Information
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="price">Order Date</label>
                                                <input type="text" class="form-control"
                                                    value="{{ date('d-m-Y', strtotime(now())) }}" name="order_date"
                                                    id="order_date" autocomplete="off" required>
                                            </div>

                                            <div class="col">
                                                <label class="form-label" for="memo_no">Memo Number</label>
                                                <input type="text" class="form-control" name="memo_no">
                                            </div>
                                            <div class="col">
                                                <label class="form-label" for="invoice_id">Invoice ID</label>
                                                <input type="text" class="form-control" name="invoice_id"
                                                    value="{{ $invoice_id }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="name">Customer Name</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    autocomplete="off" required>
                                            </div>

                                            <div class="col">
                                                <label class="form-label required" for="phone">Customer Phone</label>
                                                <input type="text" class="form-control" name="phone" id="phone"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="address">Customer Address</label>
                                                <textarea name="address" id="address" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Courier
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            {{-- COURIER --}}
                                            <div class="col-12 mb-3">
                                                <label class="form-label" for="courier_id">Select Courier</label>
                                                <select name="courier_id" id="courier_id" class="form-select">
                                                    <option value="">Select Courier</option>
                                                    @foreach (DB::table('couriers')->get() as $courier)
                                                        <option value="{{ $courier->id }}">{{ $courier->courier_name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            {{-- CITY --}}
                                            {{-- <div class="col-6 mb-3 courier_city">
                                                <label class="form-label" for="courier_city">Select City</label>
                                                <select name="courier_city_id" id="courier_city" class="form-select">


                                                </select>
                                            </div> --}}

                                            {{-- ZONE --}}
                                            {{-- <div class="col-6 mb-3 courier_zone">
                                                <label class="form-label" for="courier_zone">Select Zone</label>
                                                <select name="courier_zone_id" id="courier_zone" class="form-select">

                                                </select>
                                            </div> --}}

                                            <div class="city_zone_wrapper d-none">
                                                <label>City / Zone</label>
                                                <select id="courier_city_zone" class="form-control select2"
                                                    name="courier_zone_id">
                                                    <option value="">Select City → Zone</option>
                                                </select>
                                            </div>


                                        </div>


                                    </div>
                                </div>
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Action
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label class="form-label" for="status">Order Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="1">Pending </option>
                                                    <option value="2">Confirm</option>
                                                    <option value="3">Processing</option>
                                                    <option value="4">Hold</option>
                                                    <option value="5">Printed</option>
                                                    <option value="6">Packaging</option>
                                                    <option value="7">On Delivery</option>
                                                    <option value="8">Delivered</option>
                                                    <option value="9">Cancelled</option>
                                                    <option value="10">Returned</option>
                                                </select>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label class="form-label" for="source">Source <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <select name="source" id="source" class="form-select"
                                                    required="">
                                                    {{-- <option value="">Select Source</option> --}}
                                                    <option value="page">Page</option>
                                                    <option value="whatsapp">Whatsapp</option>
                                                    <option value="ab_cart">AB Cart</option>
                                                    <option value="call">Call</option>
                                                    <option value="office_sell">Office Sell</option>
                                                    <option value="instagram">Instagram</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
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

    <!-- Modal for Product Variant -->
    <div class="modal fade" id="productVariant" tabindex="-1" aria-labelledby="productVariantLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white p-3">
                    <h6 class="modal-title fw-bold" id="productVariantLabel">
                        Select Product Variant
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4 bg-white">
                    <div id="variantOptions" class="d-flex flex-column gap-3">

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-success w-100 fw-bold" id="saveVariantSelection">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#product-select').select2();
            // $('#courier_id').select2();
            $('#courier_city').select2({
                placeholder: 'Select city'
            });
            $('#courier_zone').select2({
                placeholder: 'Select zone'
            });

        });
    </script>

    <script>
        $(function() {
            function finalCalc() {
                let subTotal = 0;
                $('#prod_row tr').each(function() {
                    let qty = parseFloat($(this).find('.qty').val() || 0);
                    let price = parseFloat($(this).find('.price').val() || 0);
                    subTotal += qty * price;
                    $(this).find('.total_price').text((qty * price).toFixed(2));
                });
                $('#sub_total').val(subTotal.toFixed(2));
                let delivery = parseFloat($('#delivery_cost').val() || 0);
                let discount = parseFloat($('#discount').val() || 0);
                let grand = subTotal + delivery - discount;
                $('#grand_total').val(grand.toFixed(2));
                let paid = parseFloat($('#paid_amount').val() || 0);
                $('#due_amount').val((grand - paid).toFixed(2));
            }

            let productIndex = 0;

            function appendProductRow(rowHtml) {
                rowHtml = rowHtml.replace(/__INDEX__/g, productIndex);
                $('#prod_row').append(rowHtml);
                productIndex++;
                finalCalc();
            }

            $('#product-select').on('change', function() {
                let $opt = $(this).find(':selected');
                let product_id = $opt.val();
                let has_variant = $opt.data('variant');
                let CSRF = "{{ csrf_token() }}";

                if (has_variant) {
                    $.post("{{ route('admin.ajax.get.product.modal') }}", {
                        _token: CSRF,
                        id: product_id
                    }, function(html) {
                        $('#variantOptions').html(html);
                        $('#productVariant').removeData('editing-row').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).modal('show');
                    });
                } else {
                    $.post("{{ route('admin.ajax.get.products') }}", {
                        _token: CSRF,
                        id: product_id
                    }, function(rowHtml) {
                        appendProductRow(rowHtml);
                    });
                }
            });

            $(document).on('click', '.edit_variant', function() {
                let row = $(this).closest('tr');
                let product_id = row.data('product-id');
                let choice_str = row.find('.variant_choice').val() || [];
                let selectedChoices = choice_str.split(',');
                let CSRF = "{{ csrf_token() }}";

                $.post("{{ route('admin.ajax.get.product.modal') }}", {
                    _token: CSRF,
                    id: product_id,
                    choice_variants: selectedChoices
                }, function(html) {
                    $('#variantOptions').html(html);
                    $('#productVariant').data('editing-row', row).modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                });
            });

            $('#productVariant').on('click', '#saveVariantSelection', function() {
                let product_id = $('#variant_product_id').val();
                let qty = parseInt($('#modal_qty').val() || 1);
                let choice = [];
                $('#variantSelectionForm').find('input[type=radio]:checked').each(function() {
                    choice.push($(this).val());
                });
                let choice_key = choice.join(',');
                let editingRow = $('#productVariant').data('editing-row');
                let CSRF = "{{ csrf_token() }}";

                if (editingRow) {
                    $.post("{{ route('admin.ajax.get.modal.variant') }}", {
                        _token: CSRF,
                        id: product_id,
                        choice_variants: choice,
                        qty: qty,
                        modal: 1
                    }, function(rowHtml) {
                        let idx = editingRow.find('input[name^="products"]').attr('name').match(
                            /\d+/)[0];
                        rowHtml = rowHtml.replace(/__INDEX__/g, idx);
                        editingRow.replaceWith(rowHtml);
                        $('#productVariant').removeData('editing-row');
                        finalCalc();
                    });
                    return;
                }

                let existingRow = null;
                $('#prod_row tr').each(function() {
                    let rowProductId = $(this).data('product-id');
                    let rowChoice = $(this).find('.variant_choice').val();
                    if (rowProductId == product_id && rowChoice == choice_key) {
                        existingRow = $(this);
                        return false;
                    }
                });

                if (existingRow) {
                    let currentQty = parseInt(existingRow.find('.qty').val() || 1);
                    existingRow.find('.qty').val(currentQty + qty).trigger('change');
                    alert('Variant already exists, quantity updated!');
                    $('#productVariant').modal('hide');
                    return;
                }

                $.post("{{ route('admin.ajax.get.modal.variant') }}", {
                    _token: CSRF,
                    id: product_id,
                    choice_variants: choice,
                    qty: qty,
                    modal: 1
                }, function(rowHtml) {
                    appendProductRow(rowHtml);
                });
            });

            $(document).on('click', '.remove_btn', function() {
                $(this).closest('tr').remove();
                finalCalc();
            });
            $(document).on('keyup change', '.qty,.price,.discount, #paid_amount', function() {
                finalCalc();
            });
            $(document).on('change', '.shipping_method', function() {
                let amount = $(this).parent().find('.shipping_amount').val();
                $('#delivery_cost').val($(this).parent().find('.shipping_amount').val());
                finalCalc();
            });


            $(document).on('change', '#courier_id', function() {
                let courier_id = parseInt($(this).val());
                let allowed = [2, 3, 4];
                $('.city_zone_wrapper').addClass('d-none');
                if (!allowed.includes(courier_id)) {
                    $('#courier_city_zone').html('<option value="">Select City → Zone</option>');
                    return;
                }
                $('.city_zone_wrapper').removeClass('d-none');
                $('#courier_city_zone').html('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('admin.ajax.get.courier.cities') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        courier_id: courier_id
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.courier_id == 2) {
                                $('#courier_city_zone').html(
                                    '<option value="">Select City → Zone</option>');
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.zone_id}"> ${item.city_name} → ${item.zone_name} </option>`
                                    );
                                });
                            } else if (response.courier_id == 3) {
                                $('#courier_city_zone').html(
                                    '<option value="">Select City → Zone</option>'
                                );
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.parent_id}">${item.district} → ${item.name} </option>`
                                    );
                                });
                                $('#courier_city_zone').select2('destroy').select2();
                            } else if (response.courier_id == 4) {
                                $('#courier_city_zone').html(
                                    '<option value="">Select City → Zone</option>'
                                );
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.zone_id}">${item.city_name} → ${item.zone_name}</option>`
                                    );
                                });
                                $('#courier_city_zone').select2('destroy').select2();
                            }
                        }
                    }
                    // }
                });

            });
        });
    </script>
@endpush
