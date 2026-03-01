@extends('backEnd.admin.layouts.master')
@section('title')
    Edit Product
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.css') }}">
@endpush
@php
    $setting = App\WebSettings::select('stock_management')->first();
@endphp
@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">

                <div class="row  mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Product
                            <small class="float-end">
                                <a href="{{ route('admin.product') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{ Auth::guard('admin')->check() ? route('admin.product.update', $data->id) : (Auth::guard('manager')->check() ? route('manager.product.update', $data->id) : '') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="thumb_old" value="{{ $data->thumb }}">
                            <input type="hidden" name="image_old" value="{{ $data->image }}">
                            <input type="hidden" name="gallery_images_old" value="{{ $data->gallery_images }}">
                            <div class="row">
                                {{-- <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label" for="theme_id">Frontend Theme <span
                                            class="text-danger">*</span></label>
                                    <select name="theme_id" id="theme_id" class="form-control select2" required>
                                        @foreach ($themes as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $data->theme_id == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-md-6 col-12 mb-3">
                                    @php
                                        $prod_cat = explode(',', $prod_cat);
                                    @endphp
                                    <label class="form-label" for="category_id_e">Product Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id[]" id="category_id_e" class="form-control select2" multiple
                                        required>
                                        @foreach ($categories as $key => $item)
                                            <option value="{{ $key }}"
                                                {{ in_array($key, $prod_cat) ? 'selected' : '' }}>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 col-12">
                                    <label class="form-label" for="name_e">Product Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name_e" name="name"
                                        value="{{ $data->name }}" required>
                                </div>
                            </div>

                            <div class="row g-2 align-items-end mb-3 slug-section">

                                <!-- Slug Input -->
                                <div class="col-md-11 col-12">
                                    <label class="form-label" for="slug">Product Slug
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input type="text" id="slug" name="slug" class="form-control"
                                            value="{{ $data->slug }}" required>
                                        <button type="button" class="btn btn-primary px-3" id="saveBtn" title="Save">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-check" width="22" height="22"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        </button>
                                    </div>

                                    <small id="slug-message"></small>
                                </div>

                            </div>

                            <div class="row">
                                <?php
                                $hover_image = null;
                                foreach ($extraFields as $key => $field) {
                                    if ($field['field_name'] == 'hover_image') {
                                        $hover_image = $field;
                                    }
                                }
                                // dd($hover_image);
                                ?>

                                @if ($hover_image)
                                    <div class="col-4 mb-3">
                                        <label class="form-label"
                                            for="{{ $hover_image->field_name }}">{{ $hover_image->field_label }}</label>
                                        <div id="hoverPreview" class="mb-2">
                                            @if ($data->hover_image_id)
                                                <img width="50" src="{{ asset($data->hover_image_url) }}"
                                                    alt="Hover Image" class="img-fluid">
                                            @endif
                                        </div>
                                        <input type="{{ $hover_image->field_type }}" id="hover_image"
                                            name="{{ $hover_image->field_name }}" class="form-control"
                                            value="{{ old($hover_image->field_name) }}"
                                            {{ $hover_image->is_required ? 'required' : '' }}>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label class="form-label" for="image_e">Feature Image</label>

                                        <div class="featurePreview mb-2">
                                            @if ($data->get_thumb)
                                                <img width="50"
                                                    src="{{ $data->get_thumb ? asset($data->get_thumb->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="">
                                            @endif
                                        </div>

                                        <input type="file" class="form-control" id="featureImage" name="image">
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label class="form-label" for="gallery_image_e">Gallery Image</label>
                                        <div class="galleryPreview mb-2">
                                            @foreach ($data->images as $photo)
                                                <img width="50"
                                                    src="{{ $photo ? asset($photo) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="">
                                            @endforeach
                                        </div>

                                        <input type="file" class="form-control" id="gallery_image_e"
                                            name="gallery_image[]" multiple>
                                    </div>
                                @else
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label" for="image_e">Feature Image</label>

                                        <div class="featurePreview mb-2">
                                            @if ($data->get_thumb)
                                                <img width="50"
                                                    src="{{ $data->get_thumb ? asset($data->get_thumb->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="">
                                            @endif
                                        </div>

                                        <input type="file" class="form-control" id="featureImage" name="image">
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label" for="gallery_image_e">Gallery Image</label>
                                        <div class="galleryPreview mb-2">
                                            @foreach ($data->images as $photo)
                                                <img width="50"
                                                    src="{{ $photo ? asset($photo) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="">
                                            @endforeach
                                        </div>

                                        <input type="file" class="form-control" id="gallery_image_e"
                                            name="gallery_image[]" multiple>
                                    </div>
                                @endif

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label class="form-label" for="sku_e">SKU <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="sku_e"
                                                        data-product_id="{{ $data->id }}" name="sku"
                                                        value="{{ $data->sku }}" required>
                                                    <input type="hidden" id="sku_o" value="{{ $data->sku }}">
                                                    <span class="text-danger" id="error_msg"></span>
                                                    <small id="sku-message"></small>
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label " for="purchase_price">Purchase Price <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="purchase_price" min="0"
                                                        id="purchase_price_e"
                                                        value="{{ formatNumber($data->purchase_price) ?? 0 }}"
                                                        class="form-control  mb-2" required="">
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label" for="sale_price_e">Sale Price</label>
                                                    <input type="text" class="form-control" id="sale_price_e"
                                                        name="sale_price"
                                                        value="{{ formatNumber($data->sale_price) ?? 0 }}">
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label" for="regular_price_e">Regular Price
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="regular_price_e"
                                                        name="regular_price"
                                                        value="{{ formatNumber($data->regular_price) }}" required>
                                                    <input type="hidden" name="old_name" value="{{ $data->name }}">
                                                    <input type="hidden" name="old_slug" value="{{ $data->slug }}">
                                                </div>
                                                @if ($setting?->stock_management == 0)
                                                    <div class="col mb-3">
                                                        <label class="form-label" for="stock_e">Stock</label>
                                                        <input type="number" name="stock" id="stock_e"
                                                            class="form-control" value="{{ $data->stock ?? 0 }}">
                                                    </div>
                                                @endif
                                                <div class="col mb-3">
                                                    <label class="form-label" for="package_qty">Package
                                                        Quantity</label>
                                                    <input type="number" name="package_qty" id="package_qty"
                                                        class="form-control" min="0"
                                                        value="{{ $data->package_qty ?? 1 }}"
                                                        {{ $data->is_combo ? 'readonly' : '' }}>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-17 col-12 mb-3">

                                    <div class="row" id="product-attributes">
                                        <div class="col-12">
                                            <div class="card">


                                                <div class="card-header">
                                                    <h4 class="mb-1">Variants</h4>
                                                </div>
                                                {{-- @dd($data->get_product_choice_attributes); --}}
                                                @php
                                                    $grouped_attributes = $data->get_variant_item_ids();
                                                    $selected_attributes = array_keys($grouped_attributes);

                                                    // শুধুমাত্র item id (flatten করে)
                                                    $selected_attribute_items = collect($grouped_attributes)
                                                        ->flatten()
                                                        ->unique()
                                                        ->toArray();

                                                @endphp

                                                {{-- @dd($selected_attributes, $selected_attribute_items) --}}

                                                <div class="card-body">

                                                    <div class="row attribute-row">
                                                        <input type="hidden" name="v_purchase_price"
                                                            id="v_purchase_price">
                                                        <input type="hidden" name="v_regular_price"
                                                            id="v_regular_price">
                                                        <input type="hidden" name="v_sale_price" id="v_sale_price">
                                                        <input type="hidden" name="h_sku" id="h_sku">
                                                        <input type="hidden" name="id"
                                                            value="{{ $data->id }}">
                                                        @foreach ($attributes as $key => $attribute)
                                                            <div class="form-group col-md-3 col-12">
                                                                <div
                                                                    class="attribute-header d-flex justify-content-between align-items-center mb-2">
                                                                    <label class="text-capitalize form-label"
                                                                        for="attribute_item_id{{ $key }}">
                                                                        <input type="checkbox"
                                                                            id="attribute_item_id{{ $key }}"
                                                                            data-is_image="{{ $attribute->is_image }}"
                                                                            class="attribute_id"
                                                                            value="{{ $attribute->id }}"
                                                                            {{ in_array($attribute->id, $selected_attributes) ? 'checked' : '' }}>
                                                                        {{ $attribute->name }}</label>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary item-add-btn"
                                                                        id="attributeItemCreateBtn"
                                                                        {{ in_array($attribute->id, $selected_attributes) ? '' : 'disabled' }}
                                                                        onclick="$('#hidden_attribute_id').val({{ $attribute->id }})">
                                                                        <i class="ti ti-plus"></i> Add
                                                                        Item
                                                                    </button>

                                                                </div>



                                                                <select name="attribute[{{ $attribute->id }}][]"
                                                                    id="attribute_item_id{{ $key }}"
                                                                    class="form-control select attribute_item_id" multiple
                                                                    {{ in_array($attribute->id, $selected_attributes) ? '' : 'disabled' }}
                                                                    required>
                                                                    @foreach ($attribute->items as $att_item)
                                                                        <option data-name="{{ $att_item->name }}"
                                                                            value="{{ $att_item->id }}"
                                                                            {{ in_array($att_item->id, $selected_attribute_items) ? 'selected' : '' }}>
                                                                            {{ $att_item->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <div class="card attribute-div" style="display: none;">
                                                                <div class="card-header">
                                                                    <h4 class="mb-0">Variants Image</h4>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row attribute-image-container">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-10 col-12" id="attribute-table-put">
                                                            @if (count($data->get_variants) > 0)
                                                                <table class="table table-bordered my-2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center">
                                                                                Variant
                                                                            </th>

                                                                            <th class="text-center">
                                                                                SKU
                                                                            </th>
                                                                            <th class="text-center">
                                                                                Purchase Price
                                                                            </th>
                                                                            <th class="text-center">
                                                                                Regular Price
                                                                            </th>
                                                                            <th class="text-center">
                                                                                Sale Price
                                                                            </th>
                                                                            @if ($setting->stock_management == 0)
                                                                                <th class="text-center">
                                                                                    Quantity
                                                                                </th>
                                                                            @endif
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($data->get_variants as $key => $variant)
                                                                            @php
                                                                                $variant_name = $variant->items
                                                                                    ->map(function ($item) {
                                                                                        $attrName =
                                                                                            $item->attribute->name ??
                                                                                            '';
                                                                                        $itemName = $item->name ?? '';
                                                                                        return $attrName .
                                                                                            ': ' .
                                                                                            $itemName;
                                                                                    })
                                                                                    ->implode(', ');

                                                                            @endphp

                                                                            <tr class="attribute">
                                                                                <td class="text-center">
                                                                                    <label for=""
                                                                                        class="control-label">{{ $variant_name }}</label>
                                                                                    {{-- <input type="hidden"
                                                                                        name="attribute_name[]"
                                                                                        value="{{ $variant->attribute }}"> --}}
                                                                                </td>

                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="variant_sku[]"
                                                                                        value="{{ $variant->sku }}"
                                                                                        class="form-control" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        name="variant_purchase_price[]"
                                                                                        value="{{ formatNumber($variant->purchase_price) ?? 0 }}"
                                                                                        min="0" step="0.01"
                                                                                        class="form-control auto-select-number"
                                                                                        required>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        name="variant_regular_price[]"
                                                                                        value="{{ formatNumber($variant->regular_price) ?? 0 }}"
                                                                                        min="0" step="0.01"
                                                                                        class="form-control auto-select-number"
                                                                                        required>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        name="variant_sale_price[]"
                                                                                        value="{{ formatNumber($variant->sale_price) ?? 0 }}"
                                                                                        min="0" step="0.01"
                                                                                        class="form-control auto-select-number"
                                                                                        required>
                                                                                </td>
                                                                                @if ($setting->stock_management == 0)
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            lang="en"
                                                                                            name="variant_stock[]"
                                                                                            value="{{ $variant->stock }}"
                                                                                            min="0" step="1"
                                                                                            class="form-control auto-select-number"
                                                                                            required>
                                                                                    </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @dd($extraFields) --}}


                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description_e">Product Description</label>
                                    <textarea name="description" id="ckeditor" id="description_e" class="summernote">
                                        {!! $data->description !!}
                                    </textarea>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label" for="status_e">Status</label>
                                    <select name="status" id="status_e" class="form-control">
                                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Unpublished
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-8 col-12 mb-3">
                                    <label class="form-label" for="related_products"> Related Products </label>
                                    <select name="related_products[]" id="related_products" class="form-control select2"
                                        multiple>
                                        @foreach ($products as $productItem)
                                            <option value="{{ $productItem->id }}"
                                                @if (!empty($data->related_products) && in_array($productItem->id, $data->related_products)) selected @endif>
                                                {{ $productItem->name }} #{{ $productItem->sku }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" id="form_update_btn" class="btn btn-success float-end">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- attribute item create modal --}}
    <div class="modal fade" id="attributeItemCreateModal" tabindex="-1" aria-labelledby="attributeItemCreateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attributeItemCreateModalLabel">Add Variant Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="attributeItemForm">
                    @csrf
                    <input type="hidden" name="hidden_attribute_id" id="hidden_attribute_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Variant Item Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span class="text-danger name_error mt-2"></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.js') }}"></script>
    {{-- image preview --}}
    <script>
        $(document).ready(function() {
            $('#featureImage').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.featurePreview').html('<img src="' + e.target.result + '" width="50">');
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('#hover_image').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#hoverPreview').html('<img src="' + e.target.result + '" width="50">');
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
        //attribute
        $('.attribute_id').on('click', function() {
            var parent = $(this).closest('.form-group');
            if ($(this).is(':checked', true)) {
                parent.find('select').prop('disabled', false);
                parent.find('.item-add-btn').prop('disabled', false);
            } else {
                parent.find('select').prop('disabled', true);
                parent.find('.item-add-btn').prop('disabled', true);
            }
        });
        //check unique slug
        $(document).on('keyup', '#slug_e', function() {
            let value = $(this).val().replaceAll(' ', '-').toLowerCase();
            $(this).val(value);
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.check.product.unique.slug') }}',
                data: {
                    slug: $(this).val()
                },
                success: function(response) {
                    if (response.success) {
                        $('.slug_text').text(response.success);
                        $('.slug_text').addClass('text-success');
                        $('.slug_text').removeClass('text-danger');
                    }

                    if (response.error) {
                        $('.slug_text').text(response.error);
                        $('.slug_text').removeClass('text-success');
                        $('.slug_text').addClass('text-danger');
                    }
                }
            });
        });
        $(document).on('keyup', '#sku_e', function() {
            let sku = $(this).val().replaceAll(' ', '-').toLowerCase();
            let productId = $(this).data('product_id');
            console.log(sku, productId);
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.check.product.unique.sku') }}',
                data: {
                    sku: sku,
                    productId: productId,
                },
                success: function(response) {
                    if (response.success) {
                        $('#sku-message').text(response.success);
                        $('#sku-message').addClass('text-success');
                        $('#sku-message').removeClass('text-danger');
                        $("#form_update_btn").prop('disabled', false);
                    }

                    if (response.error) {
                        $('#sku-message').text(response.error);
                        $('#sku-message').removeClass('text-success');
                        $('#sku-message').addClass('text-danger');
                        $("#form_update_btn").prop('disabled', true);
                    }
                }
            });
        });

        function variants() {
            //$('#v_prod_name').val($('#name').val());
            $('#v_purchase_price').val($('#purchase_price_e').val());
            $('#v_regular_price').val($('#regular_price_e').val());
            $('#v_sale_price').val($('#sale_price_e').val());
            $('#h_sku').val($('#sku_e').val());

            var CSRF = `{{ csrf_token() }}`;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                type: "POST",
                url: `{{ route('admin.product.ajax.get.combined.attributes.edit') }}`,
                data: $("#product-attributes").find('select, input').serialize(),
                beforeSend: function() {
                    // $('#contentLoader').fadeIn();
                    // $('#contentWrapper').css('pointer-events', 'none'); // block content
                },
                success: function(data) {
                    if (data) {
                        //hide_sku_stock();
                        $('#attribute-table-put').empty().html(data);

                    } else {
                        //show_sku_stock();
                        $('#attribute-table-put').empty();
                    }
                },
                // complete: function() {
                //     $('#fullPageLoader').fadeOut(200);
                // }
            });
        }



        function updateVariantImages() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                type: "POST",
                url: `{{ route('admin.product.ajax.get.color.image.edit') }}`,
                data: $(".attribute-row").find('input ,select').serialize(),
                success: function(data) {
                    if (data) {
                        $('.attribute-div').show();
                        $('.attribute-image-container').empty().html(data);
                    } else {
                        $('.attribute-div').hide();
                        $('.attribute-image-container').empty();
                    }
                }
            });


        }
        $(document).ready(function() {
            updateVariantImages();
            // variants();
        });

        $(document).on('change', '.attribute_item_id', function() {
            updateVariantImages();
            variants();
        });

        $(document).on("click", ".auto-select-number", function() {
            if ($(this).val() <= 0) {
                $(this).select();
            }
        });

        $('#purchase_price_e').on('keyup', function() {
            variants();
        });

        $('#regular_price_e').on('keyup', function() {
            variants();
        });

        $('#sale_price_e').on('keyup', function() {
            variants();
        });
        $('#sku_e').on('keyup', function() {
            variants();
        });
    </script>

    {{-- summernote --}}
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
                callbacks: {

                    // 🔼 MULTIPLE IMAGE UPLOAD
                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImage(files[i]);
                        }
                    },

                    // ❌ IMAGE DELETE (cut / delete)
                    onMediaDelete: function(target) {
                        let src = target[0].src;
                        deleteImage(src);
                    }
                },
                codeviewFilter: false,
                codeviewIframeFilter: false,

            });

            function uploadImage(file) {
                let data = new FormData();
                data.append('image', file);
                data.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('summernote.upload') }}',
                    method: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('.summernote').summernote('insertImage', res.url);
                    },
                    error: function(xhr) {
                        let res = xhr.responseJSON;
                        alert(res.errors.image[0]);
                    }
                });
            }

            function deleteImage(src) {
                $.ajax({
                    url: '{{ route('summernote.delete') }}',
                    method: 'POST',
                    data: {
                        src: src,
                        _token: '{{ csrf_token() }}'
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select attribute",
            });

        });
    </script>
    <script>
        function resetVariantModal(attributeId) {
            $('#attributeItemForm')[0].reset();
            $('#hidden_attribute_id').val(attributeId);
            $('.name_error').text('');
            $('.preview-img').attr('src', '');
        }
        // #attributeItemCreateModal form submit
        $(document).on('click', '.item-add-btn', function(e) {
            $('#attributeItemCreateModal').modal('show');
            resetVariantModal($('#hidden_attribute_id').val());
        });
        // #attributeItemCreateModal form submit
        $(document).on('submit', '#attributeItemForm', function(e) {
            e.preventDefault();
            var $form = $(this);

            $.ajax({
                type: "POST",
                url: `{{ route('admin.product.attribute.item.store') }}`,
                data: $form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': `{{ csrf_token() }}`
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#attributeItemCreateModal').modal('hide');
                        $form[0].reset();

                        var attributeId = $('#hidden_attribute_id').val();
                        var $select = $(`select[name="attribute[${attributeId}][]"]`);

                        var newOption = new Option(response.item.name, response.item.id, true, true);
                        $select.append(newOption).trigger('change');
                        //  update_sku();
                    } else {
                        $('.name_error').text(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error adding attribute item:', error);
                    console.error('Response:', xhr.responseText);
                    alert("Something went wrong! Please try again.");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Function to convert name to slug
            function generateSlug(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                    .replace(/\-\-+/g, '-') // Replace multiple - with single -
                    .replace(/^-+/, '') // Trim - from start
                    .replace(/-+$/, ''); // Trim - from end
            }

            // Auto-fill slug when typing name
            $("#name_e").on("keyup", function() {
                let slug = generateSlug($(this).val());
                $("#slug").val(slug);
                checkSlug(slug); // check live
            });

            // Live check when typing slug manually
            $("#slug").on("keyup change", function() {
                let slug = $(this).val();
                checkSlug(slug);
            });
            // Ajax check function
            function checkSlug(slug) {
                if (slug.length < 2) {
                    $("#slug-message").text("");
                    return;
                }

                $.ajax({
                    url: "{{ route('product.check.slug') }}",
                    type: "GET",
                    data: {
                        slug: slug
                    },
                    success: function(res) {
                        if (res.exists) {
                            $("#slug-message")
                                .text("This slug is already taken!")
                                .css("color", "red");
                            $("#saveBtn").prop('disabled', true);
                            $("#form_update_btn").prop('disabled', true);
                        } else {
                            $("#form_update_btn").prop('disabled', false);
                            $("#saveBtn").prop('disabled', false);
                            $("#slug-message").text("");
                        }
                    }
                });
            }

        });
    </script>
    <script>
        $("#saveBtn").on("click", function(e) {
            e.preventDefault();
            let name = $("#name_e").val();
            let slug = $("#slug").val();
            let id = "{{ $data->id }}";
            $.ajax({
                url: "{{ route('admin.product.quickSlugUpdate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    name: name,
                    slug: slug
                },
                success: function(res) {
                    if (res.status === 'error') {
                        $("#slug-message").html(
                            `<span class="text-danger">${res.message}</span>`
                        );
                    } else {
                        $("#slug-message").html(
                            `<span class="text-success">${res.message}</span>`
                        );
                    }
                }
            });
        });
    </script>
@endpush
