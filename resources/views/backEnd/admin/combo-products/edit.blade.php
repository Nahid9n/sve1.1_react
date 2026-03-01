@extends('backEnd.admin.layouts.master')
@section('title')
    Edit Combo Product
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.css') }}">
    <style>
        .ck-editor__editable_inline {
            height: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">
                <div class="row mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Combo Product
                            <small class="float-end">
                                <a href="{{ route('admin.combo-products.index') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-arrow-left"></i> Back
                                </a>
                            </small>
                        </h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.combo-products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data" id="comboForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Category <span class="text-danger">*</span></label>
                                    <select name="category_id[]" class="form-control select2" multiple required>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}"
                                                {{ in_array($c->id, $cats) ? 'selected' : '' }}>
                                                {{ $c->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input name="name" id="name" class="form-control"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label" for="image_e">Feature Image</label>
                                    <div class="featurePreview mb-2">
                                        @if ($product->get_thumb)
                                            <img width="50" src="{{ asset($product->get_thumb->file_url) }}"
                                                alt="">
                                        @endif
                                    </div>

                                    <input type="file" class="form-control" id="featureImage" name="image">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label" for="gallery_image_e">Gallery Image</label>
                                    <div class="galleryPreview mb-2">
                                        @foreach ($product->images as $photo)
                                            <img width="50"
                                                src="{{ $photo ? asset($photo) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                alt="">
                                        @endforeach
                                    </div>

                                    <input type="file" class="form-control" id="gallery_image_e" name="gallery_image[]"
                                        multiple>
                                </div>

                                <div class="col mb-3">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control"
                                        value="{{ old('sku', $product->sku) }}" required>
                                    <small id="sku_status" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Combo Products <span class="text-danger">*</span></label>
                                <select id="combo_products" class="form-control select2" name="combo_products[]" multiple
                                    required>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}"
                                            {{ in_array($p->id, $selectedProducts) ? 'selected' : '' }}>
                                            {{ $p->name }} (SKU: {{ $p->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product details table -->
                            <div class="card mb-3" id="productDetailsCard" style="display:block;">
                                <div class="card-header">Product Details</div>
                                <div class="card-body">
                                    <table class="table" id="comboDetailsTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Purchase Price</th>
                                                <th>Regular Price</th>
                                                <th>Sale Price</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->comboItems as $item)
                                                <tr data-id="{{ $item->product->id }}">
                                                    <td>{{ $item->product->name }}
                                                        <input type="hidden" name="combo_product_ids[]"
                                                            value="{{ $item->product->id }}">
                                                    </td>
                                                    <td><input type="number" step="0.01"
                                                            class="form-control cp_purchase" name="cp_purchase[]"
                                                            value="{{ formatNumber($item->purchase_price) }}"></td>
                                                    <td><input type="number" step="0.01" class="form-control cp_regular"
                                                            name="cp_regular[]"
                                                            value="{{ formatNumber($item->regular_price) }}"></td>
                                                    <td><input type="number" step="0.01" class="form-control cp_sale"
                                                            name="cp_sale[]" value="{{ formatNumber($item->sale_price) }}">
                                                    </td>

                                                    <td><input type="number" step="0.01" class="form-control cp_qty"
                                                            name="cp_qty[]" value="{{ $item->quantity ?? 1 }}"></td>

                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger removeRow">X</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <input type="hidden" name="purchase_price" id="purchase_price">
                                                <input type="hidden" name="regular_price" id="regular_price">
                                                <input type="hidden" name="sale_price" id="sale_price">

                                                <th>Total</th>
                                                <th id="total_purchase">0.00</th>
                                                <th id="total_regular">0.00</th>
                                                <th id="total_sale">0.00</th>
                                                <th id="total_quantity">0</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="summernote">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Unpublished
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-success float-end">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#featureImage').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.featurePreview').html('<img src="' + e.target.result + '" width="50">');
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('#gallery_image_e').change(function() {
                $('.galleryPreview').html('');
                Array.from(this.files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.galleryPreview').append('<img src="' + e.target.result +
                            '" width="50">');
                    }
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>

    <script>
        $(function() {
            $('.select2').select2();
            $('.summernote').summernote({
                height: 200
            });

            // SKU uniqueness check
            let skuTimer;
            $('#sku').on('input', function() {
                clearTimeout(skuTimer);
                const val = $(this).val().trim();
                $('#sku_status').text('');
                if (!val) return;
                skuTimer = setTimeout(() => {
                    $.get("{{ route('admin.combo-products.check-sku') }}", {
                            sku: val
                        })
                        .done(function(res) {
                            if (res.exists && val !== "{{ $product->sku }}") {
                                $('#sku_status').text('SKU already exists').addClass(
                                    'text-danger');
                            } else {
                                $('#sku_status').text('SKU is available').removeClass(
                                        'text-danger')
                                    .addClass('text-success');
                            }
                        });
                }, 400);
            });

            // fetch products
            $('#combo_products').on('change', function() {
                const ids = $(this).val() || [];
                if (ids.length === 0) {
                    $('#comboDetailsTable tbody').html('');
                    recalcTotals();
                    return;
                }
                $.post("{{ route('admin.combo-products.fetch-products') }}", {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                }).done(function(res) {
                    const products = res.products;
                    let tbody = '';
                    products.forEach(function(p) {
                        tbody += `<tr data-id="${p.id}">
                            <td>${p.name}<input type="hidden" name="combo_product_ids[]" value="${p.id}"></td>
                            <td><input type="number" step="0.01" class="form-control cp_purchase" name="cp_purchase[]" value="${parseFloat(p.purchase_price || 0).toFixed(2)}"></td>
                            <td><input type="number" step="0.01" class="form-control cp_regular" name="cp_regular[]" value="${parseFloat(p.regular_price || 0).toFixed(2)}"></td>
                            <td><input type="number" step="0.01" class="form-control cp_sale" name="cp_sale[]" value="${parseFloat(p.sale_price || 0).toFixed(2)}"></td>

                            <td><input type="number" step="0.01" class="form-control cp_qty" name="cp_qty[]" value="${p.package_qty || 1}"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
                        </tr>`;
                    });
                    $('#comboDetailsTable tbody').html(tbody);
                    recalcTotals();
                    $('#productDetailsCard').slideDown(200);
                });
            });

            // remove row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                const id = $(this).closest('tr').data('id').toString();
                let selected = $('#combo_products').val() || [];
                selected = selected.filter(i => i !== id);
                $('#combo_products').val(selected).trigger('change.select2');
                recalcTotals();
            });

            // recalc
            $(document).on('input', '.cp_purchase, .cp_sale, .cp_regular', recalcTotals);

            function recalcTotals() {
                let tPurchase = 0,
                    tSale = 0,
                    tRegular = 0;
                tQty = 0;
                $('#comboDetailsTable tbody tr').each(function() {
                    const purchase = parseFloat($(this).find('.cp_purchase').val() || 0);
                    const sale = parseFloat($(this).find('.cp_sale').val() || 0);
                    const regular = parseFloat($(this).find('.cp_regular').val() || 0);
                    const qty = parseFloat($(this).find('.cp_qty').val()) || 1; // fallback to 1
                    tPurchase += purchase;
                    tRegular += regular;
                    tSale += sale;
                    tQty += qty;


                });
                $('#total_purchase').text(tPurchase.toFixed(2));
                $('#purchase_price').val(tPurchase.toFixed(2));
                $('#total_regular').text(tRegular.toFixed(2));
                $('#regular_price').val(tRegular.toFixed(2));
                $('#total_sale').text(tSale.toFixed(2));
                $('#sale_price').val(tSale.toFixed(2));
                $('#total_quantity').text(tQty);
            }

            recalcTotals();
        });
    </script>
@endpush
