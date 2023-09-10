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
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="heading">
                        <h6 class="text-primary"><b>@lang('Purchases')</b></h6>
                    </div>

                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th>@lang('Total Purchase') :</th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_purchase) }}
                                </td>
                            </tr>

                            <tr>
                                <th>@lang('Purchase Including Tax') : </th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_purchase_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th>@lang('Purchase Return Including Tax') : </th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_purchase_return) }}
                                </td>
                            </tr>

                            <tr>
                                <th> @lang('Purchase Due'): </th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_purchase_due) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="heading">
                        <h6 class="text-primary"><b>@lang('Sales')</b></h6>
                    </div>

                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th>@lang('Total Sale') :</th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_sale) }}
                                </td>
                            </tr>

                            <tr>
                                <th>@lang('Sale Including Tax') : </th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_sale_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th>@lang('Sale Return Including Tax') : </th>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($total_sale_return) }}
                                </td>
                            </tr>

                            <tr>
                                <th> @lang('Sale Due'): </th>
                                <td>
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
