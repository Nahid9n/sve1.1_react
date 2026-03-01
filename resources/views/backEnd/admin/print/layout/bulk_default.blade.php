<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Print</title>

    <style>
        @page{
            size:A4;
            margin:10mm;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            margin:0;
            color:#000;
        }

        .page{
            width:100%;
        }

        /* ===== INVOICE BOX ===== */
        .invoice-box{
            height: 500px;
            border:1.5px solid #000;
            padding:10px;
            box-sizing:border-box;
            margin-bottom:10px;
            position:relative;
        }

        /* cut line */
        .invoice-box:after{
            content:"";
            position:absolute;
            bottom:-6px;
            left:0;
            width:100%;
            border-bottom:1px dashed #000;
        }

        /* ===== HEADER ===== */
        .header{
            display:flex;
            border-bottom:1px solid #000;
            padding-bottom:6px;
            margin-bottom:6px;
        }

        .header-left{
            width:35%;
        }
        .header-left img{
            max-height:50px;
        }
        .company-info{
            font-size:10px;
            margin-top:4px;
        }

        .header-middle{
            width:35%;
            padding-left:8px;
            border-left:1px solid #000;
        }

        .header-right{
            width:30%;
            text-align:right;
            padding-left:8px;
            border-left:1px solid #000;
        }

        .header-right h3{
            margin:0;
            font-size:16px;
        }

        .barcode{
            margin-top:4px;
        }

        /* ===== INFO ===== */
        .info{
            display:flex;
            margin:6px 0;
        }

        .info div{
            width:50%;
        }

        .info p{
            margin:2px 0;
        }

        /* ===== TABLE ===== */
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:6px;
        }

        th,td{
            border:1px solid #000;
            padding:4px;
        }

        th{
            background:#f1f1f1;
        }

        .text-center{text-align:center;}
        .text-right{text-align:right;}

        /* ===== TOTAL ===== */
        .total-row td{
            font-weight:bold;
        }

        /* ===== FOOTER ===== */
        .footer{
            position:absolute;
            bottom:10px;
            left:10px;
            right:10px;
            font-size:9px;
            text-align:center;
        }
    </style>
</head>

<body>

<div class="page">

        @foreach($orders as $data)

    <div class="invoice-box">

        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                <img src="{{ asset($web_settings?->get_header->file_url) }}">
                <div class="company-info">
                    {{ $web_settings?->website_address }}<br>
                    {{ $web_settings?->website_phone }}
                </div>
            </div>

            <div class="header-middle">
                <strong>Customer</strong><br>
                {{ $data->customer_name }}<br>
                {{ $data->customer_phone }}<br>
                {{ $data->customer_address }}
            </div>

            <div class="header-right">
                <h3>INVOICE</h3>
                <strong>#{{ $data->invoice_id }}</strong><br>
                {{ date('d M Y',strtotime($data->order_date)) }}
                <div class="barcode">
                    <img style="width: 120px" src="{{asset('/')}}barcode.webp" alt="">
{{--                   {!! DNS1D::getBarcodeHTML($data->invoice_id,'C128',1,30) !!}--}}
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <table>
            <thead>
            <tr>
                <th width="5%">SL</th>
                <th>Product</th>
                <th width="8%">Qty</th>
                <th width="18%" class="text-right">Price</th>
            </tr>
            </thead>
            <tbody>
            @php($i=1)
            @foreach($data->get_products as $item)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>
                        {{ $item->get_product->name }}<br>
                        @if (!empty($item->attributes))
                            <?php
                            $ids = explode('-', $item->attributes);
                            $attributes = $item->get_product
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
                                <small class="text-primary mt-1">{{ $attribute->name }}
                                    : {{ $attrItem }}
                                </small>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-right">{{ $web_settings?->currency_sign }} {{ $item->price }}</td>
                </tr>
            @endforeach
            <tr style="border-top: 1px solid black;">
                <td colspan="3" style="padding-left: 10px;text-align: right;padding-bottom: 0;">
                    <strong>Sub Total</strong>
                </td>
                <td style="text-align: right;padding-bottom: 0;">{{ $web_settings?->currency_sign }} {{ $data->sub_total }}</td>
            </tr>

            <tr style="border-top: 1px solid black;">
                <td colspan="3" style="padding-left: 10px;text-align: right;padding-bottom: 0;">
                    <strong>Delivery Cost (+)</strong>
                </td>
                <td style="text-align: right;padding-bottom: 0;">
                    {{ $web_settings?->currency_sign }} {{ $data->shipping_cost }}</td>
            </tr>

            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ $web_settings?->currency_sign }} {{ $data->total }}</td>
            </tr>
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            Courier: {{ $data->get_courier?->courier_name ?? 'N/A' }} |
            Powered by {{ $web_settings?->website_name }}
        </div>

    </div>

        @endforeach

</div>

<script>
    window.onload=function(){
        window.print();
        window.close();
    }
</script>

</body>
</html>
