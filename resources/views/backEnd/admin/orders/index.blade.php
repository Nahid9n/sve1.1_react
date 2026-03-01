@extends('backEnd.admin.layouts.master')

@section('title')
    @if (!$sts)
        All Orders
    @else
        {{ Str::ucfirst(str_replace('_', ' ', $sts)) }} Orders
    @endif
@endsection
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.css') }}" />
    <style>
        .telephone {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #06b6d4;
            color: white;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(6, 182, 212, 0.2);
            transition: box-shadow .2s ease;
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
    $total_courier_entry_order = $data['total_courier_entry_order'] ?? 0;
    $total_on_delivery_order = $data['total_on_delivery_order'] ?? 0;
    $total_delivered_order = $data['total_delivered_order'] ?? 0;
    $total_cancelled_order = $data['total_cancelled_order'] ?? 0;
    $total_returned_order = $data['total_returned_order'] ?? 0;
    $total_trash_order = $data['total_trash_order'] ?? 0;

    $couriers = \Illuminate\Support\Facades\DB::table('couriers')->where('status', 1)->pluck('courier_name', 'id');
    $query = request()->query('query') ?? null;
    $source = $source ?? null;
    $payment_status = $payment_status ?? null;
    $courier_id = $courier_id ?? null;
    $status = $sts ?? null;
@endphp
{{-- @dd($sts) --}}
@section('content')

    <div class="page-body mt-0">
        <div class="container-xl">
            <div class="order-header d-flex justify-content-between align-items-start flex-wrap gap-2" style="">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 me-2">
                        @if (!$sts)
                            All Orders
                        @else
                            <span class="text-capitalize text-success">
                                {{ Str::ucfirst(str_replace('_', ' ', $sts)) }} Orders
                            </span>
                        @endif
                    </h2>

                    <a href="{{ route('admin.orders.trash') }}" class="btn btn-dark btn-sm d-flex align-items-center gap-1"
                        title="Trash">
                        <i class="ti ti-trash"></i>
                        Trash
                        <span class="badge bg-danger ms-1">{{ $data['total_trash_order'] ?? 0 }}</span>
                    </a>
                </div>
                <div class="d-flex align-items-center  premium-form">
                    <form action="{{ route('admin.orders') }}" class="d-flex align-items-start gap-2" id="search_form">

                        <input type="hidden" name="status" value="{{ $status ?? null }}">

                        <div>
                            <input type="text" placeholder="Select date range" id="date_range_display"
                                class="form-control search-input  form-control-sm date_range date_range_display"
                                autocomplete="off">
                            <input type="hidden" name="date_range" id="date_range">
                        </div>

                        <select name="source" id="source" class="form-select form-select-sm cool-select">
                            <option value="">Source</option>
                            <option value="direct" {{ $source == 'direct' ? 'selected' : '' }}>Direct</option>
                            <option value="page" {{ $source == 'page' ? 'selected' : '' }}>Page</option>
                            <option value="whatsapp" {{ $source == 'whatsapp' ? 'selected' : '' }}>Whatsapp</option>
                            <option value="ab_cart" {{ $source == 'ab_cart' ? 'selected' : '' }}>AB Cart</option>
                            <option value="call" {{ $source == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="office_sell" {{ $source == 'office_sell' ? 'selected' : '' }}>Office Sell</option>
                            <option value="instagram" {{ $source == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="lp" {{ $source == 'lp' ? 'selected' : '' }}>Landing Page</option>
                        </select>

                        <select name="payment_status" id="payment_status" class="form-select form-select-sm  cool-select">
                            <option value="">Payment</option>
                            <option value="1" {{ $payment_status == '1' ? 'selected' : '' }}>Paid</option>
                            <option value="0" {{ $payment_status == '0' ? 'selected' : '' }}>Unpaid</option>
                        </select>

                        <select name="courier_id" id="courier_id" class="form-select form-select-sm  cool-select">
                            <option value="">Courier</option>
                            @foreach ($couriers as $key => $item)
                                <option value="{{ $key }}" {{ $courier_id == $key ? 'selected' : '' }}>
                                    {{ $item }}</option>
                            @endforeach
                        </select>

                        <div>
                            <input type="text" class="form-control form-control-sm search-input  cool-select"
                                placeholder="Search..." id="search" value="{{ $query }}" name="query">
                            <div class="search-msg">

                            </div>
                        </div>

                        {{-- <button type="submit" class="btn btn-gradient-info btn-sm d-flex align-items-center gap-1">
                            <i class="ti ti-search"></i> Search
                        </button> --}}

                        <a href="{{ route('admin.orders') }}"
                            class="btn btn-gradient-dark btn-sm d-flex align-items-center gap-1">
                            <i class="ti ti-refresh"></i>
                        </a>

                    </form>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-1 mb-2">
                @php
                    $statusCards = [
                        ['title' => 'Orders', 'value' => $total_order, 'status' => 'total', 'color' => '#6c757d'], // Gray

                        [
                            'title' => 'Pending',
                            'value' => $total_pending_order,
                            'status' => 'pending',
                            'color' => '#fd7e14',
                        ],
                        [
                            'title' => 'Confirmed',
                            'value' => $total_confirm_order,
                            'status' => 'confirm',
                            'color' => '#0d6efd',
                        ],

                        [
                            'title' => 'Processing',
                            'value' => $total_processing_order,
                            'status' => 'processing',
                            'color' => '#ffc107',
                        ],
                        ['title' => 'Hold', 'value' => $total_hold_order, 'status' => 'hold', 'color' => '#6f42c1'], // Purple
                        [
                            'title' => 'Printed',
                            'value' => $total_printed_order,
                            'status' => 'printed',
                            'color' => '#6c757d',
                        ], // Gray

                        [
                            'title' => 'Packaged',
                            'value' => $total_packaging_order,
                            'status' => 'packaging',
                            'color' => '#fd7e14',
                        ], // Orange
                        [
                            'title' => 'Courier Entry',
                            'value' => $total_courier_entry_order,
                            'status' => 'courier_entry',
                            'color' => '#0d6efd',
                        ],

                        [
                            'title' => 'On Delivery',
                            'value' => $total_on_delivery_order,
                            'status' => 'on_delivery',
                            'color' => '#20c997',
                        ],
                        [
                            'title' => 'Delivered',
                            'value' => $total_delivered_order,
                            'status' => 'delivered',
                            'color' => '#198754',
                        ], // Green
                        [
                            'title' => 'Cancelled',
                            'value' => $total_cancelled_order,
                            'status' => 'cancelled',
                            'color' => '#dc3545',
                        ], // Red

                        [
                            'title' => 'Return',
                            'value' => $total_returned_order,
                            'status' => 'returned',
                            'color' => '#0dcaf0',
                        ], // Cyan
                    ];
                @endphp


                <div class="status-card-wrapper">
                    @foreach ($statusCards as $card)
                        @php
                            $isActive = $status == $card['status'];
                        @endphp
                        <div class="status-card">
                            <a
                                href="{{ $card['status'] !== '' ? route('admin.orders', ['status' => $card['status']]) : route('admin.orders') }}">
                                <div class="card modern-card hover-scale text-dark {{ $isActive ? 'active' : '' }}"
                                    style="margin: 0; padding: 0;">
                                    <div class="card-body d-flex align-items-center" style="padding: 5px;">
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size:0.80rem;color:{{ $card['color'] }}">
                                                {{ $card['title'] }}</h6>
                                            <h4 class="mb-0 count-up" data-target="{{ $card['value'] }}"
                                                style="font-size:1rem">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="buttons d-flex flex-nowrap gap-2 overflow-auto align-items-start py-1">

                        {{-- Left Section --}}
                        <div class="d-flex gap-2 flex-nowrap">
                            @can('orders.create')
                                <a href="{{ route('admin.orders.create') }}"
                                    class="btn btn-gradient-success btn-sm d-flex align-items-center gap-1">
                                    <i class="ti ti-plus"></i> Add Order
                                </a>
                            @endcan

                            @can('orders.bulk.delete')
                                <form action="{{ route('admin.orders.all.status', ['status_delete' => 'delete']) }}"
                                    method="post" id="all_delete_form" class="d-flex">
                                    @csrf
                                    <input type="hidden" id="all_delete_id" name="all_delete_id" class="all_delete_id">
                                    <button type="button" id="bulk_delete_btn"
                                        onclick="return confirm('Are you sure to Delete selected orders?')"
                                        class="btn btn-gradient-danger btn-sm d-flex align-items-center gap-1">
                                        <i class="ti ti-trash"></i> Bulk Delete
                                    </button>
                                </form>
                            @endcan

                            @can('orders.bulk.print')
                                <form action="{{ route('admin.orders.bulk.print') }}" method="post" id="all_print_form"
                                    class="d-flex">
                                    @csrf
                                    <button type="button" id="bulk_print_btn"
                                        class="btn btn-gradient-info btn-sm d-flex align-items-center gap-1">
                                        <i class="ti ti-printer"></i> Bulk Print
                                    </button>
                                </form>
                            @endcan
                            {{-- @dd($packaging_sts) --}}
                            @can('orders.bulk.send_courier')
                                <a href="javascript:void(0);" type="button"
                                    class="btn btn-sm btn-gradient-primary d-flex align-items-center gap-1"
                                    id="send_to_courier">
                                    <i class="ti ti-truck"></i>
                                    Send To Courier</a>
                            @endcan
                        </div>

                        {{-- Right Section --}}
                        <div class="d-flex gap-2 flex-nowrap">
                            @can('orders.bulk.status')
                                <form action="{{ route('admin.orders.all.status', ['status_update' => 'update']) }}"
                                    method="post" id="all_status_form">
                                    @csrf
                                    <input type="hidden" id="all_status" name="all_status" class="all_status">
                                    <select name="status" id="status" class="form-select form-select-sm cool-select">
                                        <option value="">Select Status</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Confirmed</option>
                                        <option value="3">Processing</option>
                                        <option value="4">Hold</option>
                                        <option value="5">Printed</option>
                                        <option value="6">Packaging</option>
                                        <option value="7">Courier Entry</option>
                                        <option value="8">On Delivery</option>
                                        <option value="9">Delivered</option>
                                        <option value="10">Cancelled</option>
                                        <option value="11">Returned</option>
                                    </select>
                                </form>
                            @endcan

                            {{-- <form action="{{ route('admin.orders.courier_csv') }}" method="post" id="courier-form">
                                @csrf
                                <input type="hidden" id="oders_id" name="oders_id">
                                <select name="status" id="status" class="form-select form-select-sm cool-select">
                                    <option value="">Select Courier</option>
                                    <option value="1">Stead Fast</option>
                                    <option value="2">Redex</option>
                                </select>
                            </form>

                            @can('order_bulk_send_courier')
                                <form action="{{ route('admin.orders.bulk.send.to.courier') }}" method="post"
                                    id="bulk_send_to_courier_form">
                                    @csrf
                                    <input type="hidden" id="order_id" name="order_id" class="order_id">
                                    <select name="courier_id" id="bulk_courier_id"
                                        class="form-select form-select-sm cool-select">
                                        <option value="">Send To Courier</option>
                                        @foreach ($couriers as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endcan --}}

                            <form action="{{ route('admin.orders.bulk.assign') }}" method="post" id="bulk_assign_form">
                                @csrf
                                <input type="hidden" id="order_id" name="order_id" class="order_id">
                                <select name="user_id" id="bulk_user_id" class="form-select form-select-sm cool-select">
                                    <option value="">Assign User</option>
                                    @foreach (DB::table('admins')->where([['status', 1], ['role', 'employee']])->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <style>
                    .order_table thead th {
                        position: sticky;
                        top: 0;
                        /* distance from top */
                        background-color: #fff;
                        /* header background color */
                        z-index: 10;
                        /* make sure it stays above rows */
                    }
                </style>
                <!-- Page Header Close -->
                <div class="col-12">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive order_table" style="height: 100vh; overflow-y: auto;">
                            <div>
                                {{ $orders->links('backEnd.admin.includes.paginate') }}
                            </div>
                            <table class="datatable table table-vcenter card-table order-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">
                                            <input class="form-check-input m-0 align-middle" type="checkbox"
                                                aria-label="Select all invoices" id="master">
                                        </th>
                                        <th>SL.</th>
                                        <th>Invoice ID</th>
                                        <th>Customer Info</th>
                                        <th>Activity</th>
                                        <th>Products</th>
                                        <th style="width: 10%">Amount</th>
                                        <th>Courier</th>
                                        <th>Date</th>
                                        <th>payment</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Notes</th>
                                        <th>Assigned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($orders) --}}
                                    @php($i = 1)
                                    @if ($orders->count() > 0)
                                        @foreach ($orders as $item)
                                            <tr id="tr_{{ $item->id }}"
                                                class="{{ $item->is_duplicate ? 'table-danger' : '' }}"
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td>
                                                    <input class="form-check-input m-0 align-middle sub_chk"
                                                        type="checkbox" data-id="{{ $item->id }}">
                                                </td>
                                                <td class="w-1">{{ $i++ }}</td>

                                                <td>
                                                    <span
                                                        class="badge {{ $item->source == 'direct' ? 'bg-teal' : '' }}
                                                    {{ $item->source == 'call' ? 'bg-danger' : '' }}
                                                    {{ $item->source == 'page' ? 'bg-info' : '' }}
                                                    {{ $item->source == 'whatsapp' ? 'bg-green' : '' }}
                                                    {{ $item->source == 'Inc. Order' ? 'bg-facebook' : '' }}
                                                    {{ $item->source == 'ab_cart' ? 'bg-facebook' : '' }}
                                                    {{ $item->source == 'lp' ? 'bg-success' : '' }}
                                                        ">
                                                        {{ ucfirst($item->source) }}
                                                    </span>
                                                    <br>
                                                    {{ $item->invoice_id }}

                                                </td>
                                                <td style="width: 18%">
                                                    <div class="card card-sm shadow-sm  mb-3" style="">
                                                        <div class="card-body p-2">
                                                            {{-- Customer Info Section --}}
                                                            <div class="pt-2">
                                                                <div class="mb-1 fs-5"><strong>D.ID:</strong>
                                                                    {{ $item->device_id ?? 'N/A' }}</div>
                                                                <div class="mb-1 fs-5"><strong>IP:</strong>
                                                                    {{ $item->ip_address ?? 'N/A' }}</div>
                                                                <div class="mb-1 fs-5"><strong>Name:</strong>
                                                                    {{ $item->customer_name }}</div>
                                                                <div class="mb-1 fs-5">
                                                                    <strong
                                                                        style="min-width:70px; color:#0f172a;">Phone:</strong>
                                                                    <span class="order-phone"
                                                                        data-phone="{{ $item->customer_phone }}"
                                                                        style="font-weight:500;">{{ $item->customer_phone }}</span>

                                                                    <a href="tel:{{ $item->customer_phone }}"
                                                                        class="telephone"
                                                                        onmouseover="this.style.boxShadow='0 4px 10px rgba(6,182,212,0.25)';"
                                                                        onmouseout="this.style.boxShadow='0 2px 5px rgba(6,182,212,0.2)';">
                                                                        <i class="ti ti-phone"
                                                                            style="font-size:14px;"></i>
                                                                    </a>

                                                                </div>

                                                                <div class="fs-5"><strong>Address:</strong>
                                                                    {{ $item->customer_address }}</div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </td>
                                                <td>
                                                    <?php
                                                    $summary = $item->customer_activity['totalSummary'] ?? [];
                                                    $successRate = (int) ($summary['successRate'] ?? 0);
                                                    ?>
                                                    {{-- Circle Chart --}}
                                                    <a href="javascript:void(0)" class="customer_activity_btn"
                                                        data-total="{{ $summary['total'] ?? 0 }}"
                                                        data-total_delivered="{{ $summary['success'] ?? 0 }}"
                                                        data-total_returned="{{ $summary['cancel'] ?? 0 }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#courierDetailsModal_{{ $item->id }}">
                                                        <canvas class="successChart" data-rate="{{ $successRate }}"
                                                            width="75" height="75"
                                                            data-orders="{{ $summary['total'] ?? 0 }} Order"></canvas>
                                                    </a>
                                                    <!-- Courier Details Modal -->
                                                    <div class="modal fade" id="courierDetailsModal_{{ $item->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary text-white">
                                                                    <h5 class="modal-title">Courier Details for
                                                                        {{ $item->customer_name }}</h5>
                                                                    {{-- Update Badge --}}
                                                                    <a href="{{ route('admin.fraud.checker', $item->id) }}"
                                                                        title="Update"
                                                                        style="cursor: pointer;border:1px solid {{ $successRate >= 70 ? '#00a65a' : ($successRate >= 30 ? '#f39c12' : '#dd4b39') }}; border-radius: 4px;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="18" height="18"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class=" icon-tabler">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747" />
                                                                            <path d="M20 4v5h-5" />
                                                                        </svg>
                                                                    </a>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>

                                                                </div>

                                                                <div class="modal-body p-3">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table table-hover table-striped table-bordered">
                                                                            <thead class="table-light">
                                                                                <tr>
                                                                                    {{-- <th>#</th> --}}
                                                                                    <th>Courier Name</th>
                                                                                    <th>Total</th>
                                                                                    <th>Delivered</th>
                                                                                    <th>Cancelled</th>
                                                                                    <th>Success Ratio</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @if (!empty($item->customer_activity['Summaries']))
                                                                                    @foreach ($item->customer_activity['Summaries'] as $index => $courier)
                                                                                        <tr>
                                                                                            <td>{{ $index }}
                                                                                            </td>
                                                                                            <td>{{ $courier['total'] ?? 'N/A' }}
                                                                                            </td>
                                                                                            <td>{{ $courier['success'] ?? 'N/A' }}
                                                                                            </td>
                                                                                            <td>{{ $courier['cancel'] ?? 'N/A' }}
                                                                                            </td>
                                                                                            <td>

                                                                                                @if (!empty($courier['success']))
                                                                                                    {{ round(($courier['success'] / $courier['total']) * 100, 2) }}%
                                                                                                @else
                                                                                                    0.00%
                                                                                                @endif


                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @else
                                                                                    <tr>
                                                                                        <td colspan="7"
                                                                                            class="text-center text-muted">
                                                                                            No courier
                                                                                            information
                                                                                            available.
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="30%">
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach ($item->get_products as $product)
                                                            <?php
                                                            $prod = $product->get_product;
                                                            ?>

                                                            @if ($prod)
                                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                                    {{-- Product Image --}}
                                                                    @if ($prod->get_thumb)
                                                                        <div class="flex-shrink-0">
                                                                            <img src="{{ asset($prod->get_thumb->file_url) }}"
                                                                                alt="{{ $prod->name }}"
                                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                                                        </div>
                                                                    @else<div class="flex-shrink-0">
                                                                            <img src="{{ asset('no_available.jpg') }}"
                                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                                                        </div>
                                                                    @endif

                                                                    {{-- Product Info --}}
                                                                    <div class="flex-grow-1">
                                                                        <div
                                                                            class="d-flex align-items-center gap-1 flex-wrap">
                                                                            <span
                                                                                class="text-danger fw-bold">{{ $product->qty }}
                                                                                x </span>
                                                                            <a target="_blank"
                                                                                href="{{--{{ route('single.product', $prod->slug) }}--}}"
                                                                                class="text-truncate"
                                                                                style="max-width: 250px;">
                                                                                {{ $prod->name }}
                                                                                @if ($product->discount > 0)
                                                                                    <span class="text-danger fw-bold">(C.D:
                                                                                        {{ $product->discount }})</span>
                                                                                @endif

                                                                            </a>
                                                                        </div>

                                                                        @if (!empty($product->attributes))
                                                                            <?php
                                                                            $ids = explode('-', $product->attributes);
                                                                            $attributes = $product->get_product
                                                                                ->get_variants()
                                                                                ->with('items')
                                                                                ->get()
                                                                                ->pluck('items') // সব variant এর items
                                                                                ->flatten()
                                                                                ->whereIn('attribute_item_id', $ids)
                                                                                ->unique('attribute_item_id');
                                                                            // dd($attributes);
                                                                            ?>
                                                                            @foreach ($attributes as $v_item)
                                                                                <?php
                                                                                $attrItem = $v_item->name;
                                                                                $attribute = $v_item->attribute;
                                                                                ?>
                                                                                @if ($loop->first)
                                                                                @else
                                                                                    ,
                                                                                @endif
                                                                                <small
                                                                                    class="text-primary mt-1">{{ $attribute->name }}
                                                                                    : {{ $attrItem }}
                                                                                </small>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td>

                                                {{-- <td>{{ $web_settings?->currency_sign }} {{ $item->total }}</td> --}}
                                                <td>
                                                    <span>
                                                        <strong>Sub : </strong>
                                                        {{ formatNumber($item->sub_total) }}
                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong>Dis.: </strong>
                                                        {{ formatNumber($item->discount) }}
                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong>C.Dis:</strong>
                                                        {{ formatNumber($item->coupon_discount) }}
                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong>D.Charge : </strong>
                                                        {{ formatNumber($item->shipping_cost) }}

                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong>Total : </strong>
                                                        {{ formatNumber($item->total) }}
                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong class="text-success">Paid : </strong>
                                                        {{ formatNumber($item->paid) }}
                                                    </span>
                                                    <br>
                                                    <span>
                                                        <strong class="text-danger">Due : </strong>
                                                        {{ formatNumber($item->due) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($item->get_courier)
                                                        @if ($item->courier_id == 1 || $item->courier_id == 2 || $item->courier_id == 4)
                                                            @if ($item->consignment_id)
                                                                <small class="badge bg-info mb-1">
                                                                    {{ $item->consignment_id }}
                                                                </small>
                                                            @endif

                                                            @if ($item->courier_id == 1)
                                                                <span class="d-flex align-items-center  gap-1">
                                                                    <small>{{ $item->get_courier->courier_name }}</small>
                                                                    @if ($item->tracking_id)
                                                                        <a href="https://steadfast.com.bd/t/{{ $item->tracking_id }}"
                                                                            target="_blank">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="20" height="20"
                                                                                style="color: red;height: 15px;width: 15px;margin-bottom: 2px;"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path
                                                                                    d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                                                <path
                                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                                            </svg>
                                                                        </a>
                                                                    @endif
                                                                </span>
                                                                {{-- <br> --}}
                                                                @if ($item->courier_status)
                                                                    <small class="text-info">
                                                                        {{ $item->courier_status }}
                                                                    </small>
                                                                @endif
                                                            @elseif ($item->courier_id == 4)
                                                                {{-- @dd(4) --}}
                                                                <span class="d-flex align-items-center  gap-1">
                                                                    <small>{{ $item->get_courier->courier_name }}</small>
                                                                    @if ($item->consignment_id)
                                                                        <a href="https://merchant.carrybee.com/order-track/{{ $item->consignment_id }}"
                                                                            target="_blank">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="20" height="20"
                                                                                style="color: red;height: 15px;width: 15px;margin-bottom: 2px;"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path
                                                                                    d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                                                <path
                                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                                            </svg>
                                                                        </a>
                                                                    @endif
                                                                </span>
                                                                <small>
                                                                    <strong>C:</strong>
                                                                    <?php
                                                                    $city = DB::table('carry_bee_cities')->where('parent_id', $item->courier_city_id)->first()->name;
                                                                    ?>
                                                                    {{ $city }}
                                                                </small>
                                                                <br>
                                                                <small>
                                                                    <strong>Z:</strong>
                                                                    <?php
                                                                    $zone = DB::table('carry_bee_zones')->where('id', $item->courier_zone_id)->first()->name;
                                                                    ?>
                                                                    {{ $zone }}
                                                                </small>
                                                                <br>
                                                                @if ($item->courier_status)
                                                                    <small class="text-info">
                                                                        {{ $item->courier_status }}
                                                                    </small>
                                                                @endif
                                                            @else
                                                                <span class="d-flex align-items-center  gap-1">
                                                                    <small>{{ $item->get_courier->courier_name }}</small>
                                                                    @if ($item->consignment_id)
                                                                        <a href="https://merchant.pathao.com/tracking?consignment_id={{ $item->consignment_id }}&phone={{ $item->customer_phone }}"
                                                                            target="_blank">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="20" height="20"
                                                                                style="color: red;height: 15px;width: 15px;margin-bottom: 2px;"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                                    fill="none" />
                                                                                <path
                                                                                    d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                                                <path
                                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                                            </svg>
                                                                        </a>
                                                                    @endif
                                                                </span>
                                                                <small>
                                                                    <strong>C:</strong>
                                                                    <?php
                                                                    $city = DB::table('pathao_cities')->where('parent_id', $item->courier_city_id)->first()->name;
                                                                    ?>
                                                                    {{ $city }}
                                                                </small>
                                                                <br>
                                                                <small>
                                                                    <strong>Z:</strong>
                                                                    <?php
                                                                    $zone = DB::table('pathao_zones')->where('parent_id', $item->courier_zone_id)->first()->name;
                                                                    ?>
                                                                    {{ $zone }}
                                                                </small>
                                                                <br>
                                                                @if ($item->courier_status)
                                                                    <small class="text-info">
                                                                        {{ $item->courier_status }}
                                                                    </small>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($item->tracking_id)
                                                                <small class="badge bg-info mb-1">
                                                                    {{ $item->tracking_id }}
                                                                </small>
                                                            @endif

                                                            {{-- @if ($item->tracking_id) --}}
                                                            <span class="d-flex align-items-center  gap-1">
                                                                <small>{{ $item->get_courier->courier_name }}</small>
                                                                @if ($item->tracking_id)
                                                                    <a href="https://redx.com.bd/track-parcel/?trackingId={{ $item->tracking_id }}"
                                                                        target="_blank">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="20"
                                                                            style="color: red;height: 15px;width: 15px;margin-bottom: 2px;"
                                                                            height="20" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                                            <path
                                                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                                        </svg>
                                                                    </a>
                                                                @endif
                                                            </span>
                                                            <?php
                                                            $city = DB::table('redx_areas')->where('parent_id', $item->courier_zone_id)->first();
                                                            ?>
                                                            <small>
                                                                <strong>C:</strong>

                                                                {{ $city->district }}
                                                            </small>
                                                            <br>
                                                            <small>
                                                                <strong>Z:</strong>
                                                                {{ $city->name }}
                                                            </small>
                                                        @endif
                                                    @else
                                                        <span>---</span> <br>
                                                    @endif


                                                </td>
                                                <td style="width: 10%" class="text-wrap">
                                                    {{ date('d M, Y', strtotime($item->order_date)) }}<br>
                                                    {{ date('h:i:s A', strtotime($item->created_at)) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->payment_status == 1 ? 'bg-success' : '' }} {{ $item->payment_status == 0 ? 'bg-danger' : '' }} {{ $item->payment_status == 0 ? 'bg-primary' : '' }} dropdown dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        @if ($item->payment_status == 1)
                                                            Paid
                                                        @endif
                                                        @if ($item->payment_status == 0)
                                                            Unpaid
                                                        @endif
                                                        @if ($item->payment_status == 2)
                                                            Partial
                                                        @endif
                                                    </span>
                                                    @can('orders.payment_status')
                                                        <span class="dropdown-menu">
                                                            <a class="dropdown-item {{ $item->payment_status == 1 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.payment', [$item->id, 1]) }}">Paid</a>
                                                            <a class="dropdown-item {{ $item->payment_status == 0 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.payment', [$item->id, 0]) }}">Unpaid</a>
                                                            <a class="dropdown-item {{ $item->payment_status == 2 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.payment', [$item->id, 2]) }}">Partial</a>
                                                        </span>
                                                    @endcan
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->status == 1 ? 'bg-warning' : '' }} {{ $item->status == 2 ? 'bg-success' : '' }}{{ $item->status == 3 ? 'bg-info' : '' }}{{ $item->status == 4 ? 'bg-secondary' : '' }}{{ $item->status == 5 ? 'bg-azure' : '' }}{{ $item->status == 6 ? 'bg-primary' : '' }}{{ $item->status == 7 ? 'bg-info' : '' }}{{ $item->status == 8 ? 'bg-teal' : '' }}{{ $item->status == 9 ? 'bg-teal' : '' }}{{ $item->status == 10 ? 'bg-lime' : '' }} status_btn  btn-sm dropdown dropdown-toggle"
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
                                                            Packaged
                                                        @endif
                                                        @if ($item->status == 7)
                                                            Courier Entry
                                                        @endif
                                                        @if ($item->status == 8)
                                                            On Delivery
                                                        @endif
                                                        @if ($item->status == 9)
                                                            Delivered
                                                        @endif
                                                        @if ($item->status == 10)
                                                            Cancelled
                                                        @endif
                                                        @if ($item->status == 11)
                                                            Returned
                                                        @endif
                                                    </span>
                                                    @can('orders.status')
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
                                                                href="{{ route('admin.orders.status', [$item->id, 7]) }}">
                                                                Courier Entry
                                                            </a>
                                                            <a class="dropdown-item {{ $item->status == 8 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.status', [$item->id, 8]) }}">On
                                                                Delivery
                                                            </a>


                                                            <a class="dropdown-item {{ $item->status == 9 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.status', [$item->id, 9]) }}">Delivered
                                                            </a>
                                                            <a class="dropdown-item {{ $item->status == 10 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.status', [$item->id, 10]) }}">Cancelled
                                                            </a>
                                                            <a class="dropdown-item {{ $item->status == 11 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.orders.status', [$item->id, 11]) }}">Returned
                                                            </a>
                                                        </div>
                                                    @endcan
                                                </td>
                                                <td>


                                                    {{-- @dd($item) --}}
                                                    <strong>Staff Note:</strong> <br>
                                                    @if ($item->get_staff_notes && $item->get_staff_notes->isNotEmpty())
                                                        <?php
                                                        $lastNote = $item->get_staff_notes->last();
                                                        ?>
                                                        {{-- @dd($lastNote) --}}
                                                        @if ($lastNote)
                                                            <a href="javascript:void(0)" class="staff_note"
                                                                data-id="{{ $item->id }}">
                                                                <i class="ti ti-square-plus"></i>
                                                            </a>
                                                            <span>{{ $lastNote->note }}</span>
                                                        @endif
                                                    @else
                                                        <a href="javascript:void(0)" class="staff_note"
                                                            data-id="{{ $item->id }}">
                                                            <i class="ti ti-square-plus"></i>
                                                        </a> <span>N/A</span>
                                                    @endif

                                                    <br>

                                                    <strong> Customer Note:</strong> <br>
                                                    @if ($item->get_customer_notes && $item->get_customer_notes->isNotEmpty())
                                                        <?php
                                                        $lastNote = $item->get_customer_notes->last();
                                                        ?>
                                                        {{-- @dd($lastNote->user_id) --}}
                                                        @if ($lastNote)
                                                            <a href="javascript:void(0)" class="customer_note"
                                                                data-user-id="{{ $lastNote->user_id }}"
                                                                data-note="{{ $lastNote->id }}"
                                                                data-id="{{ $item->id }}">
                                                                <i class="ti ti-edit"></i>
                                                            </a>
                                                            <span>{{ $lastNote->note }}</span>
                                                        @endif
                                                    @else
                                                        <span>N/A</span>
                                                    @endif
                                                    <br>
                                                    @if ($item->get_staff_notes || $item->get_customer_notes)
                                                        <a href="#" data-id="{{ $item->id }}"
                                                            class="btn btn-outline-info btn-sm w-50 mt-1 d-flex justify-content-center align-items-center all-notes">
                                                            <i class="ti ti-eye"></i>&nbsp;
                                                            Notes
                                                        </a>
                                                    @endif


                                                </td>
                                                <td>{{ $item->get_assigned ? $item->get_assigned->get_employee->name : '' }}
                                                </td>

                                                <td class="w-1">
                                                    @can('orders.print')
                                                        <a href="javascript:void(0)"
                                                            class="btn-gradient-primary  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 print"
                                                            data-id="{{ $item->id }}">
                                                            <i class="ti ti-printer"></i>
                                                            Print
                                                        </a>
                                                    @endcan
                                                    @can('orders.edit')
                                                        <a href="{{ route('admin.orders.edit', $item->id) }}"
                                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </a>
                                                    @endcan

                                                    @can('orders.delete')
                                                        <a href="javascript:void(0)" data-id="{{ $item->id }}"
                                                            class="btn-gradient-success  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 order_activities">
                                                            <i class="ti ti-history"></i> Activies
                                                        </a>
                                                    @endcan

                                                    @can('orders.delete')
                                                        <a href="{{ route('admin.orders.delete', $item->id) }}"
                                                            class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                            onclick="return confirm('Are you sure to move this to trash?')">
                                                            <i class="ti ti-trash"></i>
                                                            Trash</a>
                                                    @endcan

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


    <!-- Modal for Send to Courier -->
    <div class="modal fade" id="sendToCourierModal" tabindex="-1" aria-labelledby="sendToCourierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- bulk send to courier modal --}}
    <div class="modal fade" id="bulkSendToCourierModal" tabindex="-1" aria-labelledby="bulkSendToCourierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- Modal for Staff Note -->
    <div class="modal fade" id="staffNoteModal" tabindex="-1" aria-labelledby="staffNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- Modal for Customer Note -->
    <div class="modal fade" id="customerNoteModal" tabindex="-1" aria-labelledby="customerNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- Modal for all notes -->
    <div class="modal fade" id="allNotesModal" tabindex="-1" aria-labelledby="allNotesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- Modal for order activities -->
    <div class="modal fade" id="orderActivitiesModal" tabindex="-1" aria-labelledby="orderActivitiesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- Modal for view more -->
    <div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

            </div>
        </div>
    </div>


@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/plugin/datepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.js') }}"></script>
    <script>
        //view more
        $(document).on('click', '.view-more', function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '{{ route('admin.orders.view.more') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    order_id: orderId
                },
                success: function(data) {
                    $('#viewMoreModal .modal-content').html(data);
                    $('#viewMoreModal').modal('show');
                    $('#orderActivitiesModal').modal('hide');
                }
            });

        });

        //order activities
        $(document).on('click', '.order_activities', function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '{{ route('admin.orders.activies') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    order_id: orderId
                },
                success: function(data) {
                    $('#orderActivitiesModal .modal-content').html(data);
                    $('#orderActivitiesModal').modal('show');
                }
            });
        });


        //bulk_assign_form
        $(document).on('change', '#bulk_assign_form', function() {
            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            if (allVals.length <= 0) {
                alert("Please select row.");
            } else {
                $('.order_id').val(allVals);
                $('#bulk_assign_form').submit();
            }
        });

        $(document).on('change', '.city', function() {
            var that = $(this);
            var city_id = $(this).find(':selected').val();
            // console.log(city_id);

            $.ajax({
                url: '{{ route('admin.orders.courier.zone') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    city_id: city_id
                },
                success: function(data) {
                    that.parents('.card').find('.zone').html(data);
                    that.parents('.card').find('.zone').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.staff_note', function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '{{ route('admin.orders.staff.note') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    order_id: orderId,

                },
                success: function(data) {
                    $('#staffNoteModal .modal-content').html(data);
                    $('#staffNoteModal').modal('show');
                }
            });
        });

        //all notes
        $(document).on('click', '.all-notes', function() {
            var orderId = $(this).data('id');
            $.ajax({
                url: '{{ route('admin.orders.notes') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    order_id: orderId,

                },
                success: function(data) {
                    $('#allNotesModal .modal-content').html(data);
                    $('#allNotesModal').modal('show');
                }
            });
        });

        $(document).on('click', '.customer_note', function() {
            var orderId = $(this).data('id');
            var noteId = $(this).data('note');
            var userId = $(this).data('user-id');
            $.ajax({
                url: '{{ route('admin.orders.customer.note') }}',
                type: 'POST',
                data: {
                    _token: @json(csrf_token()),
                    order_id: orderId,
                    note_id: noteId,
                    user_id: userId
                },
                success: function(data) {
                    $('#customerNoteModal .modal-content').html(data);
                    $('#customerNoteModal').modal('show');
                }
            });
        });
    </script>

    <script>
        $(document).on('change', '#bulk_courier_id', function() {

            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var courierId = $(this).val();
            var order_id = $('#order_id').val(allVals);
            var all_ids = order_id.val();
            // alert(order_id.val());
            if (allVals.length <= 0) {
                alert("Please select row.");
            } else {
                $.ajax({
                    url: '{{ Auth::guard('admin')->check() ? route('admin.orders.bulk.send.to.courier') : (Auth::guard('manager')->check() ? route('manager.orders.send.to.courier') : '') }}',
                    type: 'POST',
                    data: {
                        _token: @json(csrf_token()),
                        order_id: all_ids,
                        courier_id: courierId
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Order sent to courier successfully!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('.table-responsive').load(location.href + ' .table-responsive');

                        } else {
                            $('#bulkSendToCourierModal .modal-content').html(data);
                            $('.zone').prop('disabled', true);
                            $('#bulkSendToCourierModal').modal('show');
                        }
                    }

                });

                // $('#bulk_send_to_courier_form').submit();
            }
        });
    </script>
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
                // alert(4);
                if ($(this).is(':checked', true)) {
                    // alert(4)
                    $(".sub_chk").prop('checked', true);
                } else {
                    // alert(5)
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('#status').on('change', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });
                // alert(allVals)
                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('.all_status').val(allVals);
                    $('#all_status_form').submit();
                }
            });
            $('#bulk_delete_btn').on('click', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });
                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('.all_delete_id').val(allVals);
                    $('#all_delete_form').submit();
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
            $('.courier_export').on('change', function(e) {
                // alert($(this).val());
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('#oders_id').val(allVals);
                    $('#courier-form').submit();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / 200;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        });
    </script>
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
    <script>
        //send to courier
        $(document).on('click', '#send_to_courier', function(e) {
            e.preventDefault();

            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });

            if (allVals.length <= 0) {
                alert("Please select at least one row.");
            } else {
                if (confirm("Are you sure you want to send the selected orders to the courier?")) {
                    var ids = allVals.join(',');
                    var url = "{{ route('admin.orders.bulk-ids.send.to.courier') }}?ids=" + ids;
                    window.location.href = url;
                }
            }


        });
    </script>

    <script>
        $(document).ready(function() {

            let hasRange = "{{ $start && $end ? 'yes' : '' }}";

            let start = null;
            let end = null;

            if (hasRange) {
                start = moment("{{ $start }}");
                end = moment("{{ $end }}");
            }

            function updateDisplay(start, end) {
                $('.date_range_display').val(
                    start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY')
                );

                $('#date_range').val(JSON.stringify({
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                }));
            }

            $('.date_range_display').daterangepicker({
                startDate: start ?? moment(),
                endDate: end ?? moment(),
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
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                }
            });

            // ✅ Apply → set + submit
            $('.date_range_display').on('apply.daterangepicker', function(ev, picker) {
                updateDisplay(picker.startDate, picker.endDate);
                $('#search_form').submit();
            });

            // ✅ Cancel → clear + placeholder show
            $('.date_range_display').on('cancel.daterangepicker', function() {
                $(this).val('');
                $('#date_range').val('');
            });

            // Click → open picker
            $('.date_range_display').on('click', function() {
                $(this).data('daterangepicker').show();
            });

            // ✅ Only set value if start & end exist
            if (start && end) {
                updateDisplay(start, end);
            }
            // else → placeholder থাকবে
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.successChart').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const rate = parseInt(canvas.dataset.rate);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [rate, 100 - rate],
                            backgroundColor: [
                                rate < 30 ? '#e74c3c' :
                                    rate < 70 ? '#f1c40f' :
                                        '#2ecc71',
                                '#eaeaea'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '80%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        beforeDraw(chart) {
                            const {
                                ctx,
                                chartArea: {
                                    width,
                                    height
                                }
                            } = chart;
                            ctx.save();
                            ctx.font = 'bold 12px Arial';
                            ctx.fillStyle = '#333';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(rate + '%', width / 2, height / 2);
                            ctx.restore();
                        }
                    }]
                });

            });
        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.successChart').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const rate = parseInt(canvas.dataset.rate); // e.g. 100
                const orders = canvas.dataset.orders ?? '2 Order'; // optional

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [rate, 100 - rate],
                            backgroundColor: [
                                rate < 30 ? '#e74c3c' :
                                rate < 70 ? '#f1c40f' :
                                '#2ecc71',
                                '#eaeaea'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '80%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        beforeDraw(chart) {
                            const {
                                ctx,
                                chartArea
                            } = chart;
                            const centerX = (chartArea.left + chartArea.right) / 2;
                            const centerY = (chartArea.top + chartArea.bottom) / 2;

                            ctx.save();

                            // 🔥 Main Percentage
                            ctx.font = 'bold 18px Arial';
                            ctx.fillStyle = '#333';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(rate + '%', centerX, centerY - 6);

                            // 🔹 Sub text (Order)
                            ctx.font = 'normal 11px Arial';
                            ctx.fillStyle = '#777';
                            ctx.fillText(orders, centerX, centerY + 12);

                            ctx.restore();
                        }
                    }]
                });
            });
        });
    </script>
@endpush
@push('css')
    <style>
        .duplicate-order {
            background-color: #fde2e2 !important;
            border-left: 4px solid #dc3545;
        }
    </style>
@endpush
