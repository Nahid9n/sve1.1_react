@php
    // determine index type: from controller (edit) or placeholder (create)
    $rowIndex = isset($index) ? $index : '__INDEX__';
@endphp
<tr data-product-id="{{ $product->id }}">
    <td class="sku"><span>{{ $sku ?? '—' }}</span></td>
    <td>
        <strong>{{ $product->name }}</strong>
        <div>
            <small class="variant-label">{{ $variant_name ?? '' }} (Stock: {{ $stock ?? 0 }})</small><br>
            @if ($product->free_shipping == 1)
                <small class="badge bg-primary text-white" style="font-size: 9px;">Free Shipping</small>
            @endif
        </div>

        <input type="hidden" class="variant_choice" value="{{ $variant_choice ?? '' }}">
        <input type="hidden" class="sku_val" value="{{ $sku ?? '' }}">
        <input type="hidden" name="products[{{ $rowIndex }}][product_id]" value="{{ $product->id }}">
        <input type="hidden" name="products[{{ $rowIndex }}][sku]" value="{{ $sku ?? '' }}">
        <input type="hidden" name="products[{{ $rowIndex }}][variant_name]" value="{{ $variant_name ?? '' }}">
        <input type="hidden" name="products[{{ $rowIndex }}][variant_choice]"
            value="{{ $variant_choice ?? '' }}">
    </td>
    <td><input type="number" name="products[{{ $rowIndex }}][qty]" class="form-control qty"
            value="{{ $qty ?? 1 }}" min="1"></td>
    <td><input type="number" name="products[{{ $rowIndex }}][price]" class="form-control price"
            value="{{ number_format((float) $price, 2, '.', '') }}"></td>
    <td class="text-center" style="display:flex;gap:5px;flex-direction:column;">
        <button type="button" class="btn btn btn-cyan border-0 btn-sm d-flex justify-content-center gap-1 edit_variant"
            title="Edit Variant">
            <i class="ti ti-edit"></i>
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm remove_btn" title="Remove Item">
            <i class="ti ti-trash-x"></i>
        </button>
    </td>
    <td style="display:none" class="total_price">{{ number_format((float) $price * ($qty ?? 1), 2, '.', '') }}</td>
</tr>
