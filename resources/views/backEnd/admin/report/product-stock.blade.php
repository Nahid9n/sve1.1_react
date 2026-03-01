@extends('backEnd.admin.layouts.master')

@section('title')
    Product Stock Reports
@endsection
@php
    $setting = DB::table('web_settings')->first();
@endphp

@push('css')
    <style>
        .select2-container--default .select2-selection--single {
            height: 23px;
            border-radius: 3px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 18px !important;
            font-size: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 13px;
        }

        .select2-container--default .select2-selection--single {
            padding: .1rem 2.25rem .1rem .75rem;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <h3>
                    Product Stock Reports
                </h3>
            </div>
            <div class="row excel-export d-flex justify-content-between">
                <div class="col-md-10 col-12 mb-1">
                    <form action="" method="GET">
                        <input type="hidden" name="export" value="1">
                        <input type="hidden" class="product_id " name="product_id" value="{{ $product_id ?? null }}">
                        <button type="submit" class="btn btn-success btn-sm pdf-button"><svg
                                xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>CSV Export</button>
                    </form>
                </div>
                <div class="col-md-2 col-12 ">
                    <form class="d-flex justify-content-end" action="{{ route('admin.report.product.stock') }}"
                        method="GET" id="search_form">

                        <select class="form-control select2 cool-select" name="product_id" id="product_id_select">
                            <option value="">All Products</option>
                            @foreach ($product_list as $key => $product)
                                <option value="{{ $key }}"
                                    {{ request()->query('product_id') == $key ? 'selected' : '' }}>
                                    {{ $product }}
                                </option>
                            @endforeach
                        </select>

                        <a href="{{ route('admin.report.product.stock') }}" class="btn ms-1 btn-sm reset_button">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </form>

                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive">
                            <div>
                                {{ $productRange->links('backEnd.admin.includes.paginate') }}
                            </div>
                            <table class="table datatable table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th>Purchase Amount</th>
                                        <th>Selling Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    <?php
                                    $total_stock = 0;
                                    $total_purchase_price = 0;
                                    $total_selling_price = 0;
                                    
                                    ?>
                                    @foreach ($productRange as $item)
                                        <?php
                                        $total_stock += $item->stock;
                                        $purchase_price = $item->stock * $item->purchase_price;
                                        $total_purchase_price = $purchase_price + $total_purchase_price;
                                        $selling_price = $item->stock * ($item->sale_price > 0 ? $item->sale_price : $item->price);
                                        $total_selling_price = $total_selling_price + $selling_price;
                                        
                                        ?>
                                        <tr class=" {{ $item->stock <= $setting->stock_alert ? 'bg-danger-light' : '' }}">
                                            <td width="1%">{{ $i++ }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->stock }}</td>
                                            <td>
                                                {{ $setting->currency_sign }} {{ $purchase_price }}

                                            </td>
                                            <td>
                                                {{ $setting->currency_sign }} {{ $selling_price }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr style="border-bottom: none">

                                        <th colspan="2" style="text-align: end">Total</th>
                                        <th>{{ $total_stock }}</th>
                                        <th> {{ $setting->currency_sign }} {{ $total_purchase_price }} </th>
                                        <th>{{ $setting->currency_sign }} {{ $total_selling_price }} </th>
                                    </tr>
                                </tbody>

                            </table>
                            <div>
                                {{ $productRange->links('backEnd.admin.includes.paginate') }}
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
        $(document).ready(function() {
            $('.select2').select2({
                width: 'resolve'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#product_id_select').on('change', function() {
                $('#search_form').submit();
            });
        })
    </script>
@endpush
