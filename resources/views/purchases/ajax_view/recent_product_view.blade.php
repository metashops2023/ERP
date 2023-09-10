    @php
        $tax_percent = $product->tax_id ? $product->tax->tax_percent : 0;
        $tax_amount = $product->tax ? ($product->product_cost / 100) * $product->tax->tax_percent : 0;
    @endphp

    <tr class="text-center">
        <td>
            <span class="product_name">{{ $product->name }}</span><br>
            <span class="product_code">({{ $product->product_code }})</span><br>
            <span class="product_variant"></span>
            <input value="{{ $product->id }}" type="hidden" class="productId-{{ $product->id }}" id="product_id"
                name="product_ids[]">
            <input value="noid" type="hidden" id="variant_id" name="variant_ids[]">
        </td>

        <td>
            <input value="1" required name="quantities[]" type="number" class="form-control"
                id="quantity">
            <select name="unit_names[]" id="unit_name" class="form-control mt-1">
                @foreach ($units as $unit)
                    <option {{ $unit->name == $product->unit->name ? 'SELECTED' : '' }} value="{{ $unit->name }}">
                        {{ $unit->name }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input value="{{ $product->product_cost }}" required name="unit_costs[]" type="text"
                class="form-control form-control-sm" id="unit_cost">
            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                <input name="lot_number[]" placeholder="@lang('Lot No')" type="text" class="form-control mt-1"
                    id="lot_number" value="">
            @endif
        </td>

        <td>
            <input value="0.00" required name="unit_discounts[]" type="text" class="form-control"
                id="unit_discount">
        </td>

        <td>
            <input value="{{ bcadd($product->product_cost, 0, 2) }}" required name="unit_costs_with_discount[]"
                type="text" class="form-control" id="unit_cost_with_discount">
        </td>

        <td>
            <input value="{{ bcadd($product->product_cost, 0, 2) }}" required name="subtotals[]" type="text"
                class="form-control" id="subtotal">
        </td>

        <td>
            <div class="col-md-12">
                <input readonly type="text" value="{{ bcadd($tax_percent, 0, 2) }}" name="tax_percents[]" id="tax_percent" class="form-control">
                <input type="hidden"  name="unit_taxes[]" id="unit_tax" value="{{ bcadd($tax_percent, 0, 2) }}">
            </div>
        </td>

        <td>
            <input type="hidden" value="{{ $product->product_cost_with_tax }}" name="unit_costs_inc_tax[]"
                id="unit_cost_inc_tax">
            <input value="{{ bcadd($product->product_cost_with_tax, 0, 2) }}" name="net_unit_costs[]" type="text"
                class="form-control" id="net_unit_cost">
        </td>

        <td>
            <input value="{{ bcadd($product->product_cost_with_tax, 0, 2) }}" type="text" name="linetotals[]"
                id="line_total" class="form-control">
        </td>

        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
            <td>
                <input value="{{ bcadd($product->profit, 0, 2) }}" type="text" name="profits[]"
                    class="form-control" id="profit">
            </td>

            <td>
                <input value="{{ bcadd($product->product_price, 0, 2) }}" type="text" name="selling_prices[]"
                    class="form-control" id="selling_price">
                <a href="#" id="remove_product_btn" class="btn btn-sm btn-danger mt-1">-</a>
            </td>
        @endif

       <td class="text-start">
            <a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>
        </td>
    </tr>
