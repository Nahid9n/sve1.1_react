@extends('backEnd.admin.layouts.master')
@section('title', 'Edit Coupon')

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
    <style>
        .hidden {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">

                <div class="row  mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Coupon
                            <small class="float-end">
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-arrow-left"></i> Back
                                </a>
                            </small>
                        </h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}"
                                        class="form-control">
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Discount Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Fixed
                                        </option>
                                        <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span> </label>
                                    <input type="number" step="0.01" name="amount" value="{{ $coupon->amount }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="col-md-4 mb-3 percentageField {{ $coupon->type == 'percentage' ? '' : 'hidden' }}">
                                    <label class="form-label">Max Discount Amount</label>
                                    <input type="number" step="0.01" name="max_discount"
                                        value="{{ $coupon->max_discount }}" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Minimum Purchase</label>
                                    <input type="number" step="0.01" name="min_purchase"
                                        value="{{ $coupon->min_purchase }}" class="form-control">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Apply On</label>
                                    <select name="apply_on" id="apply_on" class="form-control">
                                        <option value="cart" {{ $coupon->apply_on == 'cart' ? 'selected' : '' }}>Cart
                                        </option>
                                        <option value="product" {{ $coupon->apply_on == 'product' ? 'selected' : '' }}>
                                            Specific
                                            Product</option>
                                        <option value="shipping" {{ $coupon->apply_on == 'shipping' ? 'selected' : '' }}>
                                            Shipping
                                        </option>
                                        <option value="payment" {{ $coupon->apply_on == 'payment' ? 'selected' : '' }}>
                                            Payment
                                            Method</option>
                                        <option value="first_time"
                                            {{ $coupon->apply_on == 'first_time' ? 'selected' : '' }}>
                                            First Time User</option>
                                    </select>
                                </div>
                            </div>
                            {{-- @dd($coupon->product_ids) --}}

                            {{-- products --}}
                            <div class="row {{ $coupon->apply_on == 'product' ? '' : 'hidden' }}" id="productField">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Select Products <span class="text-danger">*</span></label>
                                    <select class="form-control select2" multiple name="product_ids[]">
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}"
                                                @if (in_array($item->id, $coupon->product_ids ?? [])) selected @endif>
                                                {{ $item->name }} (#{{ $item->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- payment --}}
                            <div class="row {{ $coupon->apply_on == 'payment' ? '' : 'hidden' }}" id="paymentField">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select class="form-control" name="payment_method">
                                        <option value="cod" {{ $coupon->payment_method == 'cod' ? 'selected' : '' }}>
                                            Cash On
                                            Delivery</option>
                                        <option value="sslcommerz"
                                            {{ $coupon->payment_method == 'sslcommerz' ? 'selected' : '' }}>SSLCommerz
                                        </option>
                                        <option value="bkash" {{ $coupon->payment_method == 'bkash' ? 'selected' : '' }}>
                                            Bkash
                                        </option>
                                        <option value="nagad" {{ $coupon->payment_method == 'nagad' ? 'selected' : '' }}>
                                            Nagad
                                        </option>
                                        <option value="rocket" {{ $coupon->payment_method == 'rocket' ? 'selected' : '' }}>
                                            Rocket
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- shipping --}}
                            {{-- <div class="row {{ $coupon->apply_on == 'shipping' ? '' : 'hidden' }}" id="shippingField">
                                <div class="col-md-6 mb-3">
                                    <label>Max Shipping Discount</label>
                                    <input type="number" step="0.01" name="shipping_discount"
                                        value="{{ $coupon->shipping_discount }}" class="form-control">
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Usage Limit</label>
                                    <input type="number" class="form-control" name="usage_limit"
                                        value="{{ $coupon->usage_limit }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Per User Limit</label>
                                    <input type="number" class="form-control" name="per_user_limit"
                                        value="{{ $coupon->per_user_limit }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ $coupon->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $coupon->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" value="{{ $coupon->start_date }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" value="{{ $coupon->end_date }}"
                                        class="form-control">
                                </div>
                            </div>

                            <button class="btn btn-success float-end mt-2">Update</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $(".select2").select2();

            $("#type").on("change", function() {
                if ($(this).val() === "percentage") {
                    $(".percentageField").removeClass("hidden");
                } else {
                    $(".percentageField").addClass("hidden");
                }
            });

            $("#apply_on").on("change", function() {
                $("#productField, #paymentField, #shippingField").addClass('hidden');
                let v = $(this).val();

                if (v == "product") $("#productField").removeClass("hidden");
                if (v == "payment") $("#paymentField").removeClass("hidden");
                if (v == "shipping") $("#shippingField").removeClass("hidden");
            });

        });
    </script>
    <script>
        $(document).ready(function() {

            function toggleDynamicFields() {
                let applyOn = $("#apply_on").val();
                let productField = $("#productField");
                let productSelect = $("#productSelect");

                // Reset
                productSelect.removeAttr("required");

                if (applyOn === "product") {
                    productField.removeClass("hidden");
                    productSelect.attr("required", "required"); // required only when visible
                } else {
                    productField.addClass("hidden");
                }

                if (applyOn === "payment") {
                    $("#paymentField").removeClass('hidden');
                } else {
                    $("#paymentField").addClass('hidden');
                }

                if (applyOn === "shipping") {
                    $("#shippingField").removeClass('hidden');
                } else {
                    $("#shippingField").addClass('hidden');
                }
            }

            // Fire on change
            $("#apply_on").on("change", toggleDynamicFields);

            // Fire on page load (important for old() after validation error)
            toggleDynamicFields();
        });
    </script>
@endpush
