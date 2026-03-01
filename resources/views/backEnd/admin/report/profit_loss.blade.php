@extends('backEnd.admin.layouts.master')

@section('title')
    Profit/Loss
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
    <link rel="stylesheet" type="text/css" href="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <h3>
                    Profit/Loss Report
                </h3>
            </div>
            <div class="row excel-export d-flex justify-content-between">
                <div class="col-md-6 col-12 mb-1">
                    <form action="" method="GET">
                        <input type="hidden" name="export" value="1">
                        <input type="hidden" class="start_date" name="start_date" value="{{ $start ?? null }}">
                        <input type="hidden" class="end_date" name="end_date" value="{{ $end ?? null }}">
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
                <div class="col-md-4 col-12 ">
                    {{-- <form class="d-flex justify-content-end" action="#" method="GET" id="date_range_form">
                        <input type="hidden" class="start_date" name="start_date">
                        <input type="hidden" class="end_date" name="end_date">
                        <input type="text" class="form-control form-control-sm small-search date_range me-2"
                            placeholder="Date Range..." autocomplete="off" value="{{ $daterange ?? null }}"
                            name="date_range">
                        <a href="{{ route('admin.report.profit.loss') }}" class="btn btn-sm  reset_button"><i
                                class="ti ti-refresh"></i></a>
                    </form> --}}
                    <form id="date_range_picker_form" method="GET" action="{{ url()->current() }}"
                        class="d-flex justify-content-end">
                        <input type="text" id="date_range_display" placeholder="Select date range"
                            class="form-control small-search date_range me-2" autocomplete="off">
                        <input type="hidden" name="date_range" id="date_range">
                        <button type="submit" class="date_filter_button btn btn-sm btn-primary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-search m-0">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                        <a href="{{ route('admin.report.profit.loss') }}" class="btn btn-sm"><i
                                class="ti ti-refresh"></i></a>
                    </form>
                </div>
            </div>


            <div class="row my-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table card-table table-center datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>Total Sell</th>
                                    <th>Total Costs </th>
                                    <th>Total Expenses </th>
                                    <th>Gross Profit/Loss</th>
                                    <th>Net Profit/Loss</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $setting->currency_sign }}{{ number_format($totalSell, 2) }}</td>
                                    <td>{{ $setting->currency_sign }}{{ number_format($totalPurchaseCost, 2) }}</td>
                                    <td>{{ $setting->currency_sign }}{{ number_format($totalExpenseCost, 2) }}</td>
                                    <td>{{ $setting->currency_sign }}{{ $totalSell - $totalPurchaseCost }}</td>
                                    <td>
                                        @if ($totalSell - ($totalPurchaseCost + $totalExpenseCost) > 0)
                                            <span class="text-success">
                                                {{ $setting->currency_sign }}{{ $totalSell - ($totalPurchaseCost + $totalExpenseCost) }}</span>
                                        @else
                                            <span class="text-danger">
                                                {{ $setting->currency_sign }}{{ $totalSell - ($totalPurchaseCost + $totalExpenseCost) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/plugin/datepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: 'resolve'
            });
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            $('.date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false
            });
            $('.date_range').on('apply.daterangepicker', function(ev, picker) {
                $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));

            });
            $('.date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.date_range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Search'
                }
            });

            $('.date_range').on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                //     'DD/MM/YYYY'));
                // $('#range').val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                //     'DD/MM/YYYY'));
                $('.start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('.end_date').val(picker.endDate.format('YYYY-MM-DD'));

                $('#date_range_form').submit();
            });

            $('.date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#range').val('');
            });
        })
    </script> --}}
    <script>
        $(document).ready(function() {
            var start = moment("{{ $start }}");
            var end = moment("{{ $end }}");

            function updateDisplay(start, end) {
                $('#date_range_display').val(start.format('MMM D, YYYY') + ' - ' + end.format(
                    'MMM D, YYYY'));
                $('#date_range').val(JSON.stringify({
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                }));
            }

            $('#date_range_display').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: false,
                opens: 'left',
                locale: {
                    format: 'MMM D, YYYY'
                },
                alwaysShowCalendars: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, updateDisplay);

            // Input click → calendar open
            $('#date_range_display').on('click', function() {
                $(this).data('daterangepicker').show();
            });

            // Initialize input display
            updateDisplay(start, end);
        });
    </script>
@endpush
