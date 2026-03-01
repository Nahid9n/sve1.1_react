@extends('backEnd.admin.layouts.master')

@section('title')
    Product Stock Reports
@endsection
@php
    $setting = DB::table('web_settings')->first();
    // $products = $data['products'];
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
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <h3>
                    Product Status Report
                </h3>
            </div>
            <div class="row excel-export d-flex justify-content-between">
                <div class="col-md-6 col-12 mb-1">
                    <form action="" method="GET">
                        <input type="hidden" name="export" value="1">
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
                {{-- <div class="col-md-6 col-12 ">
                    <form class="d-flex justify-content-end" action="#" method="GET" id="date_range_form">
                        <div class="form-group d-flex me-1">
                            <input type="text" name="date_range" id="date_range" autocomplete="off"
                                class="form-control form-control-sm  date_range" placeholder="Select Date Range"
                                value="{{ $daterange ?? null }}" style="height: 28px;">
                            <input type="hidden" name="start_date" id="start_date">
                            <input type="hidden" name="end_date" id="end_date">
                        </div>
                        <div class=" form-group d-flex gap-1">
                            <a href="{{ route('admin.report.product') }}" class="btn btn-sm btn-dark">Reset</a>
                        </div>
                    </form>
                </div> --}}
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Name</th>
                                    <th>Pending </th>
                                    <th>Confirmed</th>
                                    <th>Processing</th>
                                    <th>Hold</th>
                                    <th>Printed</th>
                                    <th>Packaging</th>
                                    <th>On Delivery</th>
                                    <th>Delivered</th>
                                    <th>Cancelled</th>
                                    <th>Returned</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)

                                @foreach ($productReports as $key => $item)
                                    {{-- @dd($item) --}}
                                    <tr @if($i % 2 == 0)  style="background-color:#f5f5f5" @endif>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $key }}</td>
                                        <td>{{ $item['pending'] }}</td>
                                        <td>{{ $item['confirmed'] }}</td>
                                        <td>{{ $item['processing'] }}</td>
                                        <td>{{ $item['hold'] }}</td>
                                        <td>{{ $item['printed'] }}</td>
                                        <td>{{ $item['packaging'] }}</td>
                                        <td>{{ $item['on_delivery'] }}</td>
                                        <td>{{ $item['delivered'] }}</td>
                                        <td>{{ $item['cancelled'] }}</td>
                                        <td>{{ $item['returned'] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
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
@endpush
