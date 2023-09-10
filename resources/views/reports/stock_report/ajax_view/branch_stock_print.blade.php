<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 20px;margin-right: 20px;}
</style>
@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p><b>@lang('All Business Location')</b></p>
        @elseif ($branch_id == 'NULL')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        <h6 style="margin-top: 10px;"><b>@lang('Business Location Stock Report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('P.Code')</th>
                    <th class="text-start">@lang('Product')</th>
                    <th class="text-start">@lang('Business Location')</th>
                    <th class="text-end">@lang('Unit Price')</th>
                    <th class="text-end">@lang('Current Stock')</th>
                    <th class="text-end">@lang('Stock Value') <b><small>((@lang('By Unit Cost')))</small></b></th>
                    <th class="text-end">@lang('Total Sold')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($branch_stock as $row)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $row->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($row->variant_price) }}</td>
                            <td class="text-end">{{ $row->variant_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-end">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-end">{{ $row->v_total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-start">{{ $row->product_code }}</td>
                            <td class="text-start">{{ $row->name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name : json_decode($generalSettings->business, true)['shop_name'] !!}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($row->product_price) }}</td>
                            <td class="text-end">{{ $row->product_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-end">
                                @php
                                    $currentStockValue = $row->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td class="text-end">
                            <td class="text-end">{{ $row->total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('Print Date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (env('PRINT_SD_SALE') == true)
                <small>@lang('Powered By') <b>@lang('MetaShops Software Solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('Print Time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
