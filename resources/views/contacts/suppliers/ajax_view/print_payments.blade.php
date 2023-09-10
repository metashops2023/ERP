<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 4%;margin-right: 4%;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="row">
    <div class="col-12 text-center">

        <h6 style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>

        @if ($fromDate && $toDate)
            <p><b>@lang('Date') :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif 

        <p class="mt-2"><b>@lang('Supplier Payments') </b></p> 
    </div>
</div>

<div class="supplier_details_area mt-1">
    <div class="row">
        <div class="col-8">
            <ul class="list-unstyled">
                <li><strong>@lang('Supplier') : </strong> {{ $supplier->name }} (ID: {{ $supplier->contact_id }})</li>
                <li><strong>@lang('Phone') : </strong> {{ $supplier->phone }}</li>
                <li><strong>@lang('Address') : </strong> {{ $supplier->address  }}</li> 
            </ul>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Voucher No')</th>
                    <th class="text-start">@lang('Reference')</th>
                    <th class="text-start">@lang('Against Invoice')</th>
                    {{-- <th>@lang('Created By')</th> --}}
                    <th class="text-start">@lang('Payment Status')</th>
                    <th class="text-start">@lang('Payment Type')</th>
                    <th class="text-start">@lang('Account')</th>
                    <th class="text-end">@lang('Less Amount')</th>
                    <th class="text-end">@lang('Paid Amount')</th>
                </tr>
            </thead>
        
            <tbody>
                @foreach ($payments as $row)
                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp
                            
                            {{ date($__date_format, strtotime($row->report_date)) }}
                        </td>

                        <td class="text-start">
                            {{ $row->supplier_payment_voucher . $row->purchase_payment_voucher }}
                        </td>

                        <td class="text-start">{{ $row->reference }}</td>

                        <td class="text-start">
                            @if ($row->purchase_inv || $row->return_inv) 

                                @if ($row->purchase_inv) 
        
                                    {{ 'Purchase : ' . $row->purchase_inv}}
                                @else 
        
                                    {{ 'Purchase Return : ' . $row->return_inv }}
                                @endif
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($row->voucher_type == 3 || $row->voucher_type == 5) 

                                {{ 'Payment' }}
                            @else 
        
                                {{ 'Return Payment' }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{  $row->sp_pay_mode . $row->sp_payment_method . $row->pp_pay_mode . $row->pp_payment_method }}
                        </td>

                        <td class="text-start">
                            @if ($row->sp_account) 

                                {{ $row->sp_account . '(A/C:' . $row->sp_account_number . ')' }}
                            @else 

                                {{ $row->pp_account . '(A/C:' . $row->pp_account_number . ')' }}
                            @endif
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->less_amount) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-12 text-center">
            <small>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small> 
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
    <small style="font-size: 5px;float:right;" class="text-end">
        Print Date: {{ date('d-m-Y , h:iA') }}
    </small>
</div>
