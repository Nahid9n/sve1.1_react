@extends('backEnd.admin.layouts.master')
@section('title', 'Create Coupon')

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
                            Create Coupon
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

                        <form action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                                        required>
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Discount Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                    </select>
                                    @error('type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="amount"
                                        value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div
                                    class="col-md-4 mb-3 percentageField {{ old('type') == 'percentage' ? '' : 'hidden' }}">
                                    <label class="form-label">Max Discount Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="max_discount"
                                        value="{{ old('max_discount') }}">
                                    @error('max_discount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Minimum Purchase</label>
                                    <input type="number" step="0.01" class="form-control" name="min_purchase"
                                        value="{{ old('min_purchase', 0) }}">
                                    @error('min_purchase')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Apply On</label>
                                    <select name="apply_on" id="apply_on" class="form-control">
                                        <option value="cart" {{ old('apply_on') == 'cart' ? 'selected' : '' }}>Cart
                                        </option>
                                        <option value="product" {{ old('apply_on') == 'product' ? 'selected' : '' }}>
                                            Specific Product</option>
                                        <option value="shipping" {{ old('apply_on') == 'shipping' ? 'selected' : '' }}>
                                            Shipping</option>
                                        <option value="payment" {{ old('apply_on') == 'payment' ? 'selected' : '' }}>
                                            Payment Method</option>
                                        <option value="first_time" {{ old('apply_on') == 'first_time' ? 'selected' : '' }}>
                                            First Time User</option>
                                    </select>
                                    @error('apply_on')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            {{-- Product field --}}
                            <div class="row {{ old('apply_on') == 'product' ? '' : 'hidden' }}" id="productField">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Select Products <span class="text-danger">*</span></label>
                                    <select class="form-control select2" multiple name="product_ids[]">
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}"
                                                {{ collect(old('product_ids'))->contains($item->id) ? 'selected' : '' }}>
                                                {{ $item->name }} (#{{ $item->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_ids')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            {{-- Payment method field --}}
                            <div class="row {{ old('apply_on') == 'payment' ? '' : 'hidden' }}" id="paymentField">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select class="form-control" name="payment_method">
                                        <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash
                                            On Delivery</option>
                                        <option value="sslcommerz"
                                            {{ old('payment_method') == 'sslcommerz' ? 'selected' : '' }}>SSLCommerz
                                        </option>
                                        <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>
                                            bkash</option>
                                        <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>
                                            Nagad</option>
                                        <option value="rocket" {{ old('payment_method') == 'rocket' ? 'selected' : '' }}>
                                            Rocket</option>
                                    </select>
                                    @error('payment_method')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            {{-- Shipping Coupon --}}
                            {{-- <div class="row hidden" id="shippingField">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Max Shipping Discount <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="0.01" class="form-control" name="shipping_discount">
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Usage Limit (Total)</label>
                                    <input type="number" class="form-control" name="usage_limit"
                                        value="{{ old('usage_limit') }}">
                                    @error('usage_limit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Per User Limit</label>
                                    <input type="number" class="form-control" name="per_user_limit"
                                        value="{{ old('per_user_limit') }}">
                                    @error('per_user_limit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <button class="btn btn-success float-end mt-2">Create</button>

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
            // Type = percentage → show max_discount
            $("#type").on("change", function() {
                if ($(this).val() === "percentage") {
                    $(".percentageField").removeClass('hidden');
                } else {
                    $(".percentageField").addClass('hidden');
                }
            });
            // Apply On change
            $("#apply_on").on("change", function() {
                $("#productField, #paymentField, #shippingField").addClass('hidden');
                let v = $(this).val();

                if (v === "product") {
                    $("#productField").removeClass('hidden');
                }
                if (v === "payment") {
                    $("#paymentField").removeClass('hidden');
                }
                if (v === "shipping") {
                    $("#shippingField").removeClass('hidden');
                }
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
