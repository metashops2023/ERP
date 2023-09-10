@php
    $tax_percent = $product->product->tax_id ? $product->product->tax->tax_percent : 0;
    $tax_amount = $product->product->tax ? $product->product->product_price/100 * $product->product->tax->tax_percent : 0;
@endphp
<tr>
    <td colspan="2" class="text-start">
        <a href="#" class="text-success" id="edit_product">
        <span class="product_name">{{ $product->product->name }}</span>
        <span class="product_variant"></span>
        <span class="product_code">({{ $product->product->product_code }})</span>
        </a><br/>
        <input type="{{ ($product->product->is_show_emi_on_pos == 1 ? 'text' : 'hidden') }}" name="descriptions[]" class="form-control scanable mb-1" placeholder="@lang('IMEI, Serial number or other info.')">
        <input value="{{ $product->product_id }}" type="hidden" class="productId-{{ $product->product_id }}" id="product_id" name="product_ids[]">
        <input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">
        <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ bcadd($tax_percent, 0, 2) }}">
        <input type="hidden" id="tax_type" value="{{ $product->product->tax_type }}">
        <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="{{ bcadd($tax_amount, 0, 2) }}">
        <input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">
        <input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">
        <input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">
        <input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="{{ $product->product->product_cost_with_tax }}">
        <input value="0.00" type="hidden" id="previous_quantity">
        <input type="hidden" id="qty_limit" value="{{ $product->product_quantity }}">
    </td>

    <td>
        <div class="input-group">
            <div class="input-group-prepend">
                <a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>
            </div>
            <input value="1.00" required name="quantities[]" type="text" class="form-control text-center " id="quantity">
            <div class="input-group-prepend">
                <a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>
            </div>
        </div>
    </td>

    <td class="text">
        <b><span class="span_unit">{{ $product->product->unit->name }}</span></b>
        <input  name="units[]" type="hidden" id="unit" value="{{ $product->product->unit->name }}">
    </td>

    <td>
        <input readonly name="unit_prices_exc_tax[]" type="hidden"  id="unit_price_exc_tax" value="{{ $product->product->product_price }}">
        @php
            $unitPriceIncTax = $product->product->product_price / 100 * $tax_percent + $product->product->product_price;
        @endphp
        <input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="{{ bcadd($unitPriceIncTax, 0, 2) }}">
    </td>

    <td class="text text-center">
        <strong><span class="span_subtotal"> {{ bcadd($unitPriceIncTax, 0, 2) }}</span></strong>
        <input value="{{ bcadd($unitPriceIncTax, 0, 2) }}" readonly name="subtotals[]" type="hidden"  id="subtotal">
    </td>

    <td class="text-center">
        <a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>
    </td>
</tr>

