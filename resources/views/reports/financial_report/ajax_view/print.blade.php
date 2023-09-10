<style>
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
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
        <h6 style="margin-top: 10px;"><b>@lang('Financial Report') </b></h6>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="aiability_area">
                        <table class="table table-sm">
                            <tbody>
                                {{-- Cash Flow from investing --}}
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('ASSET') :</strong>
                                    </th>
                                </tr>
                                
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Fixed Asset') :</em> 
                                    </td>
                                    <td class="text-end"><b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['fixed_asset_balance']) }}</em></b>  </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('PURCHASE') :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                       <em>@lang('Total Purchase') :</em>   
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase']) }}</em> </b>
                                    </td>
                                </tr> 
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Paid') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_paid']) }}</em> </b>  
                                    </td>
                                </tr> 
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Purchase Due') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_due']) }}</em> </b>  
                                    </td>
                                </tr> 
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Purchase Return') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_return']) }}</em> </b>  
                                    </td>
                                </tr> 
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('SALES') :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Sale'):</em>  
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale']) }}</em></b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Payment Received') :</em>  
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_paid']) }}</em></b>    
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Sale Due') :</em>  
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_due']) }}</em></b>    
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Sale Return') :</em>  
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_return']) }}</em> </b>   
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('EXPENSES') :</strong>
                                    </th>
                                </tr> 
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Direct Expense') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_direct_expense']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Indirect Expense') :</em>
                                    </td>
                                    
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_indirect_expense']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('PRODUCTS') :</strong>
                                    </th>
                                </tr>
        
                                @if (!$fromDate)
                                    <tr>
                                        <td class="text-start">
                                            <em>@lang('Closing Stock') (<small>@lang('Non-filterable by Date')</small>) :</em>
                                        </td>
            
                                        <td class="text-end">
                                            <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['closing_stock']) }}</em> </b>  
                                        </td>
                                    </tr>
                                @endif
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Stock Adjustment') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Stock Adjustment Recovered Amount') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted_recovered']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('PROFIT LOSS') :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Daily Profit') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['daily_profit']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Gross Profit') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['gross_profit']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em> @lang('Net Profit') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['net_profit']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start text-dark" colspan="2">
                                        <strong>@lang('ACCOUNT BALANCE') :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Cash-In-Hand Balance') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['cash_in_hand']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Bank A/C Balance') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['bank_account']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start bg-secondary text-dark" colspan="2">
                                        <strong>@lang('LOAN') & ADVANCE :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Loan') & Advance :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance']) }}</em></b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Loan') & Advance Due Received :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance_received']) }}</em> </b>  
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Receivable Loan') & Advance Due :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance_due']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <th class="text-start bg-secondary text-dark" colspan="2">
                                        <strong>@lang('LOAN') & LIABILITIES :</strong>
                                    </th>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Loan Liabilities') :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Total Loan') & Liabilities Due Paid :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability_paid']) }}</em> </b>  
                                    </td>
                                </tr>
        
                                <tr>
                                    <td class="text-start">
                                        <em>@lang('Payable Loan') & Liabilities Due :</em>
                                    </td>
        
                                    <td class="text-end">
                                        <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability_due']) }}</em> </b>  
                                    </td>
                                </tr>
        
                            </tbody>
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