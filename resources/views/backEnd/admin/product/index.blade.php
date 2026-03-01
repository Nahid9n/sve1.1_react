@extends('backEnd.admin.layouts.master')
@section('title')
    Products
@endsection
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>
                        Product List
                        <small class="float-end">
                            Total Products: {{ $products->total() }}
                        </small>
                    </h3>

                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="action-btn d-flex gap-2">
                        @can('products.create')
                            <a href="{{ route('admin.product.create') }}" class="btn btn-success btn-sm">
                                <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                                Add Product</a>
                        @endcan
                        <form action="{{ route('admin.product.bulk.delete') }}" method="post" id="all_delete_form"
                            class="d-flex">
                            @csrf
                            <input type="hidden" id="all_delete_id" name="all_delete_id" class="all_delete_id">
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-gradient-danger bulk_button d-flex align-items-center gap-1"
                                id="bulk_delete_btn">
                                <i class="ti ti-trash"></i>Bulk Delete
                            </a>
                        </form>
                    </div>
                    <div class="search-section d-flex">
                        <form action="{{ route('admin.product') }}" method="GET" id="search_form">
                            <div class="input-group" style="width: 400px;">
                                <select class="form-select form-select-sm  me-2" name="status" id="product_status">
                                    <option value="">-- Select Status --</option>
                                    <option value="1"{{ $status == 1 ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ $status != null && $status == 0 ? 'selected' : '' }}>
                                        Unpublished</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm small-search me-2"
                                    placeholder="Search..." value="{{ request()->search }}">
                                {{-- <button type="submit" class="btn btn-info btn-sm me-1">Search</button> --}}
                            </div>
                        </form>
                        <a href="{{ route('admin.product') }}" class="btn btn-sm  reset_button"><i
                                class="ti ti-refresh"></i></a>

                    </div>
                </div>
            </div>
            <div class="row row-deck row-cards mt-2 product-index">
                <div class="col-12 m-0">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive order_table">
                            <div>
                                {{ $products->links('backEnd.admin.includes.paginate') }}
                            </div>
                            <table class="table table-vcenter card-table order-table">
                                <thead>
                                    <?php
                                    $flags = $item->extra_fields ?? [];
                                    $targetKeys = ['is_new_arrival', 'is_hot_sale', 'is_best_seller', 'is_trending', 'is_feature'];

                                    // check if any of these keys exist in array
                                    $hasRequiredKey = collect($targetKeys)->contains(function ($key) use ($flags) {
                                        return array_key_exists($key, $flags);
                                    });
                                    // dd($hasRequiredKey);
                                    ?>
                                    <tr class="custom-tr">
                                        <th>
                                            <input class="form-check-input m-0 align-middle" type="checkbox"
                                                aria-label="Select all invoices" id="selectAll">
                                        </th>
                                        <th>SL. </th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Category Name</th>
                                        {{-- <th>SKU</th> --}}
                                        <th>Stock</th>
                                        <th>Prices</th>
                                        <th>Product Compaign</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Free Shipping</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($products->count() > 0)
                                        @foreach ($products as $item)
                                            <tr class="custom-tr product-item{{ $item->id }}"
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td class="w-1">
                                                    <input class="form-check-input m-0 align-middle sub_chk" type="checkbox"
                                                        data-id="{{ $item->id }}">
                                                </td>
                                                <td class="w-1">{{ $i++ }}</td>
                                                <td>
                                                    @if ($item->get_thumb)
                                                        <img width="60"
                                                            src="{{ $item->get_thumb ? asset($item->get_thumb->file_url) : '' }}"
                                                            alt="">
                                                    @else
                                                        <img width="60" src="{{ asset('no_available.jpg') }}"
                                                            alt="">
                                                    @endif
                                                </td>
                                                <td class="text-truncate" style="max-width: 300px">
                                                    <a href="{{--{{ route('single.product', $item->slug) }}--}}"
                                                        style="font-size: 14px;color: #000;font-weight: 600;">
                                                        {{ $item->name }} ({{ $item->sku }}) </a> <br>
                                                    @if ($item->get_variants->count() > 0)
                                                        <strong style="font-size:12px">Variants:</strong> <br>
                                                        @foreach ($item->get_variants as $variant)
                                                            <?php
                                                            $variantInfo = $variant->items
                                                                ->map(function ($item) {
                                                                    $attrName = $item->attribute->name ?? '';
                                                                    $itemName = $item->name ?? '';
                                                                    return $attrName . ': ' . $itemName;
                                                                })
                                                                ->implode(', ');
                                                            ?>
                                                            <small>
                                                                {{-- <strong>{{ $variant->sku }}</strong> --}}
                                                                ({{ $variantInfo }})
                                                                - Stock: {{ $variant->stock }}
                                                            </small><br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td style="width:20%">
                                                    @foreach ($item->get_categories as $key => $cat)
                                                        {{ $key != 0 ? ', ' : '' }}{{ $cat->category_name }}
                                                    @endforeach
                                                </td>
                                                {{-- <td>{{ $item->sku }}</td> --}}
                                                <td>{{ $item->stock }}</td>
                                                {{-- <td>{{ $web_settings?->currency_sign }} {{ $item->price }}</td> --}}
                                                <td>

                                                    <strong>Regular: </strong>
                                                    {{ formatNumber($item->regular_price) }}
                                                    <br>
                                                    <strong>Sale: </strong>
                                                    {{ formatNumber($item->sale_price) }}
                                                    <br>
                                                    <strong>Purchase: </strong>
                                                    {{ formatNumber($item->purchase_price) }}
                                                    <br>


                                                </td>
                                                <td class="text-nowrap">
                                                    <?php
                                                    $flags = $item->extra_fields ?? [];
                                                    $targetKeys = ['is_new_arrival', 'is_hot_sale', 'is_best_seller', 'is_trending', 'is_feature'];
                                                    ?>

                                                    @foreach ($flags as $k => $val)
                                                        @if (in_array($k, $targetKeys))
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input flagSwitch" type="checkbox"
                                                                    data-id="{{ $item->id }}"
                                                                    data-key="{{ $k }}"
                                                                    {{ ($flags[$k] ?? null) == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label">
                                                                    {{ ucwords(str_replace('_', ' ', str_replace('is_', '', $k))) . '?' }}
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>



                                                <td class="w-1">
                                                    <?php
                                                    $flags = $item->extra_fields ?? [];
                                                    $targetKeys = ['position'];
                                                    ?>

                                                    @foreach ($flags as $k => $val)
                                                        @if (in_array($k, $targetKeys))
                                                            <input type="text"
                                                                class="form-control form-control-sm positionInput"
                                                                data-id="{{ $item->id }}"
                                                                data-key="{{ $k }}"
                                                                value="{{ $val }}">
                                                        @endif
                                                    @endforeach
                                                </td>


                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.product.status', $item->id) }}"
                                                            class="badge bg-success"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Published
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.product.status', $item->id) }}"
                                                            class="badge bg-danger"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Unpublished
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="checkbox" hidden="hidden" data-id="{{ $item->id }}"
                                                        id="username-{{ $item->id }}" class="free_shiiping"
                                                        {{ $item->free_shipping == 1 ? 'checked' : '' }}>
                                                    <label class="switch" for="username-{{ $item->id }}"></label>
                                                </td>
                                                <td class="w-1">
                                                    @can('products.edit')
                                                        <a href="{{ route('admin.product.edit', $item->id) }}"
                                                            class="btn-gradient-info border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </a>
                                                    @endcan
                                                    @can('products.delete')
                                                        <a href="{{ route('admin.product.delete', $item->id) }}"
                                                            onclick="return confirm('Are You Sure To Delete This?')"
                                                            class="btn-gradient-danger border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-trash"></i>
                                                            Delete
                                                        </a>
                                                    @endcan

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center text-danger font-weight-bold">No
                                                products Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div>
                                {{ $products->links('backEnd.admin.includes.paginate') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("search_form");
            const searchInput = document.getElementById("search");

            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', function() {
                    form.submit();
                });
            });

            // searchInput.addEventListener('keydown', function(e) {
            //     if (e.key === 'Enter') {
            //         e.preventDefault();
            //         form.submit();
            //     }
            // });
        });
    </script>
    <script>
        $(document).on('change', '.flagSwitch', function() {

            let id = $(this).data('id');
            let key = $(this).data('key');
            let value = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('product.updateFlag') }}",
                type: "POST",
                data: {
                    id: id,
                    key: key,
                    value: value,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: key.replace(/_/g, ' ').toUpperCase() + ' updated!',
                            showConfirmButton: false,
                            timer: 1200
                        });
                        //reload
                    }
                },
                error: function(xhr) {
                    console.log("Error:", xhr.responseText);
                }
            });

        });
    </script>
    <script>
        $(document).on('blur', '.positionInput', function() {
            let id = $(this).data('id');
            let position = $(this).val();
            let key = $(this).data('key');
            $.ajax({
                url: "{{ route('product.updatePosition') }}",
                method: "POST",
                data: {
                    id: id,
                    key: key,
                    position: position,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Position updated!',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to update!',
                        timer: 1500
                    });
                }
            });
        });


        // ENTER চাপলে blur trigger
        $(document).on('keypress', '.positionInput', function(e) {
            if (e.which === 13) {
                $(this).blur(); // triggers the update ajax
            }
        });
    </script>
    <script>
        $(document).on('change', '#selectAll', function() {
            // alert(4);
            if ($(this).is(':checked', true)) {
                // alert(4)
                $(".sub_chk").prop('checked', true);
            } else {
                // alert(5)
                $(".sub_chk").prop('checked', false);
            }
        });
        // bulk delete
        $(document).on('click', '#bulk_delete_btn', function(e) {
            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            if (allVals.length <= 0) {
                alert("Please select row.");
            } else {
                if (confirm("Are you sure you want to delete?")) {
                    $('.all_delete_id').val(allVals);
                    $('#all_delete_form').submit();
                }
            }
        });
    </script>
    <script>
        $(document).on('change', '.free_shiiping', function() {
            let productId = $(this).data('id');
            let isFree = $(this).is(':checked') ? 1 : 0;

            $.post("{{ route('admin.free.shipping') }}", {
                _token: "{{ csrf_token() }}",
                product_id: productId,
                free_shipping: isFree
            }, function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Free shipping updated!',
                        timer: 1200,
                        showConfirmButton: false
                    })
                }
            });
        });
    </script>
@endpush
