@extends('backEnd.admin.layouts.master')

@section('title')
    {{ $status }} Orders
@endsection
@push('css')
    <style>
        @media (max-width: 576px) {
            .form-inline .form-control {
                display: inline-block;
                width: auto;
                vertical-align: middle;
            }
        }
    </style>
@endpush
@php
    $orders = $data['orders'] ?? 0;
    $total_order = $data['total_order'] ?? 0;
    $total_pending_order = $data['total_pending_order'] ?? 0;
    $total_confirm_order = $data['total_confirm_order'] ?? 0;
    $total_processing_order = $data['total_processing_order'] ?? 0;
    $total_hold_order = $data['total_hold_order'] ?? 0;
    $total_printed_order = $data['total_printed_order'] ?? 0;
    $total_packaging_order = $data['total_packaging_order'] ?? 0;
    $total_on_delivery_order = $data['total_on_delivery_order'] ?? 0;
    $total_delivered_order = $data['total_delivered_order'] ?? 0;
    $total_cancelled_order = $data['total_cancelled_order'] ?? 0;
    $total_returned_order = $data['total_returned_order'] ?? 0;

    $couriers = \Illuminate\Support\Facades\DB::table('couriers')->where('status', 1)->pluck('courier_name', 'id');
    $query = request()->query('query') ?? null;
    $courier_id = $courier_id ?? null;
