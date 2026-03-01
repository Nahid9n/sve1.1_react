@extends('backEnd.admin.layouts.master')

@section('title')
    Dashboard
@endsection

@php
    $net_profit = $data['net_profit'] ?? 0;
    $gross_profit = $data['gross_profit'] ?? 0;
    $total_expense = $data['total_expense'] ?? 0;
    $total_customer = $data['total_customer'] ?? 0;
    $total_product = $data['total_product'] ?? 0;
    $total_staff = $data['total_staff'] ?? 0;

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

    $today_all_orders = $data['today_all_orders'] ?? 0;
    $today_pending_orders = $data['today_pending_order'] ?? 0;
    $today_confirm_orders = $data['today_confirm_order'] ?? 0;
    $today_processing_orders = $data['today_processing_order'] ?? 0;
    $today_hold_orders = $data['today_hold_order'] ?? 0;
    $today_printed_orders = $data['today_printed_order'] ?? 0;
    $today_packaging_orders = $data['today_packaging_order'] ?? 0;
    $today_on_delivery_orders = $data['today_on_delivery_order'] ?? 0;
    $today_courier_entry_orders = $data['today_courier_entry_order'] ?? 0;
    $today_delivered_orders = $data['today_delivered_order'] ?? 0;
    $today_cancelled_orders = $data['today_cancelled_order'] ?? 0;
    $today_returned_orders = $data['today_returned_order'] ?? 0;

    $top_selling_products = $data['top_selling_products'] ?? [];

    $recent_orders = $data['recent_orders'] ?? collect([]);
@endphp

