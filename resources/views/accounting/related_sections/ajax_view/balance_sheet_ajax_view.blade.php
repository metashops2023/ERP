<?php
    $totalLiability = 0;
    $totalAsset = 0;
    $netProfitOrLoss = $netProfitLossAccount['net_profit'];
?>
<table class="table modal-table table-sm table-bordered">
    <thead>
        <tr class="bg-primary">
            <th class="liability text-white">@lang('Liabilities')</th>
            <th class="assets text-white">@lang('Assets')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-start"><em>@lang('Supplier Balance') :</em>  </td>
                            <td class=" text-end">
                                <span class="supplier_due"><em>{{ App\Utils\Converter::format_in_bdt($suppliers->sum('total_due')) }}</em> </span>
                                @php $totalLiability += $suppliers->sum('total_due')  @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Customer Return Balance') : </em> </td>
                            <td class="text-end">
                                <em class="customer_return_due">{{ App\Utils\Converter::format_in_bdt($customers->sum('total_return_due')) }}</em>
                                @php $totalLiability += $customers->sum('total_return_due')  @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('Payable Loan') & Liabilities : </em>
                            </td>

                            <td class="text-end">
                                <em class="payable_ll">{{ App\Utils\Converter::format_in_bdt($loanCompanies->sum('total_ll_payable')) }}</em>
                                @php $totalLiability += $loanCompanies->sum('total_ll_payable') @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('Capital A/C') : </em>
                            </td>

                            <td class="text-end">
                                <em class="capital_balance">{{ App\Utils\Converter::format_in_bdt($TotalCapital->sum('total_capital')) }}</em>
                                @php $totalLiability += $TotalCapital->sum('total_capital') @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('Profit') & Loss A<C : </em>
                            </td>

                            <td class="text-end">
                                <em class="capital_balance">{{ App\Utils\Converter::format_in_bdt($netProfitOrLoss) }}</em>
                                @php $totalLiability += $netProfitOrLoss @endphp
                            </td>
                        </tr>

                        <tr class="bg-danger">
                            <td class="text-end text-white">
                                <em>@lang('Current Total') : </em>
                            </td>
                            <td class="text-end text-white">
                                <em>{{ App\Utils\Converter::format_in_bdt($totalLiability) }}</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('Difference In Opening Balance'): </em>
                            </td>

                            <td class="text-end">
                                <em class="different"></em>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td class="asset_area">
                <table class="table table-sm">
                    @php
                        $totalCurrnetAsset = 0;
                    @endphp
                    <tbody>
                        <tr>
                            <td class="text-start"><em>@lang('Cash-In-Hand') : </em></td>
                            <td class="text-end">
                                <em class="cash_in_hand">{{ App\Utils\Converter::format_in_bdt($totalCashInHand->sum('total_cash')) }}</em>
                                @php
                                    $totalAsset += $totalCashInHand->sum('total_cash');
                                    $totalCurrnetAsset += $totalCashInHand->sum('total_cash');
                                @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Bank A/C Balance') : </em></td>
                            <td class="text-end">
                                <em class="bank_balance">{{ App\Utils\Converter::format_in_bdt($TotalBankBalance->sum('total_bank_balance')) }}</em>
                                @php
                                    $totalAsset += $TotalBankBalance->sum('total_bank_balance');
                                    $totalCurrnetAsset += $TotalBankBalance->sum('total_bank_balance');
                                @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Customer Balance') : </em></td>
                            <td class="text-end">
                                <em class="customer_due">{{ App\Utils\Converter::format_in_bdt($customers->sum('total_due')) }}</em>
                                @php
                                    $totalAsset += $customers->sum('total_due');
                                    $totalCurrnetAsset += $customers->sum('total_due');
                                @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Supplier Return Balance') : </em></td>
                            <td class="text-end">
                                <em class="supplier_return_due">{{ App\Utils\Converter::format_in_bdt($suppliers->sum('total_return_due')) }}</em>
                                @php
                                    $totalAsset += $suppliers->sum('total_return_due');
                                    $totalCurrnetAsset += $suppliers->sum('total_return_due');
                                @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Current Stock Value') : </em></td>
                            <td class=" text-end">
                                <em class="stock_value">{{ App\Utils\Converter::format_in_bdt($currentStockValue) }}</em>
                                @php
                                    $totalAsset += $currentStockValue;
                                    $totalCurrnetAsset += $currentStockValue;
                                 @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Investments') : </em></td>
                            <td class=" text-end">
                                <em class="investment">{{ App\Utils\Converter::format_in_bdt($TotalInvestment->sum('total_investment')) }}</em>
                                @php
                                    $totalAsset += $TotalInvestment->sum('total_investment');
                                    $totalCurrnetAsset += $TotalInvestment->sum('total_investment');
                                @endphp
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start"><em>@lang('Receivable Loan')&Advance : </em></td>
                            <td class=" text-end">
                                <em class="receiveable_la">{{ App\Utils\Converter::format_in_bdt($loanCompanies->sum('total_la_receivable')) }}</em>
                                @php
                                    $totalAsset += $loanCompanies->sum('total_la_receivable');
                                    $totalCurrnetAsset += $loanCompanies->sum('total_la_receivable');
                                @endphp
                            </td>
                        </tr>

                        <tr class="bg-info">
                            <td class="text-end text-white"><em>@lang('Total Current Asset') : </em></td>
                            <td class=" text-end text-white">
                                <em class="total_current_asset">{{ App\Utils\Converter::format_in_bdt($totalCurrnetAsset) }}</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end text-white"></td>
                            <td class="text-end"></td>
                        </tr>

                        <tr class="bg-secondary">
                            <th colspan="2" class="text-start text-white"><em>@lang('Fixed Assets List') :</em></th>
                        </tr>

                        <tr class="account_balance_list_area">
                            <td colspan="2">
                                <table class="table table-sm">
                                    <tbody class="account_balance_list">
                                        @foreach ($fixedAssets as $fixedAsset)
                                            <tr>
                                                <td class="text-start" colspan="2">
                                                    <em>{{ $fixedAsset->name }} : </em>
                                                    @php
                                                        $totalAsset += $fixedAsset->balance;
                                                    @endphp
                                                </td>
                                                <td class="text-end">
                                                    <em>{{ App\Utils\Converter::format_in_bdt($fixedAsset->balance) }}</em>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr class="bg-primary">
            {{-- Calculate difference --}}
            @php
                $differ = $totalAsset - $totalLiability;
                $totalLiability += $differ;
            @endphp
            <td class="total_liability_area">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-start"><em>@lang('Total Liability') : ({{ json_decode($generalSettings->business, true)['currency'] }}) </em> </td>
                            <td class="text-end">
                                <em class="total_liability">{{ App\Utils\Converter::format_in_bdt($totalLiability) }}</em>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </th>
            <td class="total_asset_area">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-start"><em>@lang('Total Asset') : ({{ json_decode($generalSettings->business, true)['currency'] }})</em></td>
                            <td class="text-end">
                                <em class="total_asset">{{ App\Utils\Converter::format_in_bdt($totalAsset) }}</em>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </th>
        </tr>
    </tfoot>
</table>

<script>
    // document.getElementById('different').innterHTML = "{{ $differ }}";
    $('.different').html(bdFormat("{{ $differ }}"));
</script>
