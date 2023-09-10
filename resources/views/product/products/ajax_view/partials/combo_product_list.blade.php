<table id="single_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">@lang('Product Name')</th>
            <th class="text-white text-start">@lang('Prodcut cost (Inc').Tax)</th>
            <th class="text-white text-start">@lang('Profit Margin')(%)</th>
            <th class="text-white text-start">@lang('Default Selling Price (Exc.Tax)')</th>
            <th class="text-white text-start">@lang('Default Selling Price (Inc.Tax)')</th>
            <th class="text-white text-start">@lang('Quantity')</th>
            <th class="text-white text-start">@lang('Total Amount')</th>
        </tr>
    </thead>
    <tbody class="single_product_pricing_table_body">
        @foreach ($product->ComboProducts as $comPro)
        @php
            $__tax = $comPro->parentProduct->tax ? $comPro->parentProduct->tax->tax_percent : 0;
            $priceIncTax = 0;
            $subTotal = 0;
            if ($comPro->product_variant) {
                $priceIncTax = ($comPro->product_variant->variant_price / 100 * $__tax) + $comPro->product_variant->variant_price;
                $subTotal = $priceIncTax * $comPro->quantity;
                if ($comPro->parentProduct->tax_type == 2) {
                    $inclusiveTax = 100 + $__tax;
                    $calc = ($comPro->product_variant->variant_price / $inclusiveTax) * 100;
                    $__tax_amount = $comPro->product_variant->variant_price - $calc;
                    $priceIncTax = $comPro->product_variant->variant_price + $__tax_amount;
                    $subTotal = $priceIncTax * $comPro->quantity;
                }
            } else {
                $priceIncTax = ($comPro->parentProduct->product_price / 100 * $__tax) + $comPro->parentProduct->product_price;
                $subTotal = $priceIncTax * $comPro->quantity;
                if ($comPro->parentProduct->tax_type == 2) {
                    $inclusiveTax = 100 + $__tax;
                    $calc = ($comPro->parentProduct->product_price / $inclusiveTax) * 100;
                    $__tax_amount = $comPro->parentProduct->product_price - $calc;
                    $priceIncTax = $comPro->parentProduct->product_price + $__tax_amount;
                    $subTotal = $priceIncTax * $comPro->quantity;
                }
            }
        @endphp
            <tr>
                <td class="text-start">{{ $comPro->parentProduct->name.' '.($comPro->product_variant ? $comPro->product_variant->variant_name : '') }}</td>
                <td class="text-start">
                    @if ($comPro->product_variant_id)
                        {{ App\Utils\Converter::format_in_bdt($comPro->product_variant->variant_cost_with_tax) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($comPro->parentProduct->product_cost_with_tax) }}
                    @endif
                </td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($comPro->parentProduct->profit) }}%</td>
                <td class="text-start">
                    @if ($comPro->product_variant_id)
                        {{ App\Utils\Converter::format_in_bdt($comPro->product_variant->variant_price) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($comPro->parentProduct->product_price) }}
                    @endif
                </td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($priceIncTax) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($comPro->quantity) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($subTotal) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
