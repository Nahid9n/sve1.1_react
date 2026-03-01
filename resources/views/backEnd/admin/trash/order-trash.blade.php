@extends('backEnd.admin.layouts.master')

@section('title')
    Orders Trash
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

        .trash {
            position: relative;
            height: 23.14px;
            display: block;
        }

        .trash-count {
            position: absolute;
            height: 22px;
            width: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            bottom: 33px;
            left: 48px;
            font-weight: 700;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('backEnd/assets/plugin/datepicker/daterangepicker.css') }}" />
@endpush
@php
    $daterange = $daterange ?? null;
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row mb-2 ">
                <div class="col-12 d-flex justify-content-end mb-2">
                    <a href="{{ route('admin.orders') }}" class="btn btn-dark btn-sm">
                        <i class="ti ti-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <h3 class="m-0">
                        Orders Trash
                    </h3>
                    <div class="search d-flex justify-content-end align-items-center">
                        <form action="{{ route('admin.orders.trash') }}" id="date_range_form"
                            class="d-flex flex-md-row flex-column align-items-end justify-content-end">
                            <input type="hidden" class="start_date" name="start_date">
                            <input type="hidden" class="end_date" name="end_date">
                            <input type="text" class="form-control form-control-sm  date_range me-2"
                                placeholder="Date Range..." autocomplete="off" value="{{ $daterange }}"
                                name="date_range">
                            <input type="hidden" name="status" value="{{ $status ?? null }}">
                            <select name="source" id="source" class="form-select form-select-sm me-2">
                                <option value="">Select Source</option>
                                <option value="direct" {{ request()->source == 'direct' ? 'selected' : '' }}>Direct</option>
                                <option value="call" {{ request()->source == 'call' ? 'selected' : '' }}>Call</option>
                                <option value="page" {{ request()->source == 'page' ? 'selected' : '' }}>Page</option>
                                <option value="whatsapp" {{ request()->source == 'whatsapp' ? 'selected' : '' }}>Whatsapp
                                </option>
                                <option value="ab_cart" {{ request()->source == 'ab_cart' ? 'selected' : '' }}>Abandoned
                                    Cart</option>
                            </select>

                            <input type="text" class="form-control form-control-sm  me-2" placeholder="Search..."
                                name="search" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-info btn-sm me-2">Search</button>
                            <a href="{{ route('admin.orders.trash') }}" class="btn btn-sm btn-dark reset_button"><i
                                    class="ti ti-refresh"></i></a>
                        </form>
                    </div>
                </div>

            </div>
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive order_table">
                            <div>
                                {{ $orders->links('backEnd.admin.includes.paginate') }}
                            </div>
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
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td>
                                                    <input class="form-check-input m-0 align-middle sub_chk" type="checkbox"
                                                        data-id="{{ $item->id }}">
                                                </td>
                                                <td class="w-1">{{ $i++ }}</td>

                                                <td>
                                                    @if ($item->source == 'direct')
                                                        <span class="badge bg-teal">{{ ucfirst($item->source) }}</span>
                                                    @endif
                                                    @if ($item->source == 'call')
                                                        <span class="badge bg-danger">{{ ucfirst($item->source) }}</span>
                                                    @endif
                                                    @if ($item->source == 'page')
                                                        <span class="badge bg-info">{{ ucfirst($item->source) }}</span>
                                                    @endif
                                                    @if ($item->source == 'whatsapp')
                                                        <span class="badge bg-green">{{ ucfirst($item->source) }}</span>
                                                    @endif
                                                    @if ($item->source == 'ab_cart')
                                                        <span class="badge bg-facebook">{{ ucfirst($item->source) }}</span>
                                                    @endif
                                                    <br>
                                                    {{ $item->invoice_id }}

                                                </td>
                                                <td style="width: 18%">
                                                    <?php
                                                    $summary = $item->customer_activity['totalSummary'] ?? [];
                                                    $successRate = (int) ($summary['successRate'] ?? 0);
                                                    ?>
                                                    <div class="card card-sm shadow-sm  mb-3"
                                                        style="border-width: 1px;border-color: {{ $successRate >= 70 ? '#00a65a' : ($successRate >= 30 ? '#f39c12' : '#dd4b39') }};">
                                                        <div class="card-body p-2">

                                                            {{-- Courier Summary + Buttons on one line --}}

                                                            {{-- Badges --}}
                                                            <div
                                                                class="d-flex flex-nowrap justify-content-between align-items-center gap-1 mb-1 fs-md">
                                                                <span>T:
                                                                    {{ $summary['total'] ?? 0 }}</span>
                                                                <span> D:
                                                                    {{ $summary['success'] ?? 0 }}</span>
                                                                <span> C:
                                                                    {{ $summary['cancel'] ?? 0 }}</span>
                                                                <span class="badge text-white"
                                                                    style="{{ $successRate >= 70 ? 'background-color: #00a65a' : ($successRate >= 30 ? 'background-color: #f39c12' : 'background-color: #dd4b39') }}">
                                                                    R:
                                                                    {{ $successRate }}%</span>
                                                                {{-- Icon Buttons --}}

                                                                {{-- Courier Details Badge --}}
                                                                <span
                                                                    style="cursor: pointer; border: 1px solid {{ $successRate >= 70 ? '#00a65a' : ($successRate >= 30 ? '#f39c12' : '#dd4b39') }}; border-radius: 4px;"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#courierDetailsModal_{{ $item->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                                        height="18" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M12 9h.01" />
                                                                        <path d="M11 12h1v4h1" />
                                                                    </svg>
                                                                </span>

                                                                <!-- Courier Details Modal -->
                                                                <div class="modal fade"
                                                                    id="courierDetailsModal_{{ $item->id }}"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <div
                                                                        class="modal-dialog modal-lg modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div
                                                                                class="modal-header bg-primary text-white">
                                                                                <h5 class="modal-title">Courier Details for
                                                                                    {{ $item->customer_name }}</h5>
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


                                                                {{-- Update Badge --}}
                                                                <a href="{{ route('admin.fraud.checker', $item->id) }}"
                                                                    title="Update"
                                                                    style="cursor: pointer;border:1px solid {{ $successRate >= 70 ? '#00a65a' : ($successRate >= 30 ? '#f39c12' : '#dd4b39') }}; border-radius: 4px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                                        height="18" viewBox="0 0 24 24" fill="none"
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


                                                            </div>
                                                            {{-- Customer Info Section --}}
                                                            <div class="border-top pt-2">
                                                                <div class="mb-1 fs-5"><strong>D.ID:</strong>
                                                                    {{ $item->device_id ?? 'N/A' }}</div>
                                                                <div class="mb-1 fs-5"><strong>IP:</strong>
                                                                    {{ $item->ip_address ?? 'N/A' }}</div>
                                                                <div class="mb-1 fs-5"><strong>Name:</strong>
                                                                    {{ $item->customer_name }}</div>
                                                                <div class="mb-1 fs-5">
                                                                    <strong
                                                                        style="min-width:70px; color:#0f172a;">Phone:</strong>
                                                                    <span
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
                                                                                href="{{ route('single.product', $prod->slug) }}"
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
                                                        @if ($item->courier_id == 1 || $item->courier_id == 2)
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
                                                <td>
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

                                                    @can('orders.delete')
                                                        <a href="{{ route('admin.orders.restore', $item->id) }}"
                                                            class="btn btn-outline-warning btn-sm w-100 mb-1 restore"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="ti ti-recycle"></i>
                                                            Restore
                                                        </a>
                                                    @endcan

                                                    @can('orders.delete')
                                                        <a href=" {{ route('admin.orders.force.delete', $item->id) }}"
                                                            class="btn btn-danger border-0 btn-sm w-100 d-flex justify-content-center gap-1 mb-1 delete"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="ti ti-trash"></i>

                                                            Delete
                                                        </a>
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
@endpush
