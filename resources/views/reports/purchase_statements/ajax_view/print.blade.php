<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
    th { font-size:9px!important; font-weight: 550!important;}
    td { font-size:8px;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
            <p style="width: 60%; margin:0 auto;">
                {{ json_decode($generalSettings->business, true)['address'] }}
            </p>
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

        <h6 style="margin-top: 10px;"><b>@lang('Purchase Statements') </b></h6>
    </div>
</div>
<br>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

    $totalItems = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalPurchaseAmount = 0;
    $TotalPaid = 0;
    $TotalReturnedAmount = 0;
    $TotalDue = 0;
@endphp
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Invoice ID')</th>
                    <th class="text-start">@lang('Stock Location')</th>
                    <th class="text-start">@lang('Customer')</th>
                    <th class="text-start">@lang('Entered By')</th>
                    <th class="text-end">@lang('Total Item')</th>
                    <th class="text-end">@lang('Net total Amt').</th>
                    <th class="text-end">@lang('Order Discount')</th>
                    <th class="text-end">@lang('Order Tax')</th>
                    <th class="text-end">@lang('Total Purchase Amt').</th>
                    <th class="text-end">@lang('Paid Amt').</th>
                    <th class="text-end">@lang('Return Amount')</th>
                    <th class="text-end">@lang('Due Amt').</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($purchases as $purchase)
                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($purchase->date))}}</td>
                        <td class="text-start">{{ $purchase->invoice_id }}</td>
                        <td class="text-start">
                            @if ($purchase->branch_name)

                                 {!! $purchase->branch_name . '/' . $purchase->branch_code . '(<b>BL</b>)' !!}
                            @else

                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ $purchase->supplier_name ? $purchase->supplier_name : 'Walk-In-Customer' }}
                        </td>

                        <td class="text-start">
                            {{ $purchase->created_prefix . ' ' . $purchase->created_name . ' ' . $purchase->created_last_name }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->total_item) }}
                            @php
                                $totalItems += $purchase->total_item;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                            @php
                                $TotalNetTotal += $purchase->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                            @php
                                $TotalOrderDiscount += $purchase->order_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->purchase_tax_amount). '(' . $purchase->purchase_tax_percent . '%)' }}
                            @php
                                $TotalOrderTax += $purchase->purchase_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                            @php
                                $TotalPurchaseAmount += $purchase->total_purchase_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                            @php
                                $TotalPaid += $purchase->paid;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->purchase_return_amount) }}
                            @php
                                $TotalReturnedAmount += $purchase->purchase_return_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                            @php
                                $TotalDue += $purchase->due;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>

                <tr>
                    <th class="text-end">@lang('Total Item') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalItems) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Net Total Amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Order Discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Order Tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Purchased Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalPurchaseAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalPaid) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnedAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalDue) }}
                    </td>
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
