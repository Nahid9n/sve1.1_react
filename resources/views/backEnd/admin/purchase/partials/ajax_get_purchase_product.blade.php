@if (count($product->get_variants) > 0)
    <tr>
        <td colspan="5">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                {{-- <th></th> --}}
                                <th>Product Name</th>
                                <th>Purchase Quantity</th>
                                <th>Purchase Cost</th>
                                <th>Regular Price</th>
                                <th>Sale Price</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->get_variants as $attribute)
                                {{-- @dd($attribute) --}}
                                <tr>
                                    {{-- <td class="w-20"><a href="javascript:void(0);"
                                            class="remove_product_attribute text-danger fs-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M18 6l-12 12" />
                                                <path d="M6 6l12 12" />
                                            </svg></a></td> --}}
                                    <td>
                                        <span class="text-primary">{{ $attribute->sku }}</span><br>
                                        {{ $product->name }}
                                        <input type="hidden" name="sku[]" value="{{ $attribute->sku }}">
                                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="purchase_quantity[]"
                                            class="form-control purchase_quantity purchase_quantity_{{ $product->id }}"
                                            id="purchase_quantity" data-id="{{ $product->id }}" value="10"
                                            required>
                                    </td>
                                    <td>
                                        <input type="number" name="purchase_cost[]"
                                            class="form-control purchase_cost purchase_cost_{{ $product->id }}"
                                            id="purchase_cost"
                                            value="{{ formatNumber($attribute->purchase_price) ?? 0 }}" step="0.01"
                                            data-id="{{ $product->id }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="regular_price[]"
                                            class="form-control regular_price regular_price_{{ $product->id }}"
                                            id="regular_price"
                                            value="{{ formatNumber($attribute->regular_price) ?? 0 }}" step="0.01"
                                            data-id="{{ $product->id }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="sell_price[]"
                                            class="form-control sell_price sell_price_{{ $product->id }}"
                                            id="sell_price" value="{{ formatNumber($attribute->sale_price) ?? 0 }}"
                                            step="0.01" data-id="{{ $product->id }}" required>

                                        <input type="hidden"
                                            class="form-control single_purchase_total  single_purchase_item_total_{{ $product->id }}"
                                            name="single_purchase_total[]" id="single_purchase_total" step="0.01"
                                            value="0" required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </td>
        <td>
            <input type="number" class="form-control single_purchase_total_{{ $product->id }}"
                id="single_purchase_total_{{ $product->id }}" step="0.01" value="0" required readonly>
        </td>
        {{-- <td class="w-1">
            <a href="javascript:void(0);" class="remove_product_btn text-danger fs-5"><i class='bx bx-x'></i></a>
        </td> --}}
    </tr>
@else
    <tr>
        <td>
            <span class="text-primary">{{ $product->sku }}</span><br>
            {{ $product->name }}
            <input type="hidden" name="sku[]" value="{{ $product->sku }}">
            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
        </td>
        <td>
            <input type="number" name="purchase_quantity[]" class="form-control purchase_quantity"
                id="purchase_quantity" value="1" required>
        </td>
        <td>
            <input type="number" name="purchase_cost[]" class="form-control purchase_cost" id="purchase_cost"
                value="{{ formatNumber($product->purchase_price) ?? 0 }}" step="0.01" required>
        </td>
        <td>
            <input type="number" name="regular_price[]" class="form-control regular_price" id="regular_price"
                value="{{ formatNumber($product->regular_price) ?? 0 }}" step="0.01" required>
        </td>

        <td>
            <input type="number" name="sell_price[]" class="form-control sell_price" id="sell_price"
                value="{{ formatNumber($product->sale_price) ?? 0 }}" step="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control single_purchase_total" name="single_purchase_total[]"
                id="single_purchase_total" step="0.01" value="0" required readonly>
        </td>
        {{-- <td>
            <a href="javascript:void(0);" class="remove_product_btn text-danger fs-5"><i class='bx bx-x'></i></a>
        </td> --}}
    </tr>
@endif