@endphp
@section('content')
    {{-- @dd($status) --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards mb-3">

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders') }}">
                        <div class="card card-sm {{ $status == '' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_order }}
                                        </div>
                                        <div class="text-dark fw-bold  fs-5">
                                            Total Orders
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.pending') }}">
                        <div class="card card-sm {{ $status == 'Pending' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_pending_order }}
                                        </div>
                                        <div class="text-warning fw-bold  fs-5">
                                            Total Pending
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.confirm') }}">
                        <div class="card card-sm {{ $status == 'Confirm' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_confirm_order }}
                                        </div>
                                        <div class="text-success fw-bold  fs-5">
                                            Total Confirm
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.processing') }}">
                        <div class="card card-sm {{ $status == 'Processing' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_processing_order }}
                                        </div>
                                        <div class="text-info fw-bold  fs-5">
                                            Total Processing
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.hold') }}">
                        <div class="card card-sm {{ $status == 'Hold' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_hold_order }}
                                        </div>
                                        <div class="text-secondary fw-bold  fs-5">
                                            Total Hold
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.printed') }}">
                        <div class="card card-sm {{ $status == 'Printed' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_printed_order }}
                                        </div>
                                        <div class="text-danger fw-bold fs-5">
                                            Total Printed
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.packaging') }}">
                        <div class="card card-sm {{ $status == 'Packaging' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_packaging_order }}
                                        </div>
                                        <div class="text-primary fw-bold  fs-5">
                                            Total Packaging
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.on.delivery') }}">
                        <div class="card card-sm {{ $status == 'On Delivery' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_on_delivery_order }}
                                        </div>
                                        <div class="text-lime fw-bold  fs-5">
                                            Total On Delivery
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.delivered') }}">
                        <div class="card card-sm {{ $status == 'Delivered' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_delivered_order }}
                                        </div>
                                        <div class="text-teal fw-bold  fs-5">
                                            Total Delivered
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.cancelled') }}">
                        <div class="card card-sm {{ $status == 'Cancelled' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_cancelled_order }}
                                        </div>
                                        <div class="text-danger fw-bold  fs-5">
                                            Total Cancelled
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md col-4">
                    <a href="{{ route('admin.orders.status.returned') }}">
                        <div class="card card-sm {{ $status == 'Returned' ? 'border border-danger border-2' : '' }}">
                            <div class="card-body py-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="fw-bolder fs-2">
                                            {{ $total_returned_order }}
                                        </div>
                                        <div class="text-lime fw-bold  fs-5">
                                            Total Returned
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <div class="row row-deck row-cards mt-3">
                <div class="page-header d-print-none">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                <div class="action-list">
                                    <div class="buttons">
                                        <div class="col-md-2 col-12">
                                            <a href="{{ route('admin.orders.create') }}"
                                                class="btn btn-success d-block me-2 btn-sm">Add
                                                Order</a>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <form action="{{ route('admin.orders.all.status') }}" method="post"
                                                id="all_status_form" class="me-2">
                                                @csrf
                                                <input type="hidden" id="all_status" name="all_status ">
                                                <select name="status" id="status"
                                                    class="form-select form-select-sm d-block me-2">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Pending </option>
                                                    <option value="2">Confirm</option>
                                                    <option value="3">Processing</option>
                                                    <option value="4">Hold</option>
                                                    <option value="5">Printed</option>
                                                    <option value="6">Packaging</option>
                                                    <option value="7">On Delivery</option>
                                                    <option value="8">Delivered</option>
                                                    <option value="9">Cancelled</option>
                                                    <option value="10">Returned</option>
                                                </select>
                                            </form>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <form action="{{ route('admin.orders.bulk.print') }}" method="post"
                                                id="all_print_form">
                                                @csrf
                                                <button type="button" id="bulk_print_btn"
                                                    class="btn btn-info d-block btn-sm">Print Invoice
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <form action="{{ route('admin.orders.courier_csv') }}" method="post"
                                                id="all_courier_csv" class="ms-2">
                                                @csrf
                                                <input type="hidden" name="status" id="courier_status">
                                                <input type="hidden" id="all_ord_id" name="all_ord_id">
                                                <button type="button" id="steadfast_csv"
                                                    class="btn btn-success d-block me-2 btn-sm">Stead Fast
                                                    Export
                                                </button>
                                                <button type="button" id="redex_csv"
                                                    class="btn btn-danger d-block btn-sm">Redex Export
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                    <div class="search">
                                        <form action="{{ route('admin.orders.p') }}"
                                            class="d-flex flex-md-row flex-column align-items-end justify-content-end">
                                            <input type="hidden" name="status" value="{{ $status ?? null }}">
                                            <select name="courier_id" id="courier_id"
                                                class="form-control form-select-sm me-2">
                                                <option value="">Select Courier</option>
                                                @foreach ($couriers as $key => $item)
                                                    <option value="{{ $key }}"
                                                        {{ $courier_id == $key ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control form-control-sm small-search me-2"
                                                placeholder="Search..." value="{{ $query }}" name="query">
                                            <button type="submit" class="btn btn-info btn-sm me-2">Search</button>
                                            <a href="{{ route('admin.orders.p') }}"
                                                class="btn btn-sm btn-dark reset_button"><i class="ti ti-refresh"></i></a>
                                        </form>

                                    </div>
                                </div>
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- Page Header Close -->
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive order_table">
                            <table class="table table-vcenter card-table order-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">
                                            <input class="form-check-input m-0 align-middle" type="checkbox"
                                                aria-label="Select all invoices" id="master">
                                        </th>
                                        <th>SL.</th>
                                        <th>Invoice ID</th>
                                        <th>Customer Info</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Courier</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th>Assigned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($orders->count() > 0)
                                        @foreach ($orders as $item)
                                            <tr id="tr_{{ $item->id }}">
                                                <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                                        class="sub_chk" data-id="{{ $item->id }}">
                                                </td>
                                                <td class="w-1">{{ $i++ }}</td>
                                                <td>
                                                    {{ date('d M, Y', strtotime($item->order_date)) }}<br>
                                                    {{ date('h:i:s A', strtotime($item->created_at)) }}
                                                </td>
                                                <td>{{ $item->invoice_id }}</td>
                                                <td>
                                                    <span class="mb-1 d-block"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-user" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                        </svg> {{ $item->customer_name }}</span>
                                                    <span class="mb-1 d-block">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-phone" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path
                                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                                            </path>
                                                        </svg>
                                                        <a
                                                            href="tel:{{ $item->customer_phone }}"><span>{{ $item->customer_phone }}</span></a>
                                                    </span>
                                                    <span class="mb-1 d-block"><svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-map-pin" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                                            <path
                                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                            </path>
                                                        </svg> {{ $item->customer_address }}</span>
                                                </td>
                                                <td>
                                                    @foreach ($item->get_products as $product)
                                                        {{ $product->qty }} x {{ $product->get_product->name }} <br>
                                                        @if ($product->attributes)
                                                            @foreach (json_decode($product->attributes, true) as $key => $attr)
                                                                <span class="text-primary">{{ $key }} -
                                                                    {{ $attr }}</span>
                                                                <br>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $web_settings?->currency_sign }} {{ $item->total }}</td>
                                                <td>{{ $item->get_courier->courier_name ?? 'Not Selected' }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $item->status == 1 ? 'bg-warning' : '' }} {{ $item->status == 2 ? 'bg-success' : '' }}{{ $item->status == 3 ? 'bg-info' : '' }}{{ $item->status == 4 ? 'bg-secondary' : '' }}{{ $item->status == 5 ? 'bg-danger' : '' }}{{ $item->status == 6 ? 'bg-primary' : '' }}{{ $item->status == 7 ? 'bg-lime' : '' }}{{ $item->status == 8 ? 'bg-teal' : '' }}{{ $item->status == 9 ? 'bg-danger' : '' }}{{ $item->status == 10 ? 'bg-danger' : '' }} status_btn  btn-sm dropdown dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        @if ($item->status == 1)
                                                            Pending
                                                        @endif
                                                        @if ($item->status == 2)
                                                            Confirm
                                                        @endif
                                                        @if ($item->status == 3)
                                                            Processing
                                                        @endif
                                                        @if ($item->status == 4)
                                                            Hold
                                                        @endif
                                                        @if ($item->status == 5)
                                                            Printed
                                                        @endif
                                                        @if ($item->status == 6)
                                                            Packaging
                                                        @endif
                                                        @if ($item->status == 7)
                                                            On Delivery
                                                        @endif
                                                        @if ($item->status == 8)
                                                            Delivered
                                                        @endif
                                                        @if ($item->status == 9)
                                                            Cancelled
                                                        @endif
                                                        @if ($item->status == 10)
                                                            Returned
                                                        @endif


                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item {{ $item->status == 1 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 1]) }}">Pending
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 2 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 2]) }}">Confirm
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 3 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 3]) }}">Processing
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 4 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 4]) }}">Hold
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 5 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 5]) }}">Printed
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 6 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 6]) }}">Packaging
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 7 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 7]) }}">On
                                                            Delivery
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 8 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 8]) }}">Delivered
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 9 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 9]) }}">Cancelled
                                                        </a>
                                                        <a class="dropdown-item {{ $item->status == 10 ? 'd-none' : '' }}"
                                                            href="{{ route('admin.orders.status', [$item->id, 10]) }}">Returned
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>{{ $item->order_note }}</td>
                                                <td>{{ $item->get_assigned ? $item->get_assigned->get_employee->name : '' }}
                                                </td>

                                                <td class="w-1">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-outline-warning btn-sm w-100 mb-1 print"
                                                        data-id="{{ $item->id }}">Print</a>
                                                    @if (Auth::guard('admin')->check())
                                                        <a href="{{ route('admin.orders.edit', $item->id) }}"
                                                            class="btn btn btn-cyan border-0 btn-sm d-flex justify-content-center gap-1 w-100 mb-1">
                                                            Edit
                                                        </a>
                                                        <a href="{{ route('admin.orders.delete', $item->id) }}"
                                                            class="btn btn-danger border-0 btn-sm w-100 d-flex justify-content-center gap-1"
                                                            onclick="return confirm('Are you sure to delete this?')">Delete</a>
                                                    @endif

                                                    @if (Auth::guard('manager')->check())
                                                        <a href="{{ route('manager.orders.edit', $item->id) }}"
                                                            class="btn btn btn-cyan border-0 btn-sm d-flex justify-content-center gap-1 w-100 mb-1">
                                                            Edit
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center text-danger font-weight-bold">No
                                                Data Found!
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <div class="w-100 float-end p-1">
                                {{ $orders->links('backEnd.admin.includes.paginate') }}
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
        $('.print').on('click', function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('admin.orders.print') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    id: $(this).data('id')
                },
                success: function(data) {
                    newWin = window.open("");
                    newWin.document.write(data);
                    newWin.document.close();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {


            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('#status').on('change', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('#all_status').val(allVals);
                    $('#all_status_form').submit();
                }
            });

            $('#bulk_print_btn').on('click', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{ route('admin.orders.bulk.print') }}',
                        type: 'POST',
                        data: {
                            _token: @json(csrf_token()),
                            all_inv_id: allVals
                        },
                        success: function(data) {
                            newWin = window.open("");
                            newWin.document.write(data);
                            newWin.document.close();
                        }
                    });
                }
            });

            //courier export
            $('#steadfast_csv').on('click', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('#all_ord_id').val(allVals);
                    $('#courier_status').val(1);
                    $('#all_courier_csv').submit();
                }
            });

            $('#redex_csv').on('click', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('#all_ord_id').val(allVals);
                    $('#courier_status').val(2);
                    $('#all_courier_csv').submit();
                }
            });
        });
    </script>
@endpush
