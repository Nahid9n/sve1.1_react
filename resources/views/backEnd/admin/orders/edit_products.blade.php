
<tr class="product_{{ $data->id }}" data-product_id="{{ $data->id }}" data-key="{{ $key }}">
    <input type="hidden" name="product_id[]" id="product_id" class="product_id" value="{{ $data->id }}">
    <td class="text-left">
        <span class="product_sku">{{ $data->sku }}</span> <br>
        <span class="text-danger fw-bold out_of_stock text-center"></span>
    </td>
    <td>
        <span>{{ Str::limit($data->name, 45) }}</span>
        <br>
        @if (count($data->get_variants) > 0)
            @foreach ($data->get_variants as $key => $variant)
                <br>
                <small class="mb-3"
                    style="color: #7e7777; font-weight:bold;">{{ ucfirst($variant->get_variant->name) }}</small>
                <br>
                <br>
                <div class="d-flex flex-wrap">
                    @foreach ($variant->get_variant_items as $key2 => $variant_item)
                        <div class="form-check me-2">
                            <input class="form-check-input form-checked-outline attribute" type="radio"
                                name="variant[{{ $key }}][{{ $data->id }}][{{ strtolower($variant->get_variant->name) }}]"
                                id="attribute_{{ $key }}_{{ $data->id }}_{{ strtolower($variant->get_variant->name) . '_' . $key2 }}"
                                value="{{ strtolower($variant_item->name) }}" {{ $key2 == 0 ? 'checked' : '' }}>
                            <label class="form-check-label"
                                for="attribute_{{ $key }}_{{ $data->id }}_{{ strtolower($variant->get_variant->name) . '_' . $key2 }}">
                                @if ($variant_item->image)
                                    <img src="{{ asset($variant_item->image ? $variant_item->image : '') }}"
                                        alt="" width="50px">
                                @else
                                    {{ ucfirst($variant_item->name) }}
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </td>
    <td>
        <input style="width: 60px;border: 1px solid #ddd;" min="1" type="number"
            class="form-control qty auto-select-number" name="qty[]" id="qty2" value="1">
        <input type="hidden" name="price[]" id="price" class="price"
            value="{{ number_format($data->sale_price > 0 ? $data->sale_price : $data->regular_price, 2, '.', '') }}">
    </td>
    <td class="total_price">
        {{ number_format($data->sale_price > 0 ? $data->sale_price : $data->regular_price, 2, '.', '') }}
    </td>
    {{-- <td class="shipping_cost">{{ number_format($data->shipping_cost, 2) }}</td> --}}
    <td><i class="ti ti-trash icon remove_btn text-danger" style="cursor: pointer"></i></td>
</tr>
