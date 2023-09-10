
    @php
    $tax_percent = $product->product->tax_id ? $product->product->tax->tax_percent : 0;
    $tax_amount = $product->product->tax ? ($product->product->product_price / 100) * $product->product->tax->tax_percent : 0;
    @endphp
    <tr>
        <td class="serial text-start">1</td>
        <td class="text-start">
            <a class="product-name text-info" id="edit_product" href="#" tabindex="-1">{{ $product->product->name }}</a> <br/>
            <input type="{{ ($product->product->is_show_emi_on_pos == 1 ? 'text' : 'hidden') }}" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other info.">
            <input value="{{ $product->product_id }}" type="hidden" class="productId-{{ $product->product_id }}" id="product_id" name="product_ids[]">
            <input input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">
            <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ bcadd($tax_percent, 0, 2) }}">
           <input type="hidden" id="tax_type" value="{{ $product->product->tax_type }}">
            <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="{{ bcadd($tax_amount, 0, 2) }}">
            <input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">
            <input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">
            <input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">
            <input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="{{ $product->product->product_cost_with_tax }}">
            <input type="hidden" id="previous_qty" value="0.00">
            <input type="hidden" id="qty_limit" value="{{ $product->product_quantity }}">
            <input class="index-{{ $product->product->product_code }}" type="hidden" id="index">
        </td>

        <td>
            <input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">
        </td>

        <td>
            <b><span class="span_unit">{{ $product->product->unit->name }}</span></b>
            <input name="units[]" type="hidden" id="unit" value="{{ $product->product->unit->name }}">
        </td>

        <td>
            <input name="unit_prices_exc_tax[]" type="hidden" value="{{ bcadd($product->product->product_price, 0, 2) }}" id="unit_price_exc_tax">
            @php
                $unitPriceIncTax = ($product->product->product_price / 100 * $tax_percent) + $product->product->product_price;
            @endphp

            <input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax" value="{{ bcadd($unitPriceIncTax, 2) }}">

            <b><span class="span_unit_price_inc_tax">{{ bcadd($unitPriceIncTax, 0, 2) }}</span> </b>
        </td>
        <td>
            <input value="{{ bcadd($unitPriceIncTax, 2) }}" name="subtotals[]" type="hidden" id="subtotal">
            <b><span class="span_subtotal">{{ bcadd($unitPriceIncTax, 0, 2) }}</span></b>
        </td>
        <td>
            <a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash "></span></a>
        </td>
    </tr>

