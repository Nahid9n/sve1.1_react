@extends('backEnd.admin.layouts.master')
@section('title')
    Edit Order
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
@endpush
@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <div class="row mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Order
                            <small class="float-end">
                                <a href="{{ route('admin.orders') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>

                    </div>
                </div>
                <div class="row row-deck row-cards">
                    <!-- Page Header Close -->
                    <form action="{{ route('admin.orders.update', $data->id) }}" method="POST" id="add-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-7 col-12">
                                <div class="card custom-card mb-3">
                                    <div class="card-header bg-blue-lt">
                                        <b>Product & Summary</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <select id="product-select" class="form-select select">
                                                <option value="">--Select Product--</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-variant="{{ $product->has_variant }}"
                                                        data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url({{ asset($product->get_top_image ? $product->get_top_image->file_url : '') }})&quot;&gt;&lt;/span&gt;">
                                                        {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="table-responsive mb-md-3 mb-2">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SKU</th>
                                                        <th>Product(s)</th>
                                                        <th>Qty</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="prod_row">
                                                    @if (count($data->get_products) > 0)
                                                        {{-- @dd($data->get_products); --}}
                                                        @php $productIndex = 0; @endphp
                                                        @foreach ($data->get_products as $op)
                                                            @if ($op->attributes)
                                                                <?php
                                                                $ids = explode('-', $op->attributes);
                                                                $ids = array_map('intval', $ids);
                                                                $variant = $op->get_product->get_variants()->where('variant', $op->attributes)->first();
                                                                $variantItem = $op->get_product
                                                                    ->get_variants()
                                                                    ->with('items')
                                                                    ->get()
                                                                    ->pluck('items')
                                                                    ->flatten()
                                                                    ->whereIn('attribute_item_id', $ids)
                                                                    ->unique('attribute_id')
                                                                    ->values()
                                                                    ->map(function ($item) {
                                                                        return $item->name;
                                                                    })
                                                                    ->implode(', ');
                                                                // dd($variant);
                                                                ?>
                                                                @include(
                                                                    'backEnd.admin.orders.partials.product_row_variant',
                                                                    [
                                                                        'product' => $op->get_product,
                                                                        'sku' => $op->product_sku,
                                                                        'qty' => $op->qty,
                                                                        'price' => $op->price,
                                                                        'variant_name' => $variantItem,
                                                                        'variant_choice' => implode(',', $ids),
                                                                        'index' => $productIndex++,
                                                                        'stock' => $variant->stock,
                                                                    ]
                                                                )
                                                            @else
                                                                {{-- Non-Variant Product --}}
                                                                @include(
                                                                    'backEnd.admin.orders.partials.product_row',
                                                                    [
                                                                        'product' => $op->get_product,
                                                                        'qty' => $op->qty,
                                                                        'price' => $op->price,
                                                                        'index' => $productIndex++,
                                                                    ]
                                                                )
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <h3 class="shipping_method_title mb-3">Shipping Method</h3>
                                                @foreach ($shipping_methods as $key => $shipping_method)
                                                    <label class="form-check form-check-inline form-label">
                                                        <input type="hidden" class="shipping_amount"
                                                            value="{{ $shipping_method->amount }}">
                                                        <input class="form-check-input shipping_method"
                                                            value="{{ $shipping_method->id }}" name="shipping_method_id"
                                                            type="radio"
                                                            {{ $shipping_method->id == $data->shipping_method ? 'checked' : '' }}>
                                                        <span class="form-check-label">{{ $shipping_method->text }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Sub Total</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="sub_total"
                                                            id="sub_total"
                                                            value="{{ number_format($data->sub_total, 2, '.', '') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Delivery Cost (+)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control auto-select-number"
                                                            name="delivery_cost" id="delivery_cost"
                                                            value="{{ number_format($data->shipping_cost, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Discount (-)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                            class="form-control auto-select-number discount" name="discount"
                                                            id="discount"
                                                            value="{{ number_format($data->discount, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Coupon Discount (-)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number"
                                                            class="form-control auto-select-number discount" name="coupon_discount"
                                                            id="coupon_discount"
                                                            value="{{ number_format($data->coupon_discount, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Grand Total</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="grand_total"
                                                            id="grand_total" readonly=""
                                                            value="{{ number_format($data->total, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Paid (-)</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="paid_amount"
                                                            id="paid_amount" min = "0"
                                                            value="{{ number_format($data->paid, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 text-end">
                                                        <label class="col-form-label" for="">Due</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="due_amount"
                                                            id="due_amount" readonly=""
                                                            value="{{ number_format($data->due, 2, '.', '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($data->get_activities->count() > 0)
                                    <div class="card custom-card mb-3">
                                        <div class="card-header justify-content-between">
                                            <div class="card-title">
                                                Order Activities
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mb-md-3 mb-2">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                {{-- <th>Store Name</th> --}}
                                                                <th>Activity</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data->get_activities as $activity)
                                                                <tr>
                                                                    {{-- <td>
                                                                        {{ $activity->get_store ? $activity->get_store->name : 'N/A' }}
                                                                    </td> --}}

                                                                    <td>
                                                                        {{ $activity->text }}
                                                                        @if ($activity->activity_type == 2)
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary btn-sm"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#changeModal_{{ $activity->id }}">
                                                                                View More
                                                                            </a>
                                                                            <div class="modal fade"
                                                                                id="changeModal_{{ $activity->id }}"
                                                                                tabindex="-1"
                                                                                aria-labelledby="changeModalLabel_{{ $activity->id }}"
                                                                                aria-hidden="true">
                                                                                <div
                                                                                    class="modal-dialog modal-xl modal-dialog-centered">
                                                                                    <div class="modal-content">
                                                                                        <div
                                                                                            class="modal-header bg-primary text-white">
                                                                                            <h5 class="modal-title"
                                                                                                id="changeModalLabel_{{ $activity->id }}">
                                                                                                Order Data Comparison
                                                                                            </h5>
                                                                                            <button type="button"
                                                                                                class="btn-close btn-close-white"
                                                                                                data-bs-dismiss="modal"
                                                                                                aria-label="Close"></button>
                                                                                        </div>

                                                                                        <div class="modal-body">
                                                                                            <div class="row g-3">
                                                                                                <div class="col-md-6">
                                                                                                    <div
                                                                                                        class="card shadow-sm border-0 h-100">
                                                                                                        <div
                                                                                                            class="card-header bg-danger-lt fw-bold">
                                                                                                            Old Data</div>
                                                                                                        <div class="card-body"
                                                                                                            style="max-height: 400px; overflow-y: auto;">
                                                                                                            @php
                                                                                                                $old =
                                                                                                                    $activity->old_order ??
                                                                                                                    [];
                                                                                                                $oldProducts = $activity->getOldProductDetails();
                                                                                                                // dd(
                                                                                                                //     $oldProducts,
                                                                                                                // );
                                                                                                            @endphp

                                                                                                            <table
                                                                                                                class="table table-sm table-bordered mb-2">
                                                                                                                <thead>
                                                                                                                    <tr>
                                                                                                                        <th>Product
                                                                                                                            Name
                                                                                                                        </th>
                                                                                                                        <th>Qty
                                                                                                                        </th>
                                                                                                                        <th>Price
                                                                                                                        </th>
                                                                                                                        <th>Sub
                                                                                                                            Total
                                                                                                                        </th>
                                                                                                                    </tr>
                                                                                                                </thead>
                                                                                                                <tbody>
                                                                                                                    @forelse ($oldProducts as $prod)
                                                                                                                        <?php
                                                                                                                        $name = DB::table('products')->where('id', $prod['product_id'])->first();
                                                                                                                        // dd($name);
                                                                                                                        ?>
                                                                                                                        <tr>
                                                                                                                            <td>{{ $name->name ?? 'N/A' }}
                                                                                                                                @if (isset($prod['variant_name']) && !empty($prod['variant_name']))
                                                                                                                                    ({{ $prod['variant_name'] }})
                                                                                                                                @endif

                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['qty'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['price'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['qty'] * $prod['price'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @empty
                                                                                                                        <tr>
                                                                                                                            <td colspan="4"
                                                                                                                                class="text-center text-muted">
                                                                                                                                No
                                                                                                                                old
                                                                                                                                product
                                                                                                                                data
                                                                                                                                found
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforelse
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <table
                                                                                                                class="table table-sm table-bordered mb-0">
                                                                                                                <tbody>
                                                                                                                    @foreach ($old as $key => $value)
                                                                                                                        @continue($key == 'changes_fields' || in_array($key, ['products', 'qty', 'price']))

                                                                                                                        @php
                                                                                                                            $displayValue = $value;
                                                                                                                            $displayValue = match (
                                                                                                                                $key
                                                                                                                            ) {
                                                                                                                                'courier_id'
                                                                                                                                    => \App\Courier::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'courier_city_id'
                                                                                                                                    => \App\PathaoCity::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'courier_zone_id'
                                                                                                                                    => \App\PathaoZone::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'status'
                                                                                                                                    => match (
                                                                                                                                    (int) $value
                                                                                                                                ) {
                                                                                                                                    1
                                                                                                                                        => 'Pending',
                                                                                                                                    2
                                                                                                                                        => 'Confirm',
                                                                                                                                    3
                                                                                                                                        => 'Processing',
                                                                                                                                    4
                                                                                                                                        => 'Hold',
                                                                                                                                    5
                                                                                                                                        => 'Printed',
                                                                                                                                    6
                                                                                                                                        => 'Packaging',
                                                                                                                                    7
                                                                                                                                        => 'Courier Entry',
                                                                                                                                    8
                                                                                                                                        => 'On Delivery',
                                                                                                                                    9
                                                                                                                                        => 'Delivered',
                                                                                                                                    10
                                                                                                                                        => 'Cancelled',
                                                                                                                                    11
                                                                                                                                        => 'Returned',
                                                                                                                                    default
                                                                                                                                        => 'Unknown',
                                                                                                                                },
                                                                                                                                default
                                                                                                                                    => $value,
                                                                                                                            };
                                                                                                                        @endphp

                                                                                                                        <tr>
                                                                                                                            <th
                                                                                                                                class="text-muted text-capitalize">
                                                                                                                                {{ str_replace('_', ' ', $key) }}
                                                                                                                            </th>
                                                                                                                            <td>
                                                                                                                                {{ is_array($displayValue) ? json_encode($displayValue) : $displayValue }}
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div
                                                                                                        class="card shadow-sm border-0 h-100">
                                                                                                        <div
                                                                                                            class="card-header bg-success-lt fw-bold">
                                                                                                            New Data</div>
                                                                                                        <div class="card-body"
                                                                                                            style="max-height: 400px; overflow-y: auto;">
                                                                                                            @php
                                                                                                                $new =
                                                                                                                    $activity->new_order ??
                                                                                                                    [];
                                                                                                                $changes =
                                                                                                                    $new[
                                                                                                                        'changes_fields'
                                                                                                                    ] ??
                                                                                                                    [];
                                                                                                                $newProducts = $activity->getNewProductDetails();

                                                                                                            @endphp

                                                                                                            <table
                                                                                                                class="table table-sm table-bordered mb-2">
                                                                                                                <thead>
                                                                                                                    <tr>
                                                                                                                        <th>Product
                                                                                                                            Name
                                                                                                                        </th>
                                                                                                                        <th>Qty
                                                                                                                        </th>
                                                                                                                        <th>Price
                                                                                                                        </th>
                                                                                                                        <th>Sub
                                                                                                                            Total
                                                                                                                        </th>
                                                                                                                    </tr>
                                                                                                                </thead>
                                                                                                                <tbody>
                                                                                                                    @forelse ($newProducts as $prod)
                                                                                                                        <?php
                                                                                                                        $name = DB::table('products')->where('id', $prod['product_id'])->first();
                                                                                                                        ?>
                                                                                                                        <tr
                                                                                                                            @if (in_array("product_{$prod['product_id']}_qty", $changes) ||
                                                                                                                                    in_array("product_{$prod['product_id']}_price", $changes)) class="table-warning" @endif>
                                                                                                                            <td>{{ $name->name ?? 'N/A' }}
                                                                                                                                @if (isset($prod['variant_name']) && !empty($prod['variant_name']))
                                                                                                                                    ({{ $prod['variant_name'] }})
                                                                                                                                @endif
                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['qty'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['price'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                            <td>{{ $prod['qty'] * $prod['price'] ?? 'N/A' }}
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @empty
                                                                                                                        <tr>
                                                                                                                            <td colspan="4"
                                                                                                                                class="text-center text-muted">
                                                                                                                                No
                                                                                                                                new
                                                                                                                                product
                                                                                                                                data
                                                                                                                                found
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforelse
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <table
                                                                                                                class="table table-sm table-bordered mb-0">
                                                                                                                <tbody>
                                                                                                                    @foreach ($new as $key => $value)
                                                                                                                        @continue($key == 'changes_fields' || in_array($key, ['products', 'qty', 'price']))

                                                                                                                        @php
                                                                                                                            $displayValue = match (
                                                                                                                                $key
                                                                                                                            ) {
                                                                                                                                'courier_id'
                                                                                                                                    => \App\Courier::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'courier_city_id'
                                                                                                                                    => \App\PathaoCity::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'courier_zone_id'
                                                                                                                                    => \App\PathaoZone::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'assigns_id'
                                                                                                                                    => \App\User::find(
                                                                                                                                    $value,
                                                                                                                                )
                                                                                                                                    ?->name ??
                                                                                                                                    'N/A',
                                                                                                                                'status'
                                                                                                                                    => match (
                                                                                                                                    (int) $value
                                                                                                                                ) {
                                                                                                                                    1
                                                                                                                                        => 'Pending',
                                                                                                                                    2
                                                                                                                                        => 'Confirm',
                                                                                                                                    3
                                                                                                                                        => 'Processing',
                                                                                                                                    4
                                                                                                                                        => 'Hold',
                                                                                                                                    5
                                                                                                                                        => 'Printed',
                                                                                                                                    6
                                                                                                                                        => 'Packaging',
                                                                                                                                    7
                                                                                                                                        => 'Courier Entry',
                                                                                                                                    8
                                                                                                                                        => 'On Delivery',
                                                                                                                                    9
                                                                                                                                        => 'Delivered',
                                                                                                                                    10
                                                                                                                                        => 'Cancelled',
                                                                                                                                    11
                                                                                                                                        => 'Returned',
                                                                                                                                    default
                                                                                                                                        => 'Unknown',
                                                                                                                                },
                                                                                                                                default
                                                                                                                                    => $value,
                                                                                                                            };

                                                                                                                            if (
                                                                                                                                empty(
                                                                                                                                    $displayValue
                                                                                                                                ) &&
                                                                                                                                $displayValue !==
                                                                                                                                    '0'
                                                                                                                            ) {
                                                                                                                                $displayValue =
                                                                                                                                    'N/A';
                                                                                                                            }
                                                                                                                        @endphp

                                                                                                                        <tr
                                                                                                                            @if (in_array($key, $changes)) class="table-warning" @endif>
                                                                                                                            <th
                                                                                                                                class="text-muted text-capitalize">
                                                                                                                                {{ str_replace('_', ' ', $key) }}
                                                                                                                            </th>
                                                                                                                            <td>
                                                                                                                                {{ is_array($displayValue) ? json_encode($displayValue) : $displayValue }}
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    @endforeach
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
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $activity->created_at->format('d M, Y h:i A') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card custom-card mb-3">
                                        <div class="card-body text-center">
                                            <p class="text-muted">No Activities Found</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="modal-title">All Notes</h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if (!empty($data->get_notes))
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>SL</th>
                                                                    <th>Note</th>
                                                                    <th>Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($data->get_notes as $key => $note)
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ $note->note . ' (' . ucfirst($note->user_type) . ')' }}
                                                                        </td>
                                                                        <td>{{ $note->created_at }}</td>

                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                @else
                                                    <p>No Notes Found</p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="col-md-5">
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Order & Customer Information <small>#{{ $data->order_no }}</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="price">Order Date</label>
                                                <input type="text" class="form-control"
                                                    value="{{ date('d-m-Y', strtotime(now())) }}" name="order_date"
                                                    id="order_date" autocomplete="off" required>
                                            </div>

                                            <div class="col">
                                                <label class="form-label" for="memo_no">Memo Number</label>
                                                <input type="text" value="{{ $data->memo_no }}" class="form-control"
                                                    name="custom_order_no">
                                            </div>
                                            <div class="col">
                                                <label class="form-label" for="invoice_id">Invoice ID</label>
                                                <input type="text" class="form-control" name="invoice_id"
                                                    value="{{ $data->invoice_id }}" readonly>

                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="name">Customer Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $data->customer_name }}" name="name" id="name"
                                                    autocomplete="off" required>
                                            </div>

                                            <div class="col">
                                                <label class="form-label required" for="phone">Customer Phone</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $data->customer_phone }}" name="phone" id="phone"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label required" for="address">Customer Address</label>
                                                <textarea name="address" id="address" class="form-control" required>{{ $data->customer_address }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Courier
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            {{-- COURIER --}}
                                            <div class="col-12 mb-3">
                                                <label class="form-label" for="courier_id">Select Courier</label>
                                                <select name="courier_id" id="courier_id" class="form-select">
                                                    <option value="">Select Courier</option>
                                                    @foreach (DB::table('couriers')->get() as $courier)
                                                        <option value="{{ $courier->id }}"
                                                            {{ $courier->id == $data->courier_id ? 'selected' : '' }}>
                                                            {{ $courier->courier_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="city_zone_wrapper d-none">
                                                <input type="hidden" name="selected_zone_id" id="selected_zone_id"
                                                    value="{{ $data->courier_zone_id }}">
                                                <label>City / Zone</label>
                                                <select id="courier_city_zone" class="form-control select2"
                                                    name="courier_zone_id">
                                                    <option value="">Select City → Zone</option>
                                                </select>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                                <div class="card custom-card mb-3">
                                    <div class="card-header justify-content-between">
                                        <div class="card-title">
                                            Action
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="status">Order Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                                        Pending </option>
                                                    <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>
                                                        Confirm</option>
                                                    <option value="3" {{ $data->status == 3 ? 'selected' : '' }}>
                                                        Processing</option>
                                                    <option value="4" {{ $data->status == 4 ? 'selected' : '' }}>Hold
                                                    </option>
                                                    <option value="5" {{ $data->status == 5 ? 'selected' : '' }}>
                                                        Printed</option>
                                                    <option value="6" {{ $data->status == 6 ? 'selected' : '' }}>
                                                        Packaging</option>
                                                    <option value="7" {{ $data->status == 7 ? 'selected' : '' }}>
                                                        Courier Entry</option>
                                                    <option value="8"{{ $data->status == 8 ? 'selected' : '' }}>On
                                                        Delivery</option>
                                                    <option value="9"{{ $data->status == 9 ? 'selected' : '' }}>
                                                        Delivered</option>
                                                    <option value="10"{{ $data->status == 10 ? 'selected' : '' }}>
                                                        Cancelled</option>
                                                    <option value="11"{{ $data->status == 11 ? 'selected' : '' }}>
                                                        Returned</option>
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <label class="form-label" for="is_paid">Payment Status</label>
                                                <select name="is_paid" id="is_paid" class="form-select">
                                                    <option value="0" {{ $data->is_paid == 0 ? 'selected' : '' }}>
                                                        Unpaid
                                                    </option>
                                                    <option value="1" {{ $data->is_paid == 1 ? 'selected' : '' }}>
                                                        Paid
                                                    </option>
                                                    <option value="2" {{ $data->is_paid == 2 ? 'selected' : '' }}>
                                                        Partial
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <label class="form-label" for="source">Source
                                                </label>
                                                <select name="source" id="source" class="form-select"
                                                    {{ $data->source == 'direct' ? 'readonly' : '' }} required="">
                                                    {{-- <option value="">Select Source</option> --}}
                                                    @if ($data->source == 'direct')
                                                        <option value="direct" selected>Direct</option>
                                                    @else
                                                        <option value="page"
                                                            {{ $data->source == 'page' ? 'selected' : '' }}>Page
                                                        </option>
                                                        <option value="whatsapp"
                                                            {{ $data->source == 'whatsapp' ? 'selected' : '' }}>
                                                            Whatsapp
                                                        </option>
                                                        <option value="ab_cart"
                                                            {{ $data->source == 'ab_cart' ? 'selected' : '' }}>AB
                                                            Cart
                                                        </option>
                                                        <option value="call"
                                                            {{ $data->source == 'call' ? 'selected' : '' }}>Call
                                                        </option>
                                                        <option value="office_sell"
                                                            {{ $data->source == 'office_sell' ? 'selected' : '' }}>Office
                                                            Sell
                                                        </option>
                                                        <option value="instagram"
                                                            {{ $data->source == 'instagram' ? 'selected' : '' }}>Instagram
                                                        </option>
                                                        <option value="lp"
                                                            {{ $data->source == 'lp' ? 'selected' : '' }}>Landing Page
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Product Variant -->
    <div class="modal fade" id="productVariant" tabindex="-1" role="dialog" aria-labelledby="productVariantLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2">
                        Select Product Variant
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="variantOptions">
                        <!-- Variant options will be dynamically loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveVariantSelection">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for view more -->
    <div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#product-select').select2();
            // $('#courier_id').select2();
            $('#courier_city').select2({
                placeholder: 'Select city'
            });
            $('#courier_zone').select2({
                placeholder: 'Select zone'
            });

        });

        $(function() {


            // 🔸 Start index from existing rows count
            let productIndex = $('#prod_row tr').length;

            // 🔹 Calculate totals
            function finalCalc() {
                let subTotal = 0;
                $('#prod_row tr').each(function() {
                    let qty = parseFloat($(this).find('.qty').val() || 0);
                    let price = parseFloat($(this).find('.price').val() || 0);
                    subTotal += qty * price;
                    $(this).find('.total_price').text((qty * price).toFixed(2));
                });

                $('#sub_total').val(subTotal.toFixed(2));
                let delivery = parseFloat($('#delivery_cost').val() || 0);
                let discount = parseFloat($('#discount').val() || 0);
                let coupon_discount = parseFloat($('#coupon_discount').val() || 0);
                let grand = subTotal + delivery - discount - coupon_discount;
                $('#grand_total').val(grand.toFixed(2));

                let paid = parseFloat($('#paid_amount').val() || 0);
                $('#due_amount').val((grand - paid).toFixed(2));
            }

            // 🔹 Append new product row (replace placeholder index)
            function appendProductRow(rowHtml) {
                rowHtml = rowHtml.replace(/__INDEX__/g, productIndex);
                $('#prod_row').append(rowHtml);
                productIndex++;
                finalCalc();
            }

            // 🔹 Product selection
            $('#product-select').on('change', function() {
                let $opt = $(this).find(':selected');
                let product_id = $opt.val();
                if (!product_id) return;
                let has_variant = $opt.data('variant');
                let CSRF = "{{ csrf_token() }}";

                if (has_variant) {
                    $.post("{{ route('admin.ajax.get.product.modal') }}", {
                        _token: CSRF,
                        id: product_id
                    }, function(html) {
                        $('#variantOptions').html(html);
                        $('#productVariant').removeData('editing-row').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).modal('show');
                    });
                } else {
                    $.post("{{ route('admin.ajax.get.products') }}", {
                        _token: CSRF,
                        id: product_id
                    }, function(rowHtml) {
                        appendProductRow(rowHtml);
                    });
                }
            });

            // 🔹 Edit variant button click
            $(document).on('click', '.edit_variant', function() {
                let row = $(this).closest('tr');
                let product_id = row.data('product-id');
                let choice_str = row.find('.variant_choice').val() || '';
                let selectedChoices = choice_str ? choice_str.split(',') : [];
                let CSRF = "{{ csrf_token() }}";

                $.post("{{ route('admin.ajax.get.product.modal') }}", {
                    _token: CSRF,
                    id: product_id,
                    choice_variants: selectedChoices
                }, function(html) {
                    $('#variantOptions').html(html);
                    $('#productVariant').data('editing-row', row).modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                });
            });

            // 🔹 Save or update variant from modal
            $('#productVariant').on('click', '#saveVariantSelection', function() {
                let product_id = $('#variant_product_id').val();
                let qty = parseInt($('#modal_qty').val() || 1);
                let choice = [];
                $('#variantSelectionForm').find('input[type=radio]:checked').each(function() {
                    choice.push($(this).val());
                });

                let choice_key = choice.join(',');
                let editingRow = $('#productVariant').data('editing-row');
                let CSRF = "{{ csrf_token() }}";

                // ✅ Edit mode with duplicate check
                if (editingRow) {
                    let duplicateRow = null;
                    $('#prod_row tr').each(function() {
                        let rowProductId = $(this).data('product-id');
                        let rowChoice = $(this).find('.variant_choice').val();
                        // Ignore the currently editing row itself
                        if (rowProductId == product_id && rowChoice == choice_key && this !==
                            editingRow[0]) {
                            duplicateRow = $(this);
                            return false;
                        }
                    });

                    if (duplicateRow) {
                        // Add quantity to the existing duplicate row
                        let currentQty = parseInt(duplicateRow.find('.qty').val() || 1);
                        duplicateRow.find('.qty').val(currentQty + qty).trigger('change');
                        alert('Variant already exists in another row, quantity updated!');
                        $('#productVariant').removeData('editing-row').modal('hide');
                        return;
                    }

                    // ✅ No duplicate, update the editing row normally
                    $.post("{{ route('admin.ajax.get.modal.variant') }}", {
                        _token: CSRF,
                        id: product_id,
                        choice_variants: choice,
                        qty: qty,
                        modal: 1
                    }, function(rowHtml) {
                        let idx = editingRow.find('input[name^="products"]').attr('name').match(
                            /\d+/)[0];
                        rowHtml = rowHtml.replace(/__INDEX__/g, idx);
                        editingRow.replaceWith(rowHtml);
                        $('#productVariant').removeData('editing-row');
                        finalCalc();
                    });
                    return;
                }

                // ✅ New row duplicate check (existing logic)
                let existingRow = null;
                $('#prod_row tr').each(function() {
                    let rowProductId = $(this).data('product-id');
                    let rowChoice = $(this).find('.variant_choice').val();
                    if (rowProductId == product_id && rowChoice == choice_key) {
                        existingRow = $(this);
                        return false;
                    }
                });

                if (existingRow) {
                    let currentQty = parseInt(existingRow.find('.qty').val() || 1);
                    existingRow.find('.qty').val(currentQty + qty).trigger('change');
                    alert('Variant already exists, quantity updated!');
                    $('#productVariant').modal('hide');
                    return;
                }

                // ✅ Add new variant row
                $.post("{{ route('admin.ajax.get.modal.variant') }}", {
                    _token: CSRF,
                    id: product_id,
                    choice_variants: choice,
                    qty: qty,
                    modal: 1
                }, function(rowHtml) {
                    appendProductRow(rowHtml);
                });
            });


            // 🔹 Remove row
            $(document).on('click', '.remove_btn', function() {
                $(this).closest('tr').remove();
                finalCalc();
            });

            // 🔹 Qty / Price change
            $(document).on('keyup change', '.qty,.price,.discount, #paid_amount, #delivery_cost, #coupon_discount', function() {
                finalCalc();
            });

            $(document).on('change', '.shipping_method', function() {
                let amount = $(this).parent().find('.shipping_amount').val();
                $('#delivery_cost').val($(this).parent().find('.shipping_amount').val());
                finalCalc();
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedZone = $('#selected_zone_id').val(); // hidden input old value
            function loadCityZone(courier_id) {
                let allowed = [2, 3, 4];
                $('.city_zone_wrapper').addClass('d-none');
                if (!allowed.includes(parseInt(courier_id))) {
                    $('#courier_city_zone').html('<option value="">Select City → Zone</option>');
                    return;
                }
                $('.city_zone_wrapper').removeClass('d-none');
                $('#courier_city_zone').html('<option value="">Loading...</option>');
                $.ajax({
                    url: "{{ route('admin.ajax.get.courier.cities') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        courier_id: courier_id
                    },
                    success: function(response) {
                        if (response.success) {

                            $('#courier_city_zone').html(
                                '<option value="">Select City → Zone</option>');

                            if (response.courier_id == 2) { // Pathao
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.zone_id}">${item.city_name} → ${item.zone_name}</option>`
                                    );
                                });
                            } else if (response.courier_id == 3) { // RedX
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.parent_id}">${item.district} → ${item.name}</option>`
                                    );
                                });
                            } else if (response.courier_id == 4) { // CarryBee
                                $.each(response.data, function(index, item) {
                                    $('#courier_city_zone').append(
                                        `<option value="${item.zone_id}">${item.city_name} → ${item.zone_name}</option>`
                                    );
                                });
                            }

                            // Re-init Select2
                            $('#courier_city_zone').select2('destroy').select2();

                            // Auto-focus search box
                            setTimeout(() => {
                                const searchInput = document.querySelector(
                                    '.select2-container--open .select2-search__field');
                                if (searchInput) searchInput.focus();
                            }, 10);

                            // Auto-select old value on edit
                            if (selectedZone) {
                                $('#courier_city_zone').val(selectedZone).trigger('change.select2');
                            }
                        }
                    }
                });
            }

            // On page load (Edit)
            let oldCourier = $('#courier_id').val();
            if (oldCourier) {
                loadCityZone(oldCourier);
            }

            // On courier change
            $(document).on('change', '#courier_id', function() {
                let courier_id = $(this).val();
                selectedZone = null; // reset when changing courier
                loadCityZone(courier_id);
            });

        });
    </script>
@endpush
