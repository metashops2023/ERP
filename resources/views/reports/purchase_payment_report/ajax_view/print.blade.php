<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php
    $totalPaid = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p><b>@lang('All Business Location')</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
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
        <h6 style="margin-top: 10px;"><b>@lang('Purchase Payment Report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Voucher No')</th>
                    <th class="text-start">@lang('Supplier')</th>
                    <th class="text-start">@lang('Pay Method')</th>
                    <th class="text-start">@lang('P.Invoice ID')</th>
                    <th class="text-end">@lang('Paid Amount')({{json_decode($generalSettings->business, true)['currency']}})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($payments as $payment)
                @php
                    $totalPaid += $payment->paid_amount;
                @endphp
                    <tr>
                        <td class="text-start">{{ $payment->payment_invoice }}</td>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($payment->date)) }}</td>
                        <td class="text-start">{{ $payment->supplier_name }}</td>
                        <td class="text-start">{{ $payment->pay_mode }}</td>
                        <td class="text-start">{{ $payment->purchase_invoice }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">@lang('Total Paid Amount') :</th>
                    <td class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($totalPaid) }}</td>
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