@push('css')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, .1);
            transform: translateY(-2px);
            transition: 0.2s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">

                <div class="row my-3">
                    {{-- Net Profit --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="javascript:void(0);" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Net Profit</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0">
                                            <span>{{ $web_settings?->currency_sign }}</span>
                                            <span class="count-up" data-target="{{ $net_profit }}">0</span>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary fs-5">All-Time Net Profit</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-currency-dollar fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Gross Profit --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="javascript:void(0);" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Gross Profit</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0 ">
                                            <span>{{ $web_settings?->currency_sign }}</span>
                                            <span class="count-up" data-target="{{ $gross_profit }}">0</span>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary fs-5">All-Time Gross Profit</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-currency-dollar fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Total Expense --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="javascript:void(0);" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Total Expenses</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0">
                                            <span>{{ $web_settings?->currency_sign }}</span>
                                            <span class="count-up" data-target="{{ $total_expense }}">0</span>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary fs-5">All-Time Total Expenses</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-currency-dollar fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Orders --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="{{ route('admin.orders', ['status' => 'total']) }}" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Orders</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0 count-up" data-target="{{ $total_order }}">0</div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary text-decoration-underline fs-5">See all orders</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-basket fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Customers --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="{{ route('admin.customers') }}" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Customers</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0 count-up" data-target="{{ $total_customer }}">0</div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary text-decoration-underline fs-5">See all
                                                customers</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-users fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Staffs --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="{{ route('admin.staff.index') }}" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Staffs</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0 count-up" data-target="{{ $total_staff }}">0</div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary text-decoration-underline fs-5">See all staffs</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-user fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Products --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        <a href="{{ route('admin.product') }}" class="text-decoration-none">
                            <div class="card card-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="text-muted text-uppercase fw-semibold">Products</div>
                                            <div class="d-flex align-items-center text-danger">
                                                <i class="ti ti-trending-down me-1"></i>
                                                -100.00%
                                            </div>
                                        </div>
                                        <div class="h2 m-0 count-up" data-target="{{ $total_product }}">0</div>
                                        <div class="d-flex align-items-end justify-content-between">
                                            <span class="text-primary text-decoration-underline fs-5">See all
                                                products</span>
                                            <span class="avatar bg-primary-lt">
                                                <i class="ti ti-basket fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>



                {{-- --- Today's Report & Recent Orders & Stock Alert --- --}}
                {{-- Today's Report --}}
                <div class="row g-3 mb-3">

                    <div class="col-xl-4 col-lg-6 col-md-12 d-flex">
                        <div class="card modern-card w-100">
                            <h4 class="card-header">Today's Report</h4>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                    <table class="table table-borderless table-hover mb-0">
                                        <tbody>
                                            @php
                                                $todayStats = [
                                                    'Orders' => $today_all_orders,
                                                    'Pending' => $today_pending_orders,
                                                    'Confirm' => $today_confirm_orders,
                                                    'Processing' => $today_processing_orders,
                                                    'Hold' => $today_hold_orders,
                                                    'Printed' => $today_printed_orders,
                                                    'Packaging' => $today_packaging_orders,
                                                    'Courier Entry' => $today_courier_entry_orders,
                                                    'On Delivery' => $today_on_delivery_orders,
                                                    'Delivered' => $today_delivered_orders,
                                                    'Cancelled' => $today_cancelled_orders,
                                                    'Returned' => $today_returned_orders,
                                                ];
                                            @endphp
                                            @foreach ($todayStats as $key => $val)
                                                <tr>
                                                    <th>{{ $key }}</th>
                                                    <td>{{ $val }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-12 d-flex flex-column gap-3">
                        {{-- Stock Alert --}}
                        <div class="card modern-card hover-scale flex-fill">
                            <h4 class="card-header">Stock Alert</h4>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                    @php
                                        $setting = DB::table('web_settings')->first();
                                        $stock_alert = $setting?->stock_alert ?? 0;
                                        $products = DB::table('products')
                                            ->when($stock_alert > 0, function ($q) use ($stock_alert) {
                                                $q->where('stock', '<=', $stock_alert);
                                            })
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    <table class="table table-borderless table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $k => $p)
                                                <tr>
                                                    <td>{{ $p->name }}</td>
                                                    <td class="fw-bold text-warning">{{ $p->stock }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-12 d-flex flex-column gap-3">
                        {{-- top selling products  --}}
                        @include('backEnd.admin.top_selling_products')
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        {{-- Recent Orders --}}
                        <div class="card modern-card hover-scale flex-fill">
                            <h4 class="card-header">Recent Orders</h4>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 531px; overflow-y: auto;">
                                    <table class="table table-borderless table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>SL.</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Phone</th>
                                                <th>Total</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_orders as $i=>$order)
                                                @php
                                                    $s = $order->status ?? ($order->get_order->status ?? 0);
                                                    $statusMap = [
                                                        1 => 'badge-pending',
                                                        2 => 'badge-confirm',
                                                        3 => 'badge-processing',
                                                        4 => 'badge-hold',
                                                        5 => 'badge-printed',
                                                        6 => 'badge-packaging',
                                                        7 => 'badge-courier-entry',
                                                        8 => 'badge-on-delivery',
                                                        9 => 'bg-delivered',
                                                        10 => 'badge-cancelled',
                                                        11 => 'badge-returned',
                                                    ];
                                                    $statusName = [
                                                        1 => 'Pending',
                                                        2 => 'Confirm',
                                                        3 => 'Processing',
                                                        4 => 'Hold',
                                                        5 => 'Printed',
                                                        6 => 'Packaged',
                                                        7 => 'Courier Entry',
                                                        8 => 'On Delivery',
                                                        9 => 'Delivered',
                                                        10 => 'Cancelled',
                                                        11 => 'Returned',
                                                    ];
                                                @endphp
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ date('d M', strtotime($order->order_date ?? $order->get_order->order_date)) }}
                                                    </td>
                                                    <td>{{ $order->customer_name ?? $order->get_order->customer_name }}
                                                    </td>
                                                    <td><a
                                                            href="tel:{{ $order->customer_phone ?? $order->get_order->customer_phone }}">{{ $order->customer_phone ?? $order->get_order->customer_phone }}</a>
                                                    </td>
                                                    <td>{{ $web_settings?->currency_sign }}{{ $order->total ?? $order->get_order->total }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $statusMap[$s] ?? 'badge-secondary' }}">{{ $statusName[$s] ?? 'Unknown' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-warning">No Orders Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText.replace(/,/g, ''); // remove commas
                    const increment = target / 200; // adjust speed
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment).toLocaleString();
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };
                updateCount();
            });
        });
    </script>
@endpush
