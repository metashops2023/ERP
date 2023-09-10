@if ($product->is_variant == 0)
    <table id="single_product_branch_stock_table" class="table modal-table table-sm custom-table">
        <thead>
            <tr class="bg-primary">
                <th class="text-white" scope="col">@lang('Product Code(SKU)')</th>
                <th class="text-white" scope="col">@lang('Product')</th>
                <th class="text-white" scope="col">@lang('Branch')</th>
                <th class="text-white" scope="col">@lang('Unit Price (Inc').Tax)</th>
                <th class="text-white" scope="col">@lang('Current Stock')</th>
                <th class="text-white" scope="col">@lang('Stock Value')</th>
            </tr>
        </thead>
        <tbody>
            @if($product->product_branches->count() > 0)
                @foreach ($product->product_branches as $product_branch)
                    <tr>
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $product_price_inc_tax = ($product->product_price / 100 * $tax) + $product->product_price;
                        @endphp
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product_branch->branch->name .' - '. $product_branch->branch->branch_code }}</td>
                        <td>{{ number_format($product_price_inc_tax, 2) }}</td>
                        <td><strong>{{ $product_branch->product_quantity .' ('. $product->unit->code_name.')' }}</strong></td>
                        @php
                            $stockValue = $product_branch->product_quantity * $product_price_inc_tax;
                        @endphp
                        <td>{{ number_format($stockValue, 2)  }}</td>
                    </tr>
                @endforeach
            @else 
                <tr>
                    <td colspan="7" class="text-center">@lang('This product is not available in any branch').</td>
                </tr>
            @endif
        </tbody>
    </table>
@else 
    <table id="variant_product_branch_stock_table" class="table table-sm custom-table">
        <thead>
            <tr class="bg-primary">
                <th class="text-white" scope="col">@lang('Product Code(SKU)')</th>
                <th class="text-white" scope="col">@lang('Product')</th>
                <th class="text-white" scope="col">@lang('Branch')</th>
                <th class="text-white" scope="col">@lang('Unit Price (Inc').Tax)</th>
                <th class="text-white" scope="col">@lang('Current Stock')</th>
                <th class="text-white" scope="col">@lang('Stock Value')</th>
            </tr>
        </thead>
        <tbody>
            @if ($product->product_branches->count() > 0)
                @foreach ($product->product_branches as $product_branch)
                    @foreach ($product_branch->product_branch_variants as $product_branch_variant)
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $variant_price_inc_tax = ($product_branch_variant->product_variant->variant_price / 100 * $tax) + $product_branch_variant->product_variant->variant_price;
                        @endphp
                        <tr>
                            <td>{{ $product_branch_variant->product_variant->variant_code }}</td>
                            <td>{{ $product->name.' - '.$product_branch_variant->product_variant->variant_name }}</td>
                            <td>{{ $product_branch->branch->name .' - '. $product_branch->branch->branch_code }}</td>
                            <td>{{ number_format($variant_price_inc_tax, 2) }}</td>
                            <td><strong>{{ $product_branch_variant->variant_quantity.' ('.$product->unit->code_name.')' }}</strong></td>
                            @php
                                $stockValue = $product_branch_variant->variant_quantity * $variant_price_inc_tax;
                            @endphp
                            <td>{{ number_format($stockValue, 2)  }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else 
                <tr>
                    <td colspan="7" class="text-center">@lang('This product is not available in any branch').</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif