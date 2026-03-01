{{-- <div class="card-body">
    <div class="table-responsive">
        <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
            <thead>
            <tr>
                <th>SL</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Purchase Cost</th>
                <th>Sell Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @php($i=1)
            @if(count($data->get_purchase_items)>0)
                @foreach($data->get_purchase_items as $item)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{ $item->get_product->name }} <span class="text-info">#{{$item->sku}}</span></td>
                        <td>{{ $item->product_quantity }}</td>
                        <td>{{ $setting->currency_sign }} {{ number_format($item->purchase_cost,2) }}</td>
                        <td>{{ $setting->currency_sign }} {{ number_format($item->sell_price,2) }}</td>
                        <td>{{ $setting->currency_sign }} {{ number_format($item->total,2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-danger text-center">No Data Available!</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div> --}}
