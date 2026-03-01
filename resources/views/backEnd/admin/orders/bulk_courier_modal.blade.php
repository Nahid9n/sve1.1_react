<div class="card-header">
    <h5 class="modal-title" id="sendToCourierModalLabel">
        @if ($courier_id == 2)
            Redx Courier
        @endif
        @if ($courier_id == 3)
            Pathao Courier
        @endif
    </h5>
    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="sendToCourierForm" method="post" action="{{ route('admin.orders.bulk.send.to.courier.store') }}">
        @csrf
        {{-- <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}"> --}}
        <input type="hidden" name="courier_id" id="courier_id" value="{{ $courier_id }}">
        @foreach ($orders as $order)
            <input type="hidden" name="order_id[]" value="{{ $order->id }}">
            <div class="card p-3 mb-3 " data-d="1">
                <div class="row">
                    <div class="col-md-6">
                        @if ($order->invoice_id)
                            <span> <strong>Invoice ID :</strong> <span
                                    id="invoice_id"></span>{{ $order->invoice_id }}</span>
                            <br>
                        @endif
                        @if ($order->customer_name)
                            <span> <strong>Name :</strong> <span
                                    id="customer_name"></span>{{ $order->customer_name }}</span>
                            <br>
                        @endif
                        @if ($order->customer_phone)
                            <span> <strong>Phone :</strong> <span
                                    id="customer_phone"></span>{{ $order->customer_phone }}</span>
                            <br>
                        @endif
                        @if ($order->customer_email)
                            <span> <strong>Email :</strong> <span
                                    id="customer_email"></span>{{ $order->customer_email }}</span>
                            <br>
                        @endif
                        @if ($order->customer_address)
                            <span> <strong>Address :</strong> <span
                                    id="customer_address"></span>{{ $order->customer_address }}</span> <br>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select name="city[]" id="city" class="form-select city" required >
                                <option value="">Select City</option>
                                @foreach (DB::table('pathao_cities')->get() as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <label for="zone" class="form-label">Zone</label>
                            <select name="zone[]" id="zone" class="form-select zone" required >
                                <option value="">Select Zone</option>
                                @foreach (DB::table('pathao_zones')->get() as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary courier_btn btn-sm">Send</button>
    </form>
</div>
