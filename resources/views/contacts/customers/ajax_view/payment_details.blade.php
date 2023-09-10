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
@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="sale_payment_print_area">
    <div class="header_area d-none">
        <div class="company_name text-center">
            <div class="company_name text-center">
                <h4>
                    @if ($customerPayment->branch)

                        @if ($customerPayment->branch->logo != 'default.png')
                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $customerPayment->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $customerPayment->branch->name }}</span>
                        @endif
                    @else

                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </h4>

                <p>
                    @if ($customerPayment->branch)

                        <p style="width: 60%; margin:0 auto;">{{ $customerPayment->branch->city . ', ' . $customerPayment->branch->state . ', ' . $customerPayment->branch->zip_code . ', ' . $customerPayment->branch->country }}</p>
                    @else

                        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                    @endif
                </p>

                <h6 style="margin-top: 10px;">@lang('Payment Receive Voucher')</h6>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-6">
            <p><strong>@lang('Customer') :</strong> {{ $customerPayment->customer->name }}</p>
            <p><strong>@lang('Phone') :</strong> {{ $customerPayment->customer->phone }}</p>
            <p><strong>@lang('Address') :</strong> {{ $customerPayment->customer->address }}</p>
        </div>

        <div class="col-6">
            <p><strong>@lang('Type') :</strong>
                {{ $customerPayment->type == 1 ? 'Receive Payment' : 'Refund' }}
            </p>
        </div>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start">
                                <strong>@lang('Paid Amount') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                            </td>

                            <td width="50%" class="text-start">
                                {{ App\Utils\Converter::format_in_bdt($customerPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('Debit Account') :</strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->account ? $customerPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong> @lang('Payment Method') :</strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->paymentMethod ? $customerPayment->paymentMethod->name : $customerPayment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('Voucher No') :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('Reference') :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->reference }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('Paid On') :</strong></td>
                            <td width="50%" class="text-start">
                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($customerPayment->date)) . ' ' . date($timeFormat, strtotime($customerPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('Payment Note') :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (count($customerPayment->customer_payment_invoices))
        <div class="row">
            <div class="col-12">
                <div class="heading_area">
                    <p><strong>{{ $customerPayment->type == 1 ? 'RECEIVED AGAINST SALES/ORDERS:' : 'PAYMENT AGAINST RETURN INVOICES :' }} </strong></p>
                </div>
            </div>
            <div class="col-12">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('Sale Date')</th>
                            <th class="text-start">@lang('Sale Invoice ID')</th>
                            <th class="text-start">@lang('Paid Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($customerPayment->customer_payment_invoices as $pi)
                            @if ($pi->sale)
                                <tr>
                                    <td class="text-start">
                                        {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->sale->date)) }}
                                    </td>
                                    <td class="text-start">{{ $pi->sale->invoice_id }}</h6></td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                        @php $total += $pi->paid_amount; @endphp
                                    </td>
                                </tr>
                            @elseif($pi->sale_return)
                                <tr>
                                    <td class="text-start">
                                        {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->sale_return->date)) }}
                                    </td>
                                    <td class="text-start">{{ $pi->sale_return->invoice_id }}</h6></td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                        @php $total += $pi->paid_amount; @endphp
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">@lang('Total') : </th>
                            <th class="text-start">{{ App\Utils\Converter::format_in_bdt($total) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    <div class="footer_area d-none">
        <br><br>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('RECEIVED BY')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('PREPARED BY')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('AUTHORIZED BY')</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($customerPayment->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $customerPayment->voucher_no }}</p>
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
    </div>
</div>
