@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->purchase->branch)
                        {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                    @endif
                </b>
            </h3>

            <h6>
                @if ($payment->purchase->branch)
                    {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    (<b>@lang('Branch/Concern')</b>) ,<br>
                    {{ $payment->purchase->branch ? $payment->purchase->branch->city : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->state : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->zip_code : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->country : '' }}.
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }} <br>
                    <b>@lang('Phone')</b> : {{ json_decode($generalSettings->business, true)['phone'] }} <br>
                    <b>@lang('Email')</b> : {{ json_decode($generalSettings->business, true)['email'] }} <br>
                @endif
            </h6>
            <h6>@lang('Payment Details')</h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6 class="text-navy-blue"><b>@lang('Title') :</b>
            @if ($payment->is_advanced == 1)
                <b>@lang('PO Advance Payment')</b>
            @else
                {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
            @endif
        </h6>
        <h6 class="text-navy-blue"><b>@lang('P.Invoice ID') :</b> {{ $payment->purchase->invoice_id }}</h6>
        <h6 class="text-navy-blue"><b>@lang('Supplier') :</b> {{ $payment->purchase->supplier->name }}</h6>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th class="text-start" width="50%">@lang('Paid Amount') :</th>
                            <td width="50%">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Payment Method') :</th>
                            <td width="50%">
                                @if ($payment->paymentMethod)
                                    {{ $payment->paymentMethod->name }}
                                @else
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('Voucher No') :</th>
                            <td width="50%">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">@lang('Paid On') :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date))  . ' ' . date($timeFormat, strtotime($payment->time)) }}
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
    <br>

    <div class="signature_area pt-5 mt-5 d-none">
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">@lang('Signature Of Authority')</th>
                    <th width="50%" class="text-end">@lang('Signature Of Receiver')</th>
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
