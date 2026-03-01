<form id="variantSelectionForm">
    <input type="hidden" id="variant_product_id" value="{{ $product->id }}">
    <div class="mb-2">
        <label>Quantity</label>
        <input type="number" id="modal_qty" value="1" min="1" class="form-control">
    </div>

    @foreach ($product->get_attribute_with_items() as $attribute_item)
        {{-- @dd($attribute_item) --}}
        <?php
        $attribute = $attribute_item['attribute'];
        $items = $attribute_item['items'];
        ?>

        <div class="mb-3">
            <div><strong>{{ $attribute->name }}</strong></div>
            <div class="d-flex flex-wrap">
                @foreach ($items as $item)
                    <label class="me-2">
                        <input type="radio" name="attribute_{{ $item->attribute_id }}" class="attr_radio"
                            value="{{ $item->attribute_item_id }}"
                            @if (empty($selected) && $loop->first) checked
            @elseif(!empty($selected) && in_array($item->attribute_item_id, $selected))
                checked @endif>
                        {{ $item->name }}
                    </label>
                @endforeach

            </div>
        </div>
    @endforeach
</form>
