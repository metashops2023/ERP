
    <thead>
        <tr>
            <th class="text-start"><input type="checkbox" id="chack_all">@lang('All')</th>
            <th class="text-start">@lang('Product')</th>
            <th class="text-start">@lang('Supplier')</th>
            <th class="text-end">@lang('Quantity')</th>
        </tr>
    </thead>
    <tbody id="purchased_product_list">
        @php
            $totalPendingQty = 0;
        @endphp
        @if (count($supplier_products) > 0)
            @foreach ($supplier_products as $s_product)
                @php
                    $tax = $s_product->product->tax ? $s_product->product->tax->tax_percent : 0.00;
                    $price = $s_product->variant ? $s_product->variant->variant_price : $s_product->product->product_price;
                    $priceIncTax = $price /100 * $tax + $price;
                    if ($s_product->product->tax_type == 2) {
                        $inclusiveTax = 100 + $tax;
                        $calcAmount = $price / $inclusiveTax * 100;
                        $tax_amount = $price - $calcAmount;
                        $priceIncTax = $price + $tax_amount;
                    }
                @endphp

                <tr
                    data-p_id="{{ $s_product->product->id }}"
                    data-p_code="{{ $s_product->product->product_code }}"
                    data-p_name="{{ $s_product->product->name }}"
                    data-v_id="{{ $s_product->product_variant_id }}"
                    data-v_code="{{ $s_product->variant ? $s_product->variant->variant_code : '' }}"
                    data-v_name="{{ $s_product->variant ? $s_product->variant->variant_name : '' }}"
                    data-price="{{ $priceIncTax }}"
                    data-tax="{{ $tax }}"
                    data-supplier_id="{{ $s_product->supplier->id }}"
                    data-supplier_name="{{ $s_product->supplier->name }}"
                    data-supplier_prefix="{{ $s_product->supplier->prefix }}"
                    data-label_qty="{{ $s_product->label_qty }}"
                    data-barcode_type="{{ $s_product->product->barcode_type }}"
                >
                    <td class="text-start"><input type="checkbox" class="check"></td>
                    <td class="text-start">
                        <span class="span_product_name">{{ Str::limit($s_product->product->name, 15, '') }}</span>
                        @if ($s_product->product_variant_id != null)
                            <span class="span_variant_name">{{' - '.$s_product->variant->variant_name }}</span>
                        @endif
                    </td>
                    <td class="text-start">{!! $s_product->supplier->name.'/<b>'.$s_product->supplier->prefix.'</b>' !!}</td>
                    <td class="text-end">{{ $s_product->label_qty }}</td>
                    @php
                        $totalPendingQty += $s_product->label_qty;
                    @endphp
                </tr>
            @endforeach
        @else
            <tr>
                <th colspan="4" class="text-center">@lang('No Data Found').</th>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-end">@lang('Total Pending Qty') :</th>
            <th colspan="3" class="text-end">({{ $totalPendingQty }})</th>
        </tr>
    </tfoot>


