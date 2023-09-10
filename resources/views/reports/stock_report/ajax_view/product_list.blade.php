<table class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th>@lang('P.Code')(SKU)</th>
            <th>@lang('Product')</th>
            <th>@lang('Unit Price')</th>
            <th>@lang('Current Stock')</th>
            <th>@lang('Current Stock Value') <b><small>((@lang('By Unit Cost')))</small></b></th>
            <th>@lang('Current Stock Value') <b><small>((@lang('By Unit Price')))</small></b></th>
            <th>@lang('Potential profit')</th>
            <th>@lang('Total Unit Sold')</th>
            <th>@lang('Total Adjusted')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            @if (count($product->product_variants) > 0)
                @foreach ($product->product_variants as $product_variant)
                    <tr>
                        <td>{{ $product_variant->variant_code }}</td>

                        <td>{{ $product->name.' - '.$product_variant->variant_name }}</td>

                        <td>{{ $product_variant->variant_price }}</td>
                        <td>{{ $product_variant->variant_quantity. '('.$product->unit->code_name.')' }}</td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $product_variant->variant_quantity * $product_variant->variant_cost_with_tax, 2, '.', '') }}
                        </td>

                        <td>
                            @php
                                $tax = $product->tax ? $product->tax->tax_percent : 0;
                                $sellingPriceIncTax = ($product_variant->variant_price / 100 * $tax) + $product_variant->variant_price;
                            @endphp
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $product_variant->variant_quantity * $sellingPriceIncTax, 2, '.', '') }}
                        </td>

                        <td>
                            @php
                                $frofit = 0;
                                foreach ($product_variant->sale_variants as $sale_variant) {
                                    if ($sale_variant->sale->status == 1) {
                                        $frofit +=($sale_variant->unit_price_inc_tax * $sale_variant->quantity) -($sale_variant->unit_cost_inc_tax * $sale_variant->quantity);
                                    }
                                }
                            @endphp
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $frofit, 2, '.', '') }}
                        </td>

                        <td>{{ $product_variant->number_of_sale.' ('.$product->unit->code_name.')' }}</td>
                        <td>{{ $product_variant->total_adjusted.' ('.$product->unit->code_name.')' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->product_price }}</td>
                    <td>{{ $product->quantity. '('.$product->unit->code_name.')' }}</td>
                    <td>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $product->quantity * $product->product_cost_with_tax, 2, '.', '') }}
                    </td>
                    <td>
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $sellingPriceIncTax = ($product->product_price / 100 * $tax) + $product->product_price;
                        @endphp
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $product->quantity * $sellingPriceIncTax, 2, '.', '') }}
                    </td>

                    <td>
                        @php
                            $frofit = 0;
                            foreach ($product->sale_products as $sale_product) {
                                if ($sale_product->sale->status == 1) {
                                    $frofit +=($sale_product->unit_price_inc_tax * $sale_product->quantity) -($sale_product->unit_cost_inc_tax * $sale_product->quantity);
                                }
                            }
                        @endphp
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $frofit, 2, '.', '') }}
                    </td>

                    <td>{{ $product->number_of_sale.' ('.$product->unit->code_name.')' }}</td>
                    <td>{{ $product->total_adjusted.' ('.$product->unit->code_name.')' }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
    });
</script>
