
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-6">
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
                                    {{ App\Utils\Converter::format_in_bdt($totalSaleReturn) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('Total Payroll') :</th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}
                                </td>
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

        <div class="col-md-12 col-sm-12 col-lg-6">
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
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $grossProfit = ($totalSale + $totalStockAdjustmentRecovered)
                - $totalStockAdjustmentAmount
                - $totalExpense
                - $totalSaleReturn
                - $totalOrderTax
                - $totalPayroll
                - $totalTotalUnitCost
                - $totalTransferShipmentCost;
@endphp

<div class="profit_area mt-1">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="gross_profit_area">
                        <h6 class="text-muted m-0">Gross Profit :
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            <span class="{{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($grossProfit) }}</span></h6>
                            <p class="text-muted m-0">Gross Profit : (Total Sale + Total Stock Adjustment Recovered)
                                - <br>( Sold Product Total Unit Cost + Total Sale Return + Total Sale Order Tax + Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Payroll + Total Production Cost )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
