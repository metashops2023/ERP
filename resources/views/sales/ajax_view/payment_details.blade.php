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
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->sale->branch)
                        {{ $payment->sale->branch->name . '/' . $payment->sale->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                    @endif
                </b>
            </h3>

            <p>
                @if ($payment->sale->branch)
                    {{ $payment->sale->branch->city . ', ' . $payment->sale->branch->state . ', ' . $payment->sale->branch->zip_code . ', ' . $payment->sale->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <h6 style="margin-top: 10px;"><b>@lang('Payment Details')</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <p>
            <b>@lang('Title') :</b>
            {{ $payment->payment_type == 1 ? 'Receive Payment' : 'Sale Return Payment' }}
        </p>
        <p><b>@lang('Invoice No') :</b> {{ $payment->sale->invoice_id }}</p>
        <p>
            <b>@lang('Customer') :</b>
            {{ $payment->sale->customer ? $payment->sale->customer->name : 'Walk-In-Customer' }}
        </p>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('Paid Amount') :</th>
                            <td width="50%">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Payment Method') :</th>
                            <td width="50%">{{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('Voucher No') :</th>
                            <td width="50%">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Paid On') :</th>
                            <td width="50%">
                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) . ' ' . date($timeFormat, strtotime($payment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Payment Note') :</th>
                            <td width="50%">
                                {{ $payment->note }}
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
                    <th width="50%">@lang('Signature Of Authority')</th>
                    <th width="50%" class="text-end">@lang('Signature Of Receiver')</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->invoice_id , $generator::TYPE_CODE_128)) }}">
                        <p>{{ $payment->invoice_id }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center"><small>@lang('Software by MetaShops'). Ltd.</small></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

