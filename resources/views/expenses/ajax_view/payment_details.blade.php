@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->expense->branch)
                        {{ $payment->expense->branch->name . '/' . $payment->expense->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>@lang('Head Office')</b>)
                    @endif
                </b>
            </h3>
            <p>
                @if ($payment->expense->branch)
                    {{ $payment->expense->branch->city . ', ' . $payment->expense->branch->state . ', ' . $payment->expense->branch->zip_code . ', ' . $payment->expense->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <h6><b>@lang('Expense Voucher')</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <p><b>@lang('Reference No') :</b> {{ $payment->expense->invoice_id }}</p>
                <p><b>@lang('Voucher No') :</b> {{ $payment->invoice_id }}</p>
            </div>

            <div class="col-md-6 text-end">
                <p><b>@lang('Date') :</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date))  }}</p>
            </div>
        </div>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-12">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">@lang('Expense For'):</th>
                            <td class="text-end">{{ $payment->expense->admin ? $payment->expense->admin->prefix.' '.$payment->expense->admin->name.' '.$payment->expense->admin->last_name : 'N/A' }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Description'):</th>
                            <th class="text-end">@lang('Amount')</th>
                        </tr>

                        @foreach ($payment->expense->expense_descriptions as $expense_description)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}. {{ $expense_description->category->name }}</td>
                                <td class="text-end">{{ json_decode($generalSettings->business, true)['currency'] }} {{ $expense_description->amount }}</td>
                            </tr>
                        @endforeach
                       
                        <tr>
                            <th class="text-start">@lang('Total') :</th>
                            <td class="text-end"><b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $payment->expense->net_total_amount }}</b></td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Paid'):</th>
                            <td class="text-end">
                               <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $payment->paid_amount }}</b> 
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('In Word') :</th>
                            <td class="text-end"><span id="inword"></span></td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Method') :</th>
                            <td class="text-end">
                                @if ($payment->payment_method)
                                      {{ $payment->payment_method->name }}
                                @else 
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Note') :</th>
                            <td class="text-end">{{ $payment->note }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-none">
        <table class="w-100 mt-5">
            <tbody>
                <tr>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('Receiver')</p> </th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('Made By')</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('Account Manger')</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('Authority')</p></th>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        <img style="width: 170px; height:40px;" class="mt-3" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </td>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        {{ $payment->invoice_id }}
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="4" class="text-navy-blue text-center"><small>@lang('Software by') <b>@lang('MetaShops Pvt'). Ltd.</b></small> </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
        'nineteen'
    ];

    function convert_millions(num) {
        if (num >= 100000) {
            return convert_millions(Math.floor(num / 100000)) + " Lack " + convert_thousands(num % 1000000);
        } else {
            return convert_thousands(num);
        }
    }

    function convert_thousands(num) {
        if (num >= 1000) {
            return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
        } else {
            return convert_hundreds(num);
        }
    }

    function convert_hundreds(num) {
        if (num > 99) {
            return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
        } else {
            return convert_tens(num);
        }
    }

    function convert_tens(num) {
        if (num < 10) return ones[num];
        else if (num >= 10 && num < 20) return teens[num - 10];
        else {
            return tens[Math.floor(num / 10)] + " " + ones[num % 10];
        }
    }

    function convert(num) {
        if (num == 0) return "zero";
        else return convert_millions(num);
    }

    document.getElementById('inword').innerHTML = convert(parseInt("{{ $payment->paid_amount }}")).replace(
        'undefined', '(some Penny)').toUpperCase() + ' ONLY.';
</script>