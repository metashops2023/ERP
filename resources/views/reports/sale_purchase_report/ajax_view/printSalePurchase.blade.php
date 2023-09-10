<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php
    $total_purchase = 0;
    $total_purchase_inc_tax = 0;
    $total_purchase_due = 0;
    $total_purchase_return = 0;

    $total_sale = 0;
    $total_sale_inc_tax = 0;
    $total_sale_due = 0;
    $total_sale_return = 0;

    foreach ($purchases as $purchase) {
        $total_purchase += $purchase->total_purchase_amount - $purchase->purchase_tax_amount;
        $total_purchase_inc_tax += $purchase->total_purchase_amount;
        $total_purchase_due += $purchase->due;
        $total_purchase_return += $purchase->purchase_return_amount;
    }

    foreach ($sales as $sale) {
        $total_sale += $sale->total_payable_amount - $sale->order_tax_amount;
        $total_sale_inc_tax += $sale->total_payable_amount;
        $total_sale_due += $sale->due > 0 ? $sale->due : 0;
        $total_sale_return += $sale->sale_return_amount > 0 ? $sale->sale_return_amount : 0;
    }

    $saleMinusPurchase = $total_sale_inc_tax - $total_sale_return - $total_purchase_inc_tax - $total_purchase_return;
    $saleDueMinusPurchaseDue = $total_sale_due - $total_purchase_due;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
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
            <p><b>@lang('Date') :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('Sale / Purcahse Report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="heading">
                    <h6 class="text-primary"><b>@lang('Purchases')</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">@lang('Total Purchase') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Purchase Including Tax') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase_inc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Purchase Return Including Tax') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase_return) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start"> @lang('Purchase Due') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase_due) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="heading">
                    <h6 class="text-primary"><b>@lang('Sales')</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-start">@lang('Total Sale') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Sale Including Tax') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale_inc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Sale Return Including Tax') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale_return) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('Sale Due') :</th>
                            <td class="text-end">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale_due) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="sale_purchase_due_compare_area">
        <div class="col-md-12">
            <div class="card-body card-custom">
                <div class="heading">
                    <h6 class="text-navy-blue">@lang('Overall (Sale - Sale Return - Purchase - Purchase Return)')</h6>
                </div>

                <div class="compare_area mt-3">
                    <h5 class="text-muted">Sale - Purchase :
                        <span class="{{ $saleMinusPurchase < 0 ? 'text-danger' : '' }}">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($saleMinusPurchase) }}
                        </span>
                    </h5>
                    <h5 class="text-muted">Due amount (Sale Due - Purchase Due) :
                        <span class="{{ $saleDueMinusPurchaseDue < 0 ? 'text-danger' : '' }}">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($saleDueMinusPurchaseDue) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
