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
@php
    $totalQty = 0;
    $totalUnitCost = 0;
    $totalSubTotal = 0;
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
        <h6 style="margin-top: 10px;"><b>@lang('Product Purchase Report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Product')</th>
                    <th class="text-start">@lang('P.Code')(SKU)</th>
                    <th class="text-start">@lang('Supplier')</th>
                    <th class="text-start">@lang('P.Invoice ID')</th>
                    <th class="text-start">@lang('Qty')</th>
                    <th class="text-end">@lang('Unit Cost')({{json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-end">@lang('SubTotal')({{json_decode($generalSettings->business, true)['currency']}})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($purchaseProducts as $pProduct)
                    <tr>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($pProduct->report_date)) }}</td>
                        <td class="text-start">
                            @php
                                $variant = $pProduct->variant_name ? ' - ' . $pProduct->variant_name : '';
                                $totalQty += $pProduct->quantity;
                                $totalUnitCost += $pProduct->net_unit_cost;
                                $totalSubTotal += $pProduct->line_total;
                            @endphp
                           {{ $pProduct->name . $variant }}
                        </td>
                        <td class="text-start">{{ $pProduct->variant_code ? $pProduct->variant_code : $pProduct->product_code}}</td>
                        <td class="text-start">{{ $pProduct->supplier_name }}</td>
                        <td class="text-start">{{ $pProduct->invoice_id }}</td>
                        <td class="text-start">{!! $pProduct->quantity . ' (<span class="qty" data-value="' . $pProduct->quantity . '">' . $pProduct->unit_code . '</span>)' !!}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($pProduct->net_unit_cost) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($pProduct->line_total) }}</td>
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
                    <th class="text-end">@lang('Total Quantity') :</th>
                    <td class="text-end">{{ bcadd($totalQty, 0, 2) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalUnitCost) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Net Total Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSubTotal) }}</td>
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