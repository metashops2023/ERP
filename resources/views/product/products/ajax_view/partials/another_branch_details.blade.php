<table id="single_product_branch_stock_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">@lang('Product Code(SKU)')</th>
            <th class="text-white text-start">@lang('Product')</th>
            <th class="text-white text-start">@lang('Business Location')</th>
            <th class="text-white text-start">@lang('Current Stock')</th>
            <th class="text-white text-start">@lang('Stock Value')</th>
            <th class="text-white text-start">@lang('Total Sale')</th>
        </tr>
    </thead>
    <tbody>
        @if (count($another_branch_stocks) > 0)
            @foreach ($another_branch_stocks as $row)
                @if ($row->branch_id != auth()->user()->branch_id)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $product->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">
                                {!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] !!} <b>@lang('Head Office')</b>
                            </td>
                            <td class="text-start"><b>{{ $row->variant_quantity.'('.$product->unit->code_name.')' }}</b></td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->v_total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @else 
                        <tr>
                            <td class="text-start">{{ $product->product_code }}</td>
                            <td class="text-start">{{ $product->name }}</td>
                            <td class="text-start">
                                {!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] !!} <b>@lang('Head Office')</b>
                            </td>
                            <td class="text-start"><b>{{ $row->product_quantity.'('.$product->unit->code_name.')' }}</b></td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $product->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @else 
            <td colspan="6" class="text-center"><b>@lang('No Data Found')</b></td>        
        @endif
        
    </tbody>
</table>