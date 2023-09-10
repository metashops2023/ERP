<table id="single_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">@lang('Prodcut cost')({{ json_decode($generalSettings->business, true)['currency'] }}) (Exc.Tax)</th>
            <th class="text-white text-start">@lang('Prodcut cost')({{ json_decode($generalSettings->business, true)['currency'] }}) (Inc.Tax)</th>
            <th class="text-white text-start">@lang('Profit Margin')(%)</th>
            <th class="text-white text-start">@lang('Default Selling Price')({{ json_decode($generalSettings->business, true)['currency'] }}) (Exc.Tax)</th>
            <th class="text-white text-start">@lang('Default Selling Price')({{ json_decode($generalSettings->business, true)['currency'] }}) (Inc.Tax)</th>
            @if (count($price_groups) > 0)
                <th class="text-white text-start">@lang('Price Group')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            @endif
            @php
                $priceIncTax = ($product->product_price / 100) * $tax + $product->product_price;
                if ($product->tax_type == 2) {
                    $inclusiveTax = 100 + $tax;
                    $calc = ($product->product_price / $inclusiveTax) * 100;
                    $__tax_amount = $product->product_price - $calc;
                    $priceIncTax = $product->product_price + $__tax_amount;
                }
            @endphp
        </tr>
    </thead>
    <tbody class="single_product_pricing_table_body">
        <tr>
            <td class="text-start">
                {{ App\Utils\Converter::format_in_bdt($product->product_cost) }}
            </td>
            <td class="text-start">

                {{ $product->product_cost_with_tax }}
            </td>
            <td class="text-start">{{ $product->profit }}</td>
            <td class="text-start">
                {{ App\Utils\Converter::format_in_bdt($product->product_price) }}
            </td>
            <td class="text-start">
                {{ json_decode($generalSettings->business, true)['currency'] }}
                {{ App\Utils\Converter::format_in_bdt($priceIncTax) }}
            </td>
            @if (count($price_groups) > 0)
                <td class="text-start">
                    @foreach ($price_groups as $pg)
                        @php
                            $price_group_product = DB::table('price_group_products')
                            ->where('price_group_id', $pg->id)->where('product_id', $product->id)->first();
                            $groupPrice = 0;
                            if ($price_group_product) {
                                $groupPrice = $price_group_product->price;
                            }
                        @endphp
                        <p class="p-0 m-0"><strong>{{ $pg->name }}</strong> - {{ App\Utils\Converter::format_in_bdt($groupPrice)}}</p>
                    @endforeach
                </td>
            @endif
        </tr>
    </tbody>
</table>
