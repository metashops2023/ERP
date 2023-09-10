<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="loan_details_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($loan->branch)
                        {{ $loan->branch->name . '/' . $loan->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                    @endif
                </b>
            </h3>

            <p style="width: 60%; margin:0 auto;">
                @if ($loan->branch)
                    {{ $loan->branch->city . ', ' . $loan->branch->branch->state . ', ' . $loan->branch->zip_code . ', ' . $loan->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <br>
            <h6><b>@lang('Loan Details')</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <p><b>@lang('Title') :</b>
        {{ $loan->type == 1 ? 'Loan pay' : 'Loan Receive' }} </p>
        <p><b>@lang('Company/People') :</b> {{ $loan->company->name }}</p>
        <p><b>@lang('Address') :</b></p>
        <p><b>@lang('Phone') :</b></p>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('Voucher No') :</th>
                            <td width="50%" class="text-start">
                                {{ $loan->reference_no }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Pay Loan Amount :' : 'Receive Loan Amount :' }}</th>
                            <td width="50%" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($loan->loan_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Debit Account :' : 'Credit Account :' }}</th>
                            <td width="50%" class="text-start">{{ $loan->account ? $loan->account->name.' (A/C: '.$loan->account->account_number.')' : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Due Receive Amount :' : 'Due Paid Amount :' }}</th>
                            <td width="50%" class="text-start">
                                {{ $loan->type == 1 ? App\Utils\Converter::format_in_bdt($loan->total_receive) : App\Utils\Converter::format_in_bdt($loan->total_paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Due') :</th>
                            <td width="50%" class="text-start">
                                {{ App\Utils\Converter::format_in_bdt($loan->due) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Loan Reason') :</th>
                            <td width="50%" class="text-start">
                                {{ $loan->loan_reason }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-none">
        <br>
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">@lang('Signature Of Receiver')</th>
                    <th width="50%" class="text-end">@lang('Signature Of Provider')</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($loan->reference_no , $generator::TYPE_CODE_128)) }}">
                        <p>{{ $loan->reference_no }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">@lang('Software by') <b>@lang('MetaShops Pvt'). Ltd.</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
