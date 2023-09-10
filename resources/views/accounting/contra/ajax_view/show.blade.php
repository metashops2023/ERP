@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="contra_print_area">
    <div class="header_area d-none">
        <div class="company_name text-center">
            <h6>
                <b>
                    @if ($contra->branch)

                        {{ $contra->branch->name . '/' . $contra->branch->branch_code }}
                    @else

                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                    @endif
                </b>
            </h6>
            <h6>
                @if ($contra->branch)

                    {{ $contra->branch->city . ', ' . $contra->branch->state . ', ' . $contra->branch->zip_code . ', ' . $contra->branch->country }}
                @else

                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </h6>
            <h6>@lang('Contra Details')</h6>
        </div>
    </div>

    <div class="reference_area">
        <p><b>@lang('Title') :</b> @lang('Contra Entry')</p>
        <p><b>@lang('Voucher No') :</b> {{ $contra->voucher_no }}</p>
        <p><b>@lang('Date') :</b> {{ $contra->date}}</p>
        <p><b>@lang('Business Location') :</b>
            @if ($contra->branch)

                {{ $contra->branch->name . '/' . $contra->branch->branch_code }}
            @else

                {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
            @endif
        </p>
        <p><b>@lang('Entered By') :</b> {{ $contra->user ? $contra->user->prefix.' '.$contra->user->name.' '.$contra->user->last_name : '' }}</p>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th class="text-start">Sender Account :

                            </th>
                            <td class="text-start">
                                @php
                                    $senderAccountType = $contra->senderAccount->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                    $senderAccountBank = $contra->senderAccount->bank ? ', BK : '.$contra->senderAccount->bank->name : '';
                                    $sender_ac_no = $contra->senderAccount->account_number ? ', A/c No : '.$contra->senderAccount->account_number : '';
                                @endphp
                                {{ $contra->senderAccount->name.$senderAccountType.$senderAccountBank.$sender_ac_no }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Receiver Account') : </th>
                            <td class="text-start">
                                @php
                                    $receiverAccountType = $contra->receiverAccount->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                    $receiverAccountBank = $contra->receiverAccount->bank ? ', BK : '.$contra->receiverAccount->bank->name : '';
                                    $receiver_ac_no = $contra->receiverAccount->account_number ? ', A/c No : '.$contra->receiverAccount->account_number : '';
                                @endphp
                                {{ $contra->receiverAccount->name.$receiverAccountType.$receiverAccountBank.$receiver_ac_no }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Transaction Amount') :</th>
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ App\Utils\Converter::format_in_bdt($contra->amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('Remarks') :</th>
                            <td width="50%" class="text-start">
                                {{ $contra->remarks }}
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
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($contra->voucher_no, $generator::TYPE_CODE_128)) }}">
                        <p>{{ $contra->voucher_no }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">@lang('Software by MetaShops'). Ltd.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
