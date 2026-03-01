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
    <form id="sendToCourierForm" method="post" action="{{ route('admin.orders.send.to.courier.store') }}">
        @csrf
        <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
        <input type="hidden" name="courier_id" id="courier_id" value="{{ $courier_id }}">
        <div class="card ">
            <div class="row">
                <div class="col-md-6 p-3" style="border-right:  1px solid #e0e0e0;">
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
                <div class="col-md-6 p-3">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select name="city" id="city" class="form-select city" required>
                            <option value="">Select City</option>
                            @foreach (DB::table('pathao_cities')->get() as $city)
                                <option value="{{ $city->parent_id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <label for="zone" class="form-label">Zone</label>
                        <select name="zone" id="zone" class="form-select zone" required>
                            <option value="">Select Zone</option>
                            @foreach (DB::table('pathao_zones')->get() as $zone)
                                <option value="{{ $zone->parent_id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm courier_btn mt-2">Send</button>
    </form>
</div>
