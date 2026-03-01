@php
    $rowIndex = isset($index) ? $index : '__INDEX__';
@endphp
<tr>
    {{-- Hidden fields for backend --}}
    <input type="hidden" name="products[{{ $rowIndex }}][product_id]" value="{{ $product->id }}">
    <input type="hidden" name="products[{{ $rowIndex }}][price]"
        value="{{ number_format((float) $price, 2, '.', '') }}">
    <input type="hidden" name="products[{{ $rowIndex }}][sku]" value="{{ $product->sku ?? '' }}">
    <td class="sku"><span>{{ $product->sku ?? '—' }}</span></td>
    <td><strong>{{ $product->name }} </strong>
        <span>(Stock: {{ $product->stock }})</span>
    </td>
    <td><input type="number" name="products[{{ $rowIndex }}][qty]" class="form-control qty" value="1"
            min="1">
    </td>
    <td><input type="number" name="products[{{ $rowIndex }}][price]" class="form-control price"
            value="{{ number_format((float) $price, 2, '.', '') }}"></td>
    <td class="text-center">
        <button type="button" class="btn btn-outline-danger btn-sm remove_btn" title="Remove Item">
            <i class="ti ti-trash-x"></i>
        </button>
    </td>
    <td style="display:none" class="total_price">{{ number_format((float) $price, 2, '.', '') }}</td>
</tr>
