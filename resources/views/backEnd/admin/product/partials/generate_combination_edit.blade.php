@php
    $setting = DB::table('web_settings')->select('stock_management')->first();
@endphp
@if (count($combinations[0]) > 0)
    <table class="table table-bordered my-2">
        <thead>
            <tr>
                <th class="text-center">Variant</th>
                <th class="text-center">SKU</th>
                <th class="text-center">Purchase Price</th>
                <th class="text-center">Regular Price</th>
                <th class="text-center">Sale Price</th>
                @if ($setting->stock_management == 0)
                    <th class="text-center">Quantity</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $combination)
                @php
                    $sku = '';
                    $str = '';

                    foreach ($combination as $attr => $item) {
                        $keySlug = strtolower(str_replace(' ', '_', $item));
                        $str .= ($str ? ' - ' : '') . "$attr: $item";
                        $sku .= ($sku ? '-' : '') . $keySlug;
                    }

                    // Variant data load (existing)
                    $data = $product->get_variants->where('sku', strtolower(str_replace(' ', '_', $sku)))->first();
                @endphp

                <tr class="variant">
                    <td class="text-center">
                        <label class="control-label">{{ $str }}</label>
                        <input type="hidden" name="variant_name[]" value="{{ $str }}">
                    </td>

                    <td>
                        <input type="text" name="variant_sku[]"
                            value="{{ $product_sku ? $product_sku . '-' . $sku : $sku }}" class="form-control" readonly>
                    </td>

                    <td>
                        <input type="number" name="variant_purchase_price[]"
                            value="{{ $data->purchase_price ?? $v_purchase_price }}" min="0" step="0.01"
                            class="form-control auto-select-number" required>
                    </td>


                    <td>
                        <input type="number" name="variant_regular_price[]"
                            value="{{ $data->regular_price ?? $v_regular_price }}" min="0" step="0.01"
                            class="form-control auto-select-number" required>
                    </td>

                    <td>
                        <input type="number" name="variant_sale_price[]"
                            value="{{ $data->sale_price ?? $v_sale_price }}" min="0" step="0.01"
                            class="form-control auto-select-number" required>
                    </td>

                    @if ($setting->stock_management == 0)
                        <td>
                            <input type="number" name="variant_stock[]" value="{{ $data->stock ?? 10 }}" min="0"
                                step="1" class="form-control auto-select-number" required>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
