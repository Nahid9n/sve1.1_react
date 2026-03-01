@php
    $setting = DB::table('web_settings')->select('stock_management')->first();
@endphp
@if (count($combinations) > 0)
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
            @foreach ($combinations as $key => $combination)
                @php
                    // Variant নাম তৈরি (Color: Red, Size: 30)
                    $variant_label_parts = [];
                    $sku_parts = [];

                    foreach ($combination as $attr_name => $item_name) {
                        $variant_label_parts[] = "{$attr_name}: {$item_name}";
                        $sku_parts[] = strtolower(str_replace(' ', '_', $item_name));
                    }

                    $variant_label = implode(', ', $variant_label_parts);
                    $sku = implode('-', $sku_parts);
                @endphp

                @if (strlen($variant_label) > 0)
                    <tr class="variant">
                        <td class="text-center">
                            <label class="control-label">{{ $variant_label }}</label>
                            <input type="hidden" name="variant_name[]" value="{{ $variant_label }}">
                        </td>

                        <td>
                            <input type="text" name="variant_sku[]"
                                value="{{ $product_sku ? $product_sku . '-' . $sku : $sku }}" class="form-control"
                                readonly>
                        </td>

                        <td>
                            <input type="number" name="variant_purchase_price[]"
                                value="{{ $v_purchase_price ?? '0.00' }}" min="0" step="0.01"
                                class="form-control" required>
                        </td>

                        <td>
                            <input type="number" name="variant_regular_price[]"
                                value="{{ $v_regular_price ?? '0.00' }}" min="0" step="0.01"
                                class="form-control" required>
                        </td>

                        <td>
                            <input type="number" name="variant_sale_price[]" value="{{ $v_sale_price ?? '0.00' }}"
                                min="0" step="0.01" class="form-control" required>
                        </td>

                        @if ($setting->stock_management == 0)
                            <td>
                                <input type="number" lang="en" name="variant_stock[]" value="10"
                                    min="0" step="1" class="form-control" required>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif
