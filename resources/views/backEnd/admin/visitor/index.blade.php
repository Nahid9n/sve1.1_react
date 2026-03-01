@extends('backEnd.admin.layouts.master')

@section('title')
    Visitor
@endsection
@php
    $uniqueVisitors = $uniqueVisitors ?? 0;
    $totalVisitors = $totalVisitors ?? 0;
    $activeVisitors = $activeVisitors ?? 0;
@endphp
@push('css')
    <style>
        .fitter-list:hover {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row d-flex mb-2">
                <div class="col-6">
                    <h3 class="m-0">All Visitors</h3>
                </div>
                {{-- <div class="col-6 d-flex justify-content-end">
                    <a href="{{ route('admin.orders') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-arrow-left"></i>
                        Back
                    </a>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="filter">
                                <form class="d-flex justify-content-start" action="#" method="GET"
                                    id="date_range_form">
                                    <input type="hidden" class="start_date" name="start_date">
                                    <input type="hidden" class="end_date" name="end_date">
                                    <input type="text" class="form-control form-control-sm small-search date_range me-2"
                                        placeholder="Date Range..." autocomplete="off" value="{{ $dateRange ?? null }}"
                                        name="date_range" style="width: 200px;">
                                    <a href="{{ route('admin.visitor.index') }}" class="btn btn-sm reset_button"><i
                                            class="ti ti-refresh"></i></a>
                                </form>

                            </div>
                            <div class="current-month mt-3">
                                <div class="row">
                                    <div class="col-md-2 mb-2">
                                        <form action="{{ route('admin.visitor.filter') }}">
                                            <input type="hidden" class="date_range cool-input"
                                                value="{{ $dateRange ?? null }}" name="date_range">
                                            <input type="hidden" value="unique" name="status">
                                            <div class="card fitter-list">
                                                <div class="card-body">
                                                    <h2>{{ $uniqueVisitors }}</h2>
                                                    <h5>Unique Visitors</h5>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <form action="{{ route('admin.visitor.filter') }}">
                                            <input type="hidden" class="date_range" value="{{ $dateRange ?? null }}"
                                                name="date_range">
                                            <input type="hidden" value="total" name="status">
                                            <div class="card fitter-list">
                                                <div class="card-body">
                                                    <h2>{{ $totalVisitors }}</h2>
                                                    <h5>No of Visitors</h5>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <form action="{{ route('admin.visitor.filter') }}">
                                            <input type="hidden" class="date_range" value="{{ $dateRange ?? null }}"
                                                name="date_range">
                                            <input type="hidden" value="online" name="status">
                                            <div class="card fitter-list">
                                                <div class="card-body">
                                                    <h2>{{ $activeVisitors }}</h2>
                                                    <h5>Active Visitors</h5>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Weekly Visitors</h3>
                            <div id="weekly_visitors" class="chart-lg" style="min-height: 240px;">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body" style="height: 326px; overflow-y: scroll;">
                            <h3 class="card-title">Most Visited Page</h3>
                            <div class="table-responsive" style="width:100%">
                                <table class="table card-table table-vcenter text-nowrap datatable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">SL.</th>
                                            <th width="89%">URL</th>
                                            <th width="5%">Unique Visitors</th>
                                            <th width="5%">No. of Visits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($top_urls) > 0)
                                            @foreach ($top_urls as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td><a href="{{ $item->url }}"
                                                            target="_blank">{{ $item->url }}</a>
                                                    </td>
                                                    <td>
                                                        {{ $item->total_ip }}
                                                    </td>
                                                    <td>
                                                        {{ $item->count }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center"> <span class="text-danger"><b>No data
                                                            found.</b></span></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="w-100 float-end p-1">
                                {{ $top_urls->links('backEnd.admin.includes.paginate') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Visit Duration</h3>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Visit Duration</th>
                                            <th>No. of Visits</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <b>0s - 30s</b>
                                            </td>
                                            <td>
                                                {{-- @dd($visits_duration->isNotEmpty()) --}}
                                                {{ $visits_duration->isNotEmpty() ? number_format($visits_duration->whereBetween('duration', [0, 30])->count(), 0) : 0 }}
                                            </td>
                                            <td>{{ $visits_duration->isNotEmpty() ? number_format(($visits_duration->whereBetween('duration', [0, 30])->count() * 100) / $visits_duration->count(), 2) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <b>31s - 2mn</b>
                                            </td>
                                            <td>
                                                {{ $visits_duration->isNotEmpty() ? number_format($visits_duration->whereBetween('duration', [31, 120])->count(), 0) : 0 }}
                                            </td>
                                            <td>{{ $visits_duration->isNotEmpty() ? number_format(($visits_duration->whereBetween('duration', [31, 120])->count() * 100) / $visits_duration->count(), 2) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <b>2mn - 5mn</b>
                                            </td>
                                            <td>
                                                {{ $visits_duration->isNotEmpty() ? number_format($visits_duration->whereBetween('duration', [121, 300])->count(), 0) : 0 }}
                                            </td>
                                            <td>{{ $visits_duration->isNotEmpty() ? number_format(($visits_duration->whereBetween('duration', [121, 300])->count() * 100) / $visits_duration->count(), 2) : 0 }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Devices</h3>
                            <div id="device_chart" class="chart-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/plugin/datepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('backEnd/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
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
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
                $('#range').val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
                $('.start_date').val(picker.startDate.format('YYYY-MM-DD'));
                $('.end_date').val(picker.endDate.format('YYYY-MM-DD'));

                $('#date_range_form').submit();
            });

            $('.date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#range').val('');
            });

            $('.fitter-list').on('click', function() {
                // alert(4)
                $(this).parent('form').submit()
            });
        })
    </script>
    <script>
        const weekly_data = @json($weekly_data);


        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('weekly_visitors'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 300,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false
                    },
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: "Unique visitors",
                    data: weekly_data.weekly_unique_visitors
                }, {
                    name: "No. of visits",
                    data: weekly_data.weekly_visits
                }],
                grid: {
                    padding: {
                        top: -20,
                        right: 0,
                        left: -4,
                        bottom: -4
                    },
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'day',
                    categories: weekly_data.days,
                    title: {
                        text: 'Day',
                    },
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: weekly_data.days,
                colors: ["#206bc4", "#79a6dc", "#bfe399"],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>
    <script>
        const devices = @json($devices);
        const device_name = [];
        const total = [];
        $.each(devices, function(index, value) {
            device_name.push(index);
            total.push(value)
        });

        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('device_chart'), {
                chart: {
                    type: "pie",
                    fontFamily: 'inherit',
                    height: 300,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        enabled: false
                    },
                    stacked: true,
                },
                fill: {
                    opacity: 1,
                },
                dataLabels: {
                    formatter(val, opts) {
                        const name = opts.w.globals.labels[opts.seriesIndex]
                        return [name, val.toFixed(1) + '%']
                    },
                },
                series: total,
                labels: device_name,
                colors: ["#206bc4", "#79a6dc", "#bfe399"],
                legend: {
                    show: false,
                },
            })).render();
        });
    </script>
@endpush
