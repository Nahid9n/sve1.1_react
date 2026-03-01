@extends('backEnd.admin.layouts.master')
@section('title', 'Send To Courier')

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
    <style>
        /* Summary box styling */
        #courierSummary {
            position: relative;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        #courierSummary.sticky {
            position: fixed;
            top: 65px;
            right: 30px;
            background-color: #f0f1f7;
            padding: 5px 7px;
            border-radius: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        /* small visual tweaks */
        .telephone {
            margin-left: 8px;
            display: inline-block;
        }

        .result-text {
            min-width: 160px;
            display: inline-block;
        }
    </style>
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

@section('content')
    <div class="page-body mt-0">
        <div class="container-xl">
            <div class="row my-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Send to Courier</h4>

                    <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
                        <!-- Summary Buttons -->
                        <div id="courierSummary" class="d-flex gap-2">
                            <button class="btn btn-outline-dark btn-sm" disabled>
                                Total: <span id="totalCount">0</span>
                            </button>
                            <button class="btn btn-outline-success btn-sm" disabled>
                                ✅ Success: <span id="successCount">0</span>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" disabled>
                                ❌ Failed: <span id="failedCount">0</span>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <button class="btn btn-success btn-sm d-none" id="submitToCourier">
                            <i class='bx bx-revision'></i> Re-Submit
                        </button>
                        <a href="{{ route('admin.orders') }}" class="btn btn-danger btn-sm d-none" id="backToList">
                            <i class='bx bxs-share'></i> Back
                        </a>
                    </div>

                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="courierTable">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Location</th>
                                    <th>Courier</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr data-id="{{ $order->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php $src = $order->source @endphp
                                            @if ($src == 'direct')
                                                <span class="badge bg-teal">{{ ucfirst($src) }}</span>
                                            @elseif ($src == 'call')
                                                <span class="badge bg-danger">{{ ucfirst($src) }}</span>
                                            @elseif ($src == 'page')
                                                <span class="badge bg-info">{{ ucfirst($src) }}</span>
                                            @elseif ($src == 'whatsapp')
                                                <span class="badge bg-green">{{ ucfirst($src) }}</span>
                                            @elseif ($src == 'ab_cart')
                                                <span class="badge bg-facebook">{{ ucfirst($src) }}</span>
                                            @endif
                                            <br>
                                            {{ $order->invoice_id }}
                                        </td>

                                        <td class="text-start">
                                            <div class="mb-1 fs-5"><strong>IP:</strong> {{ $order->ip_address ?? 'N/A' }}
                                            </div>
                                            <div class="mb-1 fs-5"><strong>Name:</strong> {{ $order->customer_name }}</div>

                                            <div class="mb-1 fs-5">
                                                <strong style="min-width:70px; color:#0f172a;">Phone:</strong>
                                                <span style="font-weight:500;">{{ $order->customer_phone }}</span>
                                                <a href="tel:{{ $order->customer_phone }}" class="telephone"
                                                    onmouseover="this.style.boxShadow='0 4px 10px rgba(6,182,212,0.25)';"
                                                    onmouseout="this.style.boxShadow='0 2px 5px rgba(6,182,212,0.2)';">
                                                    <i class="ti ti-phone" style="font-size:14px;"></i>
                                                </a>
                                            </div>

                                            <div class="fs-5"><strong>Address:</strong> {{ $order->customer_address }}
                                            </div>
                                        </td>

                                        <td>{{ number_format($order->total, 2) }}</td>

                                        <td>
                                            <?php
                                            if ($order->courier_id == 2) {
                                                $cityZones = DB::table('pathao_cities as c')->join('pathao_zones as z', 'c.parent_id', '=', 'z.city_id')->select('c.parent_id as city_id', 'c.name as city_name', 'z.parent_id as zone_id', 'z.name as zone_name')->get();
                                            } elseif ($order->courier_id == 4) {
                                                // $cityZones = DB::table('carrybee_cities as c')->join('carrybee_zones as z', 'c.parent_id', '=', 'z.city_id')->select('c.parent_id as city_id', 'c.name as city_name', 'z.parent_id as zone_id', 'z.name as zone_name')->get();
                                            } elseif ($order->courier_id == 3) {
                                                $cityZones = DB::table('redx_areas')->select('parent_id', 'district', 'division', 'name', 'zone_id')->get();
                                            } else {
                                                $cityZones = [];
                                            }
                                            ?>


                                            {{-- @dd($order->pathaoCity->city_name, $order->pathaoZone->zone_name) --}}

                                            @if ($order->courier_id == 2)
                                                <b>Search City / Zone:</b>
                                                <select class="form-control form-control-sm city-zone-dropdown select2">
                                                    <option value="">--Search--</option>
                                                    @if (!empty($cityZones))
                                                        @foreach ($cityZones as $cz)
                                                            <option value="{{ $cz->city_id }}_{{ $cz->zone_id }}">
                                                                {{ $cz->city_name }} → {{ $cz->zone_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if (($order->pathaoCity->name ?? false) || ($order->pathaoZone->name ?? false))
                                                    <div class="pt-2">{{ $order->pathaoCity->name ?? '' }} →
                                                        {{ $order->pathaoZone->name ?? '' }}
                                                    </div>
                                                @endif
                                            @elseif($order->courier_id == 4)
                                                @if (($order->carrybeeCity->name ?? false) || ($order->carrybeeZone->name ?? false))
                                                    <div class="pt-2">{{ $order->carrybeeCity->name ?? '' }} →
                                                        {{ $order->carrybeeZone->name ?? '' }}
                                                    </div>
                                                @endif
                                            @elseif($order->courier_id == 3)
                                                <b>Search City / Zone:</b>
                                                <select class="form-control form-control-sm city-zone-dropdown select2">
                                                    <option value="">--Search--</option>
                                                    @if (!empty($cityZones))
                                                        @foreach ($cityZones as $cz)
                                                            <option value="{{ $cz->zone_id }}_{{ $cz->parent_id }}">
                                                                {{ $cz->district }} → {{ $cz->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if ($order->redxArea)
                                                    <div class="pt-2"> {{ $order->redxArea->district ?? '' }} →
                                                        {{ $order->redxArea->name ?? '' }}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if ($order->consignment_id)
                                                <span
                                                    class="badge bg-secondary consignment-id">{{ $order->consignment_id }}</span>
                                            @endif
                                            <span
                                                class="courier-name">{{ $order->get_courier ? $order->get_courier->courier_name : '---' }}</span>
                                        </td>

                                        <td class="text-center align-middle text-wrap" style="width: 20%">
                                            <div class="spinner-border d-none" role="status" aria-hidden="true"></div>
                                            <span class="result-text text-muted"></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- @if ($orders->hasPages())
                            <div class="mt-3">
                                {{ $orders->links() }}
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(function() {

            const $rows = $("#courierTable tbody tr");
            const $successCount = $("#successCount");
            const $failedCount = $("#failedCount");
            const $submitBtn = $("#submitToCourier");
            const $backBtn = $("#backToList");

            let success = 0,
                failed = 0,
                running = false;

            $("#totalCount").text($rows.length);

            function updateSummary() {
                $successCount.text(success);
                $failedCount.text(failed);
            }

            function markRow($row, status, message, consignment = null, courier = null) {
                $row.find(".spinner-border").addClass("d-none");

                const $text = $row.find(".result-text")
                    .removeClass("text-success text-danger text-muted")
                    .addClass(status === "success" ? "text-success" : "text-danger")
                    .text((status === "success" ? "✅ " : "❌ ") + message);

                if (consignment) {
                    $row.find(".consignment-id").remove();
                    $row.find("td").eq(5).prepend(
                        `<span class="badge bg-secondary consignment-id">${consignment}</span><br>`
                    );
                }
                if (courier) {
                    $row.find(".courier-name").text(courier);
                }
            }

            function processRow(i) {
                if (i >= $rows.length) {
                    running = false;
                    failed > 0 ? $submitBtn.removeClass("d-none") : $backBtn.removeClass("d-none");
                    return alert("All orders processed!");
                }

                const $row = $($rows[i]);
                const orderId = $row.data("id");
                const val = $row.find(".city-zone-dropdown").val();
                const [city, zone] = val ? val.split("_") : [null, null];

                $row.find(".spinner-border").removeClass("d-none");
                $row.find(".result-text").text("Processing...").addClass("text-muted");

                $("html, body").animate({
                    scrollTop: $row.offset().top - 100
                }, 300);

                $.post("{{ route('admin.ordercourier.send.row') }}", {
                        order_id: orderId,
                        city_id: city,
                        zone_id: zone,
                        _token: "{{ csrf_token() }}"
                    })
                    .done(res => {
                        if (res.status) {
                            markRow($row, "success", res.message, res.consignment_id, res.courier);
                            success++;
                        } else {
                            markRow($row, "error", res.message);
                            failed++;
                        }
                        updateSummary();
                        setTimeout(() => processRow(i + 1), 600);
                    })
                    .fail(xhr => {
                        failed++;
                        updateSummary();
                        markRow($row, "error", xhr.responseJSON?.message || "Server Error");
                        setTimeout(() => processRow(i + 1), 600);
                    });
            }

            function start() {
                success = failed = 0;
                updateSummary();
                $submitBtn.addClass("d-none");
                $backBtn.addClass("d-none");
                running = true;
                processRow(0);
            }

            start();

            $submitBtn.on("click", () => !running && start());

            window.addEventListener("beforeunload", e => {
                if (running) {
                    e.preventDefault();
                    e.returnValue = true;
                }
            });

        });
    </script>
@endpush
