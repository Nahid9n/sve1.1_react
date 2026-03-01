<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation #{{ $order->invoice_id }}</title>
    <style>
        /* RESET & BASE */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }

        /* GRID SYSTEM (ROW/COL) */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 15px;
        }

        /* CARD DESIGN */
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
            text-align: center;
        }

        /* SUCCESS ICON/TEXT */
        .success-icon {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 15px;
        }
        h2 { color: #1a1a1a; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 5px; }

        /* TABLE STYLING */
        .order-details {
            margin: 30px 0;
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .order-details th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 12px;
            border-bottom: 2px solid #ececec;
        }
        .order-details td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* TOTAL SECTION */
        .total-row {
            font-size: 18px;
            font-weight: bold;
            background: #fafafa;
        }
        .total-amount { color: #007bff; }

        /* BUTTON */
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: background 0.2s;
        }
        .btn:hover { background-color: #0056b3; }

        hr { border: 0; border-top: 1px solid #eee; margin: 25px 0; }

        @media (max-width: 600px) {
            .card { padding: 20px; }
            .order-details { font-size: 14px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="success-icon">✔</div>
                <h2>Order Placed Successfully!</h2>
                <p>Thank you, <strong>{{ $order->customer_name }}</strong>. We've received your order.</p>
                <p>Invoice ID: <span style="color: #333; font-weight: bold;">#{{ $order->invoice_id }}</span></p>

                <hr>

                <table class="order-details">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->get_products as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->get_product->name }}</strong>
                                @if($item->attributes)
                                    <br>
                                    @php
                                        $ids = explode('-', $item->attributes);
                                        $displayAttr = $item->get_product->get_variants->pluck('items')->flatten()
                                            ->whereIn('attribute_item_id', $ids)->unique('attribute_item_id');
                                    @endphp

                                    @foreach($displayAttr as $v_item)
                                        <small class="text-primary mt-1">
                                            {{ $v_item->attribute->name }}: {{ $v_item->name }}{{ !$loop->last ? ',' : '' }}
                                        </small>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-right">{{ number_format($item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" class="text-right">Grand Total:</td>
                            <td class="text-right total-amount">{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row">
                    <div class="col-12">
                        <p style="font-size: 14px;">A confirmation has been logged for <strong>{{ $order->customer_phone }}</strong></p>
                        <a href="{{ url('/') }}" class="btn">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
