@php
    $index = 0;
@endphp
@foreach ($invoiceProducts as $product)
    <tr>
        <td class="serial">{{$index + 1 }}</td>
        <td class="text-start">
            <a class="product-name text-info" id="edit_product" title="{{ $product->variant ? $product->variant->variant_code : $product->product->product_code }}" href="#" tabindex="-1">
                {{ $product->product->name . ($product->variant ? ' - '.$product->variant->variant_name : '')}}
            </a><br/><input type="{{$product->product->is_show_emi_on_pos == 1 ? 'text' : 'hidden'}}" name="descriptions[]" class="form-control description_input scanable" placeholder="IMEI, Serial number or other informations here." value="{{$product->description ? $product->description : ''}}">
            <input value="{{$product->product_id}}" type="hidden" class="productId-{{ $product->product_id }}" id="product_id" name="product_ids[]">
            <input input value="{{ $product->product_variant_id ? $product->product_variant_id : 'noid' }}" type="hidden" class="variantId-{{ $product->product_variant_id ? $product->product_variant_id : '' }}" id="variant_id" name="variant_ids[]">
            <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ $product->unit_tax_percent }}"> 
            <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount"
                value="{{ $product->unit_tax_amount }}">
            <input value="{{ $product->unit_discount_type }}" name="unit_discount_types[]" type="hidden" id="unit_discount_type">
            <input value="{{ $product->unit_discount }}" name="unit_discounts[]" type="hidden" id="unit_discount">
            <input value="{{ $product->unit_discount_amount }}" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">
            <input value="{{ $product->unit_cost_inc_tax }}" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">
            <input type="hidden" id="previous_qty" value="{{ $product->quantity }}">
            <input type="hidden" id="qty_limit" value="{{ $qty_limits[$index] }}">
            <input class="index-{{ uniqid() }}" type="hidden" id="index">
        </td>

        <td>
            <input value="{{ $product->quantity }}" required name="quantities[]" type="number" step="any"
                    class="form-control text-center" id="quantity">
        </td>
        <td>
            <b><span class="span_unit">{{ $product->unit }}</span></b>
            <input name="units[]" type="hidden" id="unit" value="{{ $product->unit }}">
        </td>
        <td>
            <input name="unit_prices_exc_tax[]" type="hidden" value="{{ $product->unit_price_exc_tax }}" id="unit_price_exc_tax">
            <input name="unit_prices_inc_tax[]" type="hidden" id="unit_price_inc_tax"
                value="{{ $product->unit_price_inc_tax }}">
            <b><span class="span_unit_price_inc_tax">{{ $product->unit_price_inc_tax }}</span></b>
        </td>
        <td>
            <input value="{{ $product->subtotal }}" name="subtotals[]" type="hidden" id="subtotal">
            <b><span class="span_subtotal">{{ $product->subtotal }}</span></b>
        </td>
        <td><a href="#" class="action-btn c-delete" id="remove_product_btn" tabindex="-1"><span class="fas fa-trash "></span></a></td>
    </tr> 
    @php $index++; @endphp 
@endforeach

