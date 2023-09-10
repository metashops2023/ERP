<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 text-center">
            @if ($branch_id == '')
                <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p><b>@lang('All Business Location').</b></p> 
                <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            @elseif ($branch_id == 'NULL')
                <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            @else 
                @php
                    $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')->first();
                @endphp
                <h6>{{ $branch->name.' '.$branch->branch_code }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
            @endif
            
            @if ($fromDate && $toDate)
                <p><b>@lang('Date') :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
            @endif
            <h6 style="margin-top: 10px;"><b>@lang('Profit / Loss Report') </b></h6> 
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">  
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start"> 
                                    Sold Product Total Unit Cost : 
                                    <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('Total Order Tax') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalOrderTax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start"> @lang('Total Stock Adjustment') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start"> @lang('Total Expense') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">@lang('Total transfer shipping charge') : </th>
                                <td class="text-start"> 
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">@lang('Total Sell Return') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalReturn) }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">@lang('Total Payroll') :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}</td>
                            </tr>
    
                            <tr>
                                <th class="text-start">@lang('Total Production Cost') :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body"> 
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    @lang('Total Sales') : <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>
    
                            <tr>
                                <th class="text-start">@lang('Total Stock Adjustment Recovered') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $grossProfit = ($totalSale + $totalStockAdjustmentRecovered) 
                                    - $totalStockAdjustmentAmount 
                                    - $totalExpense
                                    - $totalReturn
                                    - $totalOrderTax
                                    - $totalPayroll
                                    - $totalTotalUnitCost
                                    - $totalTransferShipmentCost;
                    @endphp

                    <div class="gross_profit_area">
                        <h6 class="text-muted m-0">Total Daily Profit : 
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            <span class="{{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($grossProfit) }}</span></h6>
                        <p class="text-muted m-0"><b>@lang('Calculate Gross Profit') :</b> (Total Sale + Total Stock Adjustment Recovered)
                            <b>-</b> ( Sold Product Total Unit Cost + Total Sale Return + Total Sale Order Tax + Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Payroll + Total Production Cost )</p>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>  



@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small> 
        </div>
    </div>
@endif