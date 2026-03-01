<table class="table table-vcenter card-table order-table">
    <thead>
        <tr>

            <th>SL.</th>
            <th>Invoice</th>
            <th>Customer Info</th>
            <th>Products</th>
            <th>Total</th>
            <th>Date</th>
            <th>payment</th>
            <th>Status</th>

        </tr>
    </thead>
    <tbody>
        @php($i = 1)
        @if ($orders->count() > 0)
            @foreach ($orders ?? [] as $item)
                <tr id="tr_{{ $item->id }}">
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
                            <a href="{{ route('admin.orders',['query' => $item->invoice_id]) }}">{{ $item->invoice_id }}</a>

                    </td>
                    <td>
                        <span class="mb-1 d-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-map-pin"
                                width="15" height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                </path>
                                <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                <path
                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                </path>
                            </svg>
                            {{ $item->ip_address ?? 'N/A' }}
                        </span>

                        <span class="mb-1 d-block"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon-tabler icon-tabler-user" width="15" height="15" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                </path>
                                <path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                            {{ $item->customer_name }}
                        </span>
                        <span class="mb-1 d-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-phone" width="15"
                                height="15" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                </path>
                                <path
                                    d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                </path>
                            </svg>
                            <a href="tel:{{ $item->customer_phone }}"><span>{{ $item->customer_phone }}</span></a>
                        </span>
                        <span class="mb-1 d-block"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon-tabler icon-tabler-map-pin" width="15" height="15"
                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                </path>
                                <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                <path
                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                </path>
                            </svg> {{ $item->customer_address }}</span>
                    </td>
                    <td>
                        @foreach ($item->get_products ?? [] as $product)
                        @if ($product->get_product && $product->attributes)
                                <div class="product-info d-flex">
                                    <span class="image me-2">
                                        <img src="{{ $product->get_product->get_thumb ? asset($product->get_product->get_thumb->file_url) : '' }}"
                                            height="40" alt="">
                                    </span>
                                    <span class="name">
                                        {{ $product->qty }} x
                                        {{ $product->get_product->name }} <br>
                                        @foreach ((array) json_decode($product->attributes, true) as $key => $attr)
                                        <span class="text-primary" style="font-size:12px">{{ $key }} -
                                                {{ $attr }}</span>
                                            @if ($loop->last)
                                                @break

                                            @else
                                                ,
                                            @endif
                                        @endforeach

                                    </span>
                                </div>
                            @else
                                <div class="product-info d-flex">
                                    <span class="image me-2">
                                        <img src="{{ $product->get_product->get_thumb ? asset($product->get_product->get_thumb->file_url) : '' }}"
                                            height="40" alt="">
                                    </span>
                                    <span class="name">
                                        {{ $product->qty }} x
                                        {{ $product->get_product->name }} <br>
                                    </span>
                                </div>
                            @endif
                            {{-- @if ($product->attributes)
                                <br>
                                @foreach (json_decode($product->attributes, true) as $key => $attr)
                                    <span class="text-primary"
                                        style="font-size:12px">{{ $key }} -
                                        {{ $attr }}</span>
                                    @if ($loop->last)
                                        @break

                                    @else
                                        ,
                                    @endif
                                @endforeach
                            @endif --}}
                            <br>
                        @endforeach
                    </td>
                    <td>{{ $web_settings?->currency_sign }} {{ $item->total }}</td>

                    <td>
                        {{ date('d M, Y', strtotime($item->order_date)) }}<br>
                        {{ date('h:i:s A', strtotime($item->created_at)) }}
                    </td>
                    <td>
                        <span
                            class="badge {{ $item->payment_status == 1 ? 'bg-success' : '' }} {{ $item->payment_status == 0 ? 'bg-danger' : '' }} {{ $item->payment_status == 0 ? 'bg-primary' : '' }} ">
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

                    </td>
                    <td>
                        <span
                            class="badge {{ $item->status == 1 ? 'bg-warning' : '' }} {{ $item->status == 2 ? 'bg-success' : '' }}{{ $item->status == 3 ? 'bg-info' : '' }}{{ $item->status == 4 ? 'bg-secondary' : '' }}{{ $item->status == 5 ? 'bg-azure' : '' }}{{ $item->status == 6 ? 'bg-primary' : '' }}{{ $item->status == 7 ? 'bg-lime' : '' }}{{ $item->status == 8 ? 'bg-teal' : '' }}{{ $item->status == 9 ? 'bg-danger' : '' }}{{ $item->status == 10 ? 'bg-lime' : '' }} status_btn  btn-sm ">
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
