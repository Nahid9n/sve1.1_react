@extends('backEnd.admin.layouts.master')

@section('title')
    Order Activity
@endsection


@section('css')
@endsection
@section('content')
    <div class="page-body m-0">
        <div class="container-xl">
            <div class="row" style="padding-top: 24px">
                <div class="col-12 m-0">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center p-2">
                            <h5 class="card-title">
                                Order Activity
                            </h5>
                            <div class="card-action">
                                <a href="{{ route('admin.orders') }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-angle-double-left"></i>
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </div>
                        </div>


                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-header bg-danger-lt fw-bold">
                                        Old Data</div>
                                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                        @php
                                            $old = $activity->old_order ?? [];
                                            $oldProducts = $activity->getOldProductDetails();
                                            // dd(
                                            //     $oldProducts,
                                            // );
                                        @endphp

                                        <table class="table table-sm table-bordered mb-2">
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
                                                        <td colspan="4" class="text-center text-muted">
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

                                        <table class="table table-sm table-bordered mb-0">
                                            <tbody>
                                                @foreach ($old as $key => $value)
                                                    @continue($key == 'changes_fields' || in_array($key, ['products', 'qty', 'price']))

                                                    @php
                                                        $displayValue = $value;
                                                        $displayValue = match ($key) {
                                                            'courier_id' => \App\Courier::find($value)?->name ?? 'N/A',
                                                            'courier_city_id' => \App\PathaoCity::find($value)?->name ??
                                                                'N/A',
                                                            'courier_zone_id' => \App\PathaoZone::find($value)?->name ??
                                                                'N/A',
                                                            'status' => match ((int) $value) {
                                                                1 => 'Pending',
                                                                2 => 'Confirm',
                                                                3 => 'Processing',
                                                                4 => 'Hold',
                                                                5 => 'Printed',
                                                                6 => 'Packaging',
                                                                7 => 'On Delivery',
                                                                8 => 'Delivered',
                                                                9 => 'Cancelled',
                                                                10 => 'Returned',
                                                                default => 'Unknown',
                                                            },
                                                            default => $value,
                                                        };
                                                    @endphp

                                                    <tr>
                                                        <th class="text-muted text-capitalize">
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
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-header bg-success-lt fw-bold">
                                        New Data</div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        @php
                                            $new = $activity->new_order ?? [];
                                            $changes = $new['changes_fields'] ?? [];
                                            $newProducts = $activity->getNewProductDetails();

                                        @endphp

                                        <table class="table table-sm table-bordered mb-2">
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
                                                    <tr @if (in_array("product_{$prod['product_id']}_qty", $changes) ||
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
                                                        <td colspan="4" class="text-center text-muted">
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

                                        <table class="table table-sm table-bordered mb-0">
                                            <tbody>
                                                @foreach ($new as $key => $value)
                                                    @continue($key == 'changes_fields' || in_array($key, ['products', 'qty', 'price']))

                                                    @php
                                                        $displayValue = match ($key) {
                                                            'courier_id' => \App\Courier::find($value)?->name ?? 'N/A',
                                                            'courier_city_id' => \App\PathaoCity::find($value)?->name ??
                                                                'N/A',
                                                            'courier_zone_id' => \App\PathaoZone::find($value)?->name ??
                                                                'N/A',
                                                            'assigns_id' => \App\User::find($value)?->name ?? 'N/A',
                                                            'status' => match ((int) $value) {
                                                                1 => 'Pending',
                                                                2 => 'Confirm',
                                                                3 => 'Processing',
                                                                4 => 'Hold',
                                                                5 => 'Printed',
                                                                6 => 'Packaging',
                                                                7 => 'On Delivery',
                                                                8 => 'Delivered',
                                                                9 => 'Cancelled',
                                                                10 => 'Returned',
                                                                default => 'Unknown',
                                                            },
                                                            default => $value,
                                                        };

                                                        if (empty($displayValue) && $displayValue !== '0') {
                                                            $displayValue = 'N/A';
                                                        }
                                                    @endphp

                                                    <tr @if (in_array($key, $changes)) class="table-warning" @endif>
                                                        <th class="text-muted text-capitalize">
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
    </div>
@endsection
