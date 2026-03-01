@extends('backEnd.admin.layouts.master')

@section('title')
    Combo Products
@endsection
@section('content')

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>
                        Combo Products
                        <small class="float-end">
                            Total Products: {{ $products->total() }}
                        </small>
                    </h3>

                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    @can('products.combo.create')
                        <a href="{{ route('admin.combo-products.create') }}" class="btn btn-success btn-sm">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Combo Product</a>
                    @endcan

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
                                    <tr class="custom-tr">
                                        <th>SL.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category Name</th>
                                        <th>Items</th>
                                        <th>Stock</th>
                                        <th>Prices</th>
                                        <th>Status</th>
                                        {{-- <th>Free Shipping</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($products->count() > 0)
                                        @foreach ($products as $item)
                                            <tr class="custom-tr"
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <img width="60"
                                                        src="{{ $item->get_thumb ? asset($item->get_thumb->file_url) : asset('frontEnd/images/no-image.jpg') }}"
                                                        alt="">
                                                </td>
                                                <td class="text-truncate" style="max-width: 300px">
                                                    <a href="{{ route('single.product', $item->slug) }}"
                                                        style="font-size: 14px;color: #000;font-weight: 600;">
                                                        {{ $item->name }} ({{ $item->sku }}) </a> <br>

                                                </td>
                                                <td style="width:20%">
                                                    @foreach ($item->get_categories as $key => $cat)
                                                        {{ $key != 0 ? ', ' : '' }}
                                                        {{ $cat->category_name }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{-- @dd($item->comboItems) --}}
                                                    @foreach ($item->comboItems as $key => $product)
                                                        @if ($loop->first)
                                                            {{ $product->product->name }}
                                                            ({{ $product->quantity ?? 1 }})
                                                        @else
                                                            <br>{{ $product->product->name }}
                                                            ({{ $product->quantity ?? 1 }})
                                                        @endif
                                                    @endforeach
                                                </td>
                                                {{-- <td>{{ $item->sku }}</td> --}}
                                                <td>{{ $item->stock }}</td>
                                                {{-- <td>{{ $web_settings?->currency_sign }} {{ $item->price }}</td> --}}
                                                <td>

                                                    <strong>Regular Price: </strong>{{ $web_settings?->currency_sign }}
                                                    {{ formatNumber($item->regular_price) }}
                                                    <br>
                                                    <strong>Sale Price: </strong>{{ $web_settings?->currency_sign }}
                                                    {{ formatNumber($item->sale_price) }}
                                                    <br>
                                                    <strong>Purchase Price: </strong>{{ $web_settings?->currency_sign }}
                                                    {{ formatNumber($item->purchase_price) }}
                                                    <br>


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
                                                {{-- <td>
                                                    <input type="checkbox" hidden="hidden" data-id="{{ $item->id }}"
                                                        id="username-{{ $item->id }}" class="free_shiiping"
                                                        {{ $item->free_shipping == 1 ? 'checked' : '' }}>
                                                    <label class="switch" for="username-{{ $item->id }}"></label>
                                                </td> --}}
                                                <td class="w-1">
                                                    @can('products.combo.edit')
                                                        <a href="{{ route('admin.combo-products.edit', $item->id) }}"
                                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit-btn">
                                                            <i class="ti ti-edit"></i>
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can('products.combo.delete')
                                                        <a href="{{ route('admin.product.delete', $item->id) }}"
                                                            onclick="return confirm('Are You Sure To Delete This?')"
                                                            class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
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

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    form.submit();
                }
            });
        });
    </script>
@endpush
