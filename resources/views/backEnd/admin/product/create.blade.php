@extends('backEnd.admin.layouts.master')
@section('title')
    Create Product
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
                            Product Create
                            <small class="float-end">
                                <a href="{{ route('admin.product') }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-angle-double-left"></i>
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label" for="theme_id">Frontend Theme <span
                                            class="text-danger">*</span></label>
                                    <select name="theme_id" id="theme_id" class="form-control select2" required>
                                        @foreach ($themes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label" for="category_id">Product Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id[]" id="category_id" class="form-control select2" required
                                        multiple>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="name">Product Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="slug">Product Slug <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="slug" name="slug" class="form-control">
                                    <small id="slug-message"></small>
                                </div>
                            </div>


                            <div class="row">
                                @php
                                    $hover_image = null;
                                    foreach ($extraFields as $key => $field) {
                                        if ($field['field_name'] == 'hover_image') {
                                            $hover_image = $field;
                                        }
                                    }
                                    // dd($hover_image);
                                @endphp

                                @if ($hover_image)
                                    <div class="col-4 mb-3">
                                        <label class="form-label"
                                            for="{{ $hover_image['field_name'] }}">{{ $hover_image['field_label'] }}</label>
                                        <div id="hoverPreview" class="mb-2"> </div>
                                        <input type="{{ $hover_image['field_type'] }}" id="hover_image"
                                            name="{{ $hover_image['field_name'] }}" class="form-control"
                                            value="{{ old($hover_image['field_name']) }}"
                                            {{ $hover_image['is_required'] ? 'required' : '' }}>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label class="form-label" for="image">Feature Image</label>
                                        <div id="featurePreview" class="mb-2"> </div>
                                        <input type="file" class="form-control" id="featureImage" name="image">
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label class="form-label" for="gallery_image">Gallery Image</label>
                                        <div id="galleryPreview" class="d-flex gap-2"></div>
                                        <input type="file" class="form-control" id="gallery_image" name="gallery_image[]"
                                            multiple>
                                    </div>
                                @else
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label" for="image">Feature Image</label>
                                        <div id="featurePreview" class="mb-2"> </div>
                                        <input type="file" class="form-control" id="featureImage" name="image">
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label" for="gallery_image">Gallery Image</label>
                                        <div id="galleryPreview" class="d-flex gap-2"></div>
                                        <input type="file" class="form-control" id="gallery_image" name="gallery_image[]"
                                            multiple>
                                    </div>
                                @endif

                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label class="form-label" for="sku">SKU <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="sku"
                                                        class="form-control product_sku mb-2" id="sku"
                                                        placeholder="Sku" required="">
                                                    <span class="sku_text fw-bold"></span>
                                                    <span class="sku_error_msg class text-danger fw-bold"></span>
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label" for="purchase_price">Purchase Price <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="purchase_price" min="0"
                                                        value="0" class="form-control  mb-2" id="purchase_price"
                                                        required="">
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label" for="regular_price">Regular Price <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="regular_price"
                                                        name="regular_price" value="0" required>
                                                </div>
                                                <div class="col mb-3">
                                                    <label class="form-label" for="sale_price">Sale Price</label>
                                                    <input type="text" class="form-control" id="sale_price"
                                                        name="sale_price" min="0" value="0">
                                                </div>

                                                @if ($setting?->stock_management == 0)
                                                    <div class="col mb-3">
                                                        <label class="form-label" for="stock">Stock</label>
                                                        <input type="number" name="stock" id="stock"
                                                            class="form-control" min="0" value="0">

                                                    </div>
                                                @endif

                                                <div class="col mb-3">
                                                    <label class="form-label" for="package_qty">Package Quantity</label>
                                                    <input type="number" name="package_qty" id="package_qty"
                                                        class="form-control" min="0" value="1">

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-md-12 col-12 mb-3">
                                    <div class="row" id="product-attributes">
                                        <div class="col-12">
                                            <div class="card">
                                                <input type="hidden" name="v_purchase_price" id="v_purchase_price">
                                                <input type="hidden" name="v_regular_price" id="v_regular_price">
                                                <input type="hidden" name="v_sale_price" id="v_sale_price">
                                                <input type="hidden" name="h_sku" id="h_sku">
                                                <div class="card-header">
                                                    <h4 class="mb-1">Attributes</h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row attribute-row">
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
                                                                            value="{{ $attribute->id }}">
                                                                        {{ $attribute->name }}
                                                                    </label>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary item-add-btn"
                                                                        id="attributeItemCreateBtn" disabled
                                                                        onclick="$('#hidden_attribute_id').val({{ $attribute->id }})">
                                                                        <i class="ti ti-plus"></i> Add
                                                                        Item
                                                                    </button>

                                                                </div>




                                                                <select name="attribute[{{ $attribute->id }}][]"
                                                                    id="attribute_item_id{{ $key }}"
                                                                    class="form-control select attribute_item_id" multiple
                                                                    disabled required>
                                                                    @foreach ($attribute->items as $att_item)
                                                                        <option data-name="{{ $att_item->name }}"
                                                                            value="{{ $att_item->id }}">
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
                                                                    <h4 class="mb-0">Attributes Image</h4>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row attribute-image-container">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-10 col-12" id="attribute-table-put"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- @dd($extraFields) --}}
                            {{-- @if ($extraFields && $extraFields->isNotEmpty())
                                <div class="row">
                                    @foreach ($extraFields as $key => $field)
                                        @if ($field->field_type == 'file' && $field->field_name == 'hover_image')
                                            @php
                                                $type = in_array($field->field_type, ['file'])
                                                    ? $field->field_type
                                                    : 'text';
                                            @endphp
                                            <div class="col-4 mb-3">
                                                <label class="form-label"
                                                    for="{{ $field->field_name }}">{{ $field->field_label }}</label>
                                                <div id="hoverPreview" class="mb-2"> </div>
                                                <input type="{{ $type }}" id="hover_image"
                                                    name="{{ $field->field_name }}" class="form-control"
                                                    value="{{ old($field->field_name) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif --}}


                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="description">Product Description</label>
                                    <textarea name="description" class="summernote"></textarea>
                                    @error('image')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row d-flex align-items-center">
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Published</option>
                                        <option value="0">Unpublished</option>
                                    </select>
                                </div>
                                <div class="col-md-8 col-12 mb-3">
                                    <label class="form-label" for="related_products"> Related Products </label>
                                    <select name="related_products[]" id="related_products" class="form-control select2"
                                        multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}
                                                #{{ $product->sku }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-2">
                                <input type="submit" class="btn btn-success float-end" id="form_add_btn"
                                    value="Create">
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
                    <h5 class="modal-title" id="attributeItemCreateModalLabel">Add Attribute Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="attributeItemForm">
                    @csrf
                    <input type="hidden" name="hidden_attribute_id" id="hidden_attribute_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Attribute Item Name</label>
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
                    $('#featurePreview').html('<img src="' + e.target.result + '" width="50">');
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
            $('#gallery_image').change(function() {
                $('#galleryPreview').html('');
                Array.from(this.files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#galleryPreview').append('<img src="' + e.target.result +
                            '" width="50">');
                    }
                    reader.readAsDataURL(file);
                });
            });

        });
    </script>

    {{-- select2 --}}
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select attribute",
            });
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
    {{-- attribute --}}
    <script>
        // $('.attribute_id').on('click', function() {
        //     if ($(this).is(':checked', true)) {
        //         $(this).closest('.form-group').find('select').prop('disabled', false);
        //     } else {
        //         $(this).closest('.form-group').find('select').prop('disabled', true);
        //     }
        // });

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

            // Check if **any checkbox is checked**
            // if ($('.attribute_id:checked').length > 0) {
            //     // At least one checked → hide stock
            //     $('.stock-div').addClass('d-none').removeClass('d-block');
            // } else {
            //     // None checked → show stock
            //     $('.stock-div').removeClass('d-none').addClass('d-block');
            // }
        });




        function variants() {
            $('#v_purchase_price').val($('#purchase_price').val());
            $('#v_sale_price').val($('#sale_price').val());
            $('#v_regular_price').val($('#regular_price').val());
            $('#h_sku').val($('#sku').val());

            var CSRF = `{{ csrf_token() }}`;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                type: "POST",
                url: `{{ route('admin.product.ajax.get.combined.attributes') }}`,
                data: $("#product-attributes").find('select, input').serialize(),
                success: function(data) {
                    if (data) {
                        //hide_sku_stock();
                        $('#attribute-table-put').empty().html(data);
                    } else {
                        //show_sku_stock();
                        $('#attribute-table-put').empty();
                    }
                }
            });
        }


        $(document).on('keyup', '#sku', function() {
            let value = $(this).val().replaceAll(' ', '-').toLowerCase();
            variants();
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.check.product.unique.sku') }}',
                data: {
                    sku: value,
                },
                success: function(response) {
                    if (response.success) {
                        $('.sku_text').text(response.success);
                        $('.sku_text').addClass('text-success');
                        $('.sku_text').removeClass('text-danger');
                    }

                    if (response.error) {
                        $('.sku_text').text(response.error);
                        $('.sku_text').removeClass('text-success');
                        $('.sku_text').addClass('text-danger');
                    }
                }
            });
        });
        //check unique slug
        $(document).on('input', '#slug', function() {

            let value = $(this).val().replaceAll(' ', '-').toLowerCase();
            $(this).val(value);

            variants();

            $.ajax({
                type: 'GET',
                url: '{{ route('admin.check.product.unique.slug') }}',
                data: {
                    slug: value
                },
                success: function(response) {

                    if (response.success) {
                        $('.slug_text')
                            .text(response.success)
                            .addClass('text-success')
                            .removeClass('text-danger');
                    }

                    if (response.error) {
                        $('.slug_text')
                            .text(response.error)
                            .addClass('text-danger')
                            .removeClass('text-success');
                    }
                }
            });
        });

        function updateVariantImages() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                type: "POST",
                url: `{{ route('admin.product.ajax.get.color.image') }}`,
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

        $(document).on('change', '.attribute_item_id', function() {
            updateVariantImages();
            variants();
        });

        $('#purchase_price').on('keyup', function() {
            variants();
        });

        $('#regular_price').on('keyup', function() {
            variants();
        });

        $('#sale_price').on('keyup', function() {
            variants();
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
            $("#name").on("input", function() {
                let slug = generateSlug($(this).val());
                $("#slug").val(slug);
                checkSlug(slug);
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
                            $("#form_add_btn").prop('disabled', true);
                        } else {
                            $("#form_add_btn").prop('disabled', false);
                            $("#slug-message").text("");
                        }
                    }
                });
            }

        });
    </script>
@endpush
