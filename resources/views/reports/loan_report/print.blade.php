<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
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
        @if (!auth()->user()->branch_id)
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b></h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', auth()->user()->branch_id)
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
        <h6 style="margin-top: 10px;"><b>@lang('Loan Report') </b></h6>
    </div>
</div>

@if ($company_id)
    @php
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
    @endphp
    <div class="customer_details_area">
        <div class="row">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li><strong>@lang('Company/People') : </strong> {{ $company->name }}</li>
                    <li><strong>@lang('Phone') : </strong> </li>
                    <li><strong>@lang('Address') : </strong> </li>
                </ul>
            </div>
        </div>
    </div>
    <br>
@endif
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('B.Location')</th>
                    <th class="text-start">@lang('Reference No')</th>
                    <th class="text-start">@lang('Company/People')</th>
                    <th class="text-start">@lang('Type')</th>
                    <th class="text-start">@lang('Loan By')</th>
                    <th class="text-end">@lang('Loan Amount')({{json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-end">@lang('Total Paid')({{json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-end">@lang('Laon Due')({{json_decode($generalSettings->business, true)['currency']}})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                   $totalPayLoan = 0;
                   $totalGetLoan = 0;
                   $totalPaid = 0;
                   $totalReceive = 0;
                   $totalPayLoanDue = 0;
                   $totalGetLoanDue = 0;
                @endphp
                @foreach ($loans as $loan)
                    <tr>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($loan->report_date)) }}</td>
                        <td class="text-start">
                            @if ($loan->b_name)
                                {!! $loan->b_name . '/' . $loan->b_code . '(<b>BL</b>)' !!}
                             @else
                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">{{ $loan->reference_no }}</td>
                        <td class="text-start">{{ $loan->c_name }}</td>

                        <td class="text-start">
                            @if ($loan->type == 1)
                                Pay Loan
                            @else
                                Receive Loan
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($loan->loan_by)
                                 {{ $loan->loan_by }}
                            @else
                                 Cash Loan pay
                            @endif
                        </td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($loan->loan_amount) }}
                            @php
                                if ($loan->type == 1) {
                                    $totalPayLoan += $loan->loan_amount;
                                }else {
                                    $totalGetLoan += $loan->loan_amount;
                                }
                            @endphp
                        </td>
                        <td class="text-end">
                            {{ $loan->type == 1 ? App\Utils\Converter::format_in_bdt($loan->total_receive) : App\Utils\Converter::format_in_bdt($loan->total_paid) }}
                            @php
                                $totalPaid += $loan->total_paid;
                                $totalReceive += $loan->total_receive;
                            @endphp
                        </td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($loan->due) }}
                            @php
                                if ($loan->type == 1) {
                                    $totalPayLoanDue += $loan->due;
                                }else {
                                    $totalGetLoanDue += $loan->due;
                                }
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <p><b>@lang('Get Laon Details')</b></p>
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">@lang('Total Get loan') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalGetLoan) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Due Paid') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Get Loan Due') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalGetLoanDue) }}</td>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-6">
        <p><b>@lang('Pay Laon Details')</b></p>
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">@lang('Total Pay loan') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPayLoan) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Due Receive') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReceive) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Pay Loan Due') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPayLoanDue) }}</td>
                </tr>
            </thead>
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
