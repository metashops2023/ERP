<table class="display data_tbl data__table">
    <thead>
        <tr class="bg-navey-blue">
            <th>@lang('P.Code')(SKU)</th>
            <th>@lang('Product')</th>
            <th>@lang('Unit Price Exc').Tax</th>
            <th>@lang('B.Location')</th>
            <th>@lang('Warehouse')</th>
            <th>@lang('Current Stock')</th>
            <th>@lang('Current Stock Value') <b><small>(By Purchase Price)</small></b></th>
            <th>@lang('Total Unit Sold')</th>
            <th>@lang('Total Adjusted')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productStock as $row)
            @if ($row->warehouse_name)
                @if ($row->w_variant_quantity)
                    <tr>
                        <td>{{ $row->variant_code }}</td>
                        <td>{{ $row->name.'-'.$row->variant_name }}</td>
                        <td>{{ $row->product_price }}</td>
                        <td>{!! $row->wb_name ? $row->wb_name.'/'.$row->wb_code : json_decode($generalSettings->business, true)['shop_name'] .'<b>(HO)</b>' !!}</td>
                        <td>{{ $row->warehouse_name.'/'.$row->warehouse_code }} </td>
                        <td>{{ $row->w_variant_quantity }}</td>
                        <td>
                            @php
                                $currentStockValue = $row->product_cost_with_tax * $row->w_variant_quantity;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                        </td>
                        <td>---</td>
                        <td>0.00</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $row->product_code }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->product_price }}</td>
                        <td>{!! $row->wb_name ? $row->wb_name.'/'.$row->wb_code : json_decode($generalSettings->business, true)['shop_name'] .'<b>(HO)</b>' !!}</td>
                        <td>{{ $row->warehouse_name.'/'.$row->warehouse_code }} </td>
                        <td>{{ $row->w_product_quantity }}</td>
                        <td>
                            @php
                                $currentStockValue = $row->product_cost_with_tax * $row->w_product_quantity;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                        </td>
                        <td>---</td>
                        <td>0.00</td>
                    </tr>
                @endif
            @endif

            @if ($row->branch_name)
                @if ($row->b_variant_quantity)
                    <tr>
                        <td>{{ $row->variant_code }}</td>
                        <td>{{ $row->name.'-'.$row->variant_name }}</td>
                        <td>{{ $row->product_price }}</td>
                        <td>{{ $row->branch_name.'/'.$row->branch_code }}</td>
                        <td>---</td>
                        <td>{{ $row->b_variant_quantity }}</td>
                        <td>
                            @php
                                $currentStockValue = $row->product_cost_with_tax * $row->b_variant_quantity;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                        </td>
                        <td>{{ $row->bv_total_sale }}</td>
                        <td>0.00</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $row->product_code }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->product_price }}</td>
                        <td>{{ $row->branch_name.'/'.$row->branch_code }}</td>
                        <td>---</td>
                        <td>{{ $row->b_product_quantity }}</td>
                        <td>
                            @php
                                $currentStockValue = $row->product_cost_with_tax * $row->b_product_quantity;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                        </td>
                        <td>{{ $row->b_total_sale }}</td>
                        <td>0.00</td>
                    </tr>
                @endif
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
