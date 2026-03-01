<!DOCTYPE html>
<html>
<head>
    <title>Invoice Label Print</title>
    {{--    <link rel="stylesheet" href="{{asset('/')}}backEnd/assets/vendor/bootstrap/css/bootstrap.min.css">--}}
    <style>
        @media print {
            @page {
                size: 3in 2in;
                margin: 0;
            }

            body {
                margin: 0;
            }
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        /* PAGE WRAPPER */
        .sheet {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* SINGLE LABEL */
        .label {
            width: 3in;
            height: 2in;
            border: 1px solid #000;   /* ✅ ONLY OUTSIDE BORDER */
            padding: 2px;
            box-sizing: border-box;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 9px;
            line-height: 1.3;
        }

        .sku-box {
            margin: 6px 0;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }

        .divider {
            height: 1px;
            background: #000;
            margin: 2px 0;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 7px;
        }

        .product-table th {
            border-bottom: 1px solid #eee;
            text-align: left;
            font-weight: bold;
        }

        .product-table td {
            padding-top: 2px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th,
        .product-table td {
            vertical-align: middle;
        }

        /* Product → Left */
        .product-table th:first-child,
        .product-table td:first-child {
            text-align: left;
        }

        /* Qty → Center */
        .product-table th:nth-child(2),
        .product-table td:nth-child(2) {
            text-align: center;
        }

        /* Price → Right */
        .product-table th:nth-child(3),
        .product-table td:nth-child(3) {
            text-align: right;
        }

        /* optional clean look */
        .product-table thead {
            background: #f5f5f5;
        }

        .product-table tbody tr {
            /*border-bottom: 1px solid #eee;*/
        }

    </style>
</head>

<body onload="window.print()">

<div class="sheet">
    @foreach($data as $order)
        <div class="label">

            <!-- HEADER -->
            <div class="row">
                <div class="align-items-center">
                    <img width="70px" src="{{asset($web_settings->get_logo->file_url)}}" alt="">
                </div>
                @if(!empty($order->stead_fast_consignment_id))
                    <div class="bold" style="font-size: 15px">
                        CN - {{ $order->stead_fast_consignment_id }}
                    </div>
                @endif
            </div>

            <div class="divider"></div>
            <!-- CUSTOMER -->
            <div class="row">
                <div class="bold" align="left">Invoice No - {{ $order->invoice_id }}</div>
                <div align="right">Date - {{ $order->created_at->format('d-m-Y') }}</div>
            </div>


            <!-- SKU + QTY -->
            <div class="sku-box">
                <table class="product-table table-bordered">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->get_products as $p)
                        <tr>
                            <td class="text-wrap">
                                <span style="font-weight: bold">{{ $p->get_product->sku }} - </span> <span style="font-weight: normal">{{ $p->get_product->name }}</span>
                            </td>
                            <td>{{ $p->qty ?? 1 }}</td>
                            <td width="10%" style="text-wrap: normal">{{$web_settings->currency_sign}} {{$p->price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tbody>
                    <tr align="right">
                        <td align="right" colspan="2" style="text-align: right"><small style="font-size: 7px;" >Delivery Charge </small></td>
                        <td style="text-align: right">{{$web_settings->currency_sign}}  {{$order->shipping_cost}}</td>
                    </tr>
                    <tr align="right">
                        <td align="right" colspan="2" style="text-align: right"><small style="font-size: 7px;">Total </small></td>
                        <td width="10%" style="text-wrap: normal; text-align: right;">{{$web_settings->currency_sign}} {{$order->total}}</td>
                    </tr>

                    </tbody>
                </table>
                <div class="" align="right">


                </div>
            </div>


            <div class="row">
                <div class="small" align="center" style="position: fixed; bottom: 0">
                    www.efoshop.com - HotLine : {{$web_settings->website_phone}}
                </div>
            </div>

            <!-- ADDRESS -->


        </div>
    @endforeach
</div>
<script>
    window.onload = function () {
        window.print();
        window.close();
    }
</script>
</body>
</html>
