<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4; margin-top: 0.8cm;margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p><b>@lang('All Business Location')</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('Date') :</b>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('Cash Flow Statement') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        @php
            $totalCashFlow = 0;
        @endphp
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="aiability_area">
                        <table class="table table-sm">
                            <tbody>
                                {{-- Cash Flow from operations --}}
                                @php
                                    $oparationTotal = 0;
                                @endphp
                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('CASH FLOW FROM OPERATIONS') :</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Net Profit Before Tax') :</em>
                                    </td>

                                    <td class="text-start">
                                       <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['net_profit_before_tax']) }}</em>
                                       @php
                                         $oparationTotal += $netProfitLossAccount['net_profit_before_tax'];
                                       @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Customer Balance') : </em>
                                    </td>

                                    <td class="text-start">
                                        <em>({{ App\Utils\Converter::format_in_bdt($customerReceivable->sum('total_due')) }})</em>
                                        @php
                                            $oparationTotal -= $customerReceivable->sum('total_due');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Supplier Balance') : </em>
                                    </td>

                                    <td class="text-start">
                                         <em>{{ App\Utils\Converter::format_in_bdt($supplierPayable->sum('total_due')) }}</em>
                                        @php
                                            $oparationTotal += $supplierPayable->sum('total_due');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Current Stock Value') : </em>
                                    </td>

                                    <td class="text-start">
                                        <em>({{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['closing_stock']) }})</em>
                                        @php
                                            $oparationTotal -= $netProfitLossAccount['closing_stock'];
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Current Asset') :</em>
                                    </td>

                                    <td class="text-start">
                                         <em>{{ App\Utils\Converter::format_in_bdt($currentAssets->sum('total_current_asset')) }}</em>
                                        @php
                                            $oparationTotal += $currentAssets->sum('total_current_asset');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Current Liability') :</em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($currentLiability->sum('current_liability')) }}</em>
                                        @php
                                            $oparationTotal += $currentLiability->sum('current_liability');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Tax Payable') :</em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['tax_payable']) }}</em>
                                        @php
                                            $oparationTotal += $netProfitLossAccount['tax_payable'];
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>Total Operations :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b>{{ $oparationTotal < 0 ? '('. App\Utils\Converter::format_in_bdt($oparationTotal).')' : App\Utils\Converter::format_in_bdt($oparationTotal) }}</b>
                                        @php
                                            $totalCashFlow += $oparationTotal;
                                        @endphp
                                    </td>
                                </tr>

                                {{-- Cash Flow from investing --}}

                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('CASH FLOW FROM INVESTING') :</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('FIXED ASSET') :</em>
                                    </td>

                                    <td class="text-start">
                                        <em>
                                            ({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})
                                        </em>
                                    </td>
                                    @php
                                        $totalCashFlow -= $fixedAssets->sum('total_fixed_asset');
                                    @endphp
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>Total Investing :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b><em>({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})</em> </b>
                                    </td>
                                </tr>

                                {{-- Cash Flow from financing --}}
                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('CASH FLOW FROM FINANCING') :</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Capital A/C') :</em>
                                    </td>
                                    <td class="text-start">0.00</td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Loan And Advance') :</em>
                                    </td>
                                    <td class="text-start">({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</td>
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>Total financing :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b>
                                            <em>({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</em>
                                        </b>
                                        @php
                                            $totalCashFlow -= $loanAndAdvance->sum('current_loan_receivable');
                                        @endphp
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>
                                                Total Cash Flow : ({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b class="total_cash_flow">
                                            <em>
                                                {{ $totalCashFlow < 0 ? '('.App\Utils\Converter::format_in_bdt($totalCashFlow).')' : App\Utils\Converter::format_in_bdt($totalCashFlow) }}
                                            </em>
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        Print Date: {{ date('d-m-Y , h:iA') }}
    </small>
</div>
