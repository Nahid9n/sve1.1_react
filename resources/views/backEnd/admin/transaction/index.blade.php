@extends('backEnd.admin.layouts.master')
@section('title')
    Account Transactions
@endsection
@php
    $setting = DB::table('web_settings')->select('currency_sign')->where('id', 1)->first();
    $trans = $data['transactions'];
@endphp
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 23px;
            border-radius: 3px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 15px !important;
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
            <div class="row d-flex align-items-center">
                <div class="col-6 d-flex align-items-center">
                    <h3 class="mb-0">
                        Transaction Report
                    </h3>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <form action="" method="GET" id="date_range_form" autocomplete="off">
                        <div class="col-md-12 col-12">
                            <div class="d-flex gap-1">
                                <select name="transaction_type" id="transaction_type"
                                    class="form-control form-select-sm cool-select">
                                    <option value="">Select Transaction Type</option>
                                    <option value="0"
                                        {{ request('transaction_type') != null && request('transaction_type') == 0 ? 'selected' : '' }}>
                                        Cash
                                        In
                                    </option>
                                    <option value="1" {{ request('transaction_type') == 1 ? 'selected' : '' }}>Cash
                                        Out
                                    </option>
                                </select>
                                <input type="text" name="date_range" id="date_range" autocomplete="off"
                                    class="form-control form-control-sm cool-select  date_range"
                                    placeholder="Select Date Range" value="{{ $daterange ?? null }}" style="height: 23px;">
                                <input type="hidden" name="start_date" class="start_date">
                                <input type="hidden" name="end_date" class="end_date">
                                {{-- <a href="{{ route('admin.account.transaction.index') }}"
                                    class="btn btn-primary ms-2 btn-sm">Reset</a> --}}

                                <a href="{{ route('admin.account.transaction.index') }}" class="btn btn-sm  reset_button"><i
                                        class="ti ti-refresh"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <!-- Page Header Close -->
                <div class="col-12 m-0">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Purpose</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($trans) > 0)
                                        @foreach ($trans as $key => $item)
                                            <tr>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>{{ $item->purpose ? $item->purpose : '---' }}</td>
                                                <td>{{ $setting->currency_sign }}{{ number_format($item->amount, 2) }}
                                                </td>
                                                <td>
                                                    @if ($item->transaction_type == 0)
                                                        <span class="badge bg-success">Cash In</span>
                                                    @else
                                                        <span class="badge bg-danger">Cash Out</span>
                                                    @endif
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
                        <div class="col-12">
                            <div class="w-100 float-end p-1">
                                {{ $trans->links('backEnd.admin.includes.paginate') }}
                            </div>
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
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#transaction_type').change(function() {
                $('#date_range_form').submit();
            });
        });
    </script>
@endpush
