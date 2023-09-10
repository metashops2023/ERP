<div class="sale_and_expense_sum_area">
    <div class="card-body card-custom">

        <div class="heading mb-1">
            <h6 class="text-navy-blue">@lang('Overall (Output - Input - Expense)') </h6>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tax_sum">
                    <h6 class="text-muted">Output Tax - Input Tax - Expense Tax :
                        {{ json_decode($generalSettings->business, true)['currency'] }} <span id="tax_sum"></span>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="user_sale_and_expense_list mt-1">
    <div class="card">
        <div class="card-body">
            <!--begin: Datatable-->
            <div class="tab_list_area">
                <ul class="list-unstyled">
                    <li>
                        <a id="tab_btn" data-show="purchase" class="tab_btn tab_active" href="#"><i
                                class="fas fa-info-circle"></i> @lang('Input Tax')</a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="sale" class="tab_btn" href="#">
                            <i class="fas fa-scroll"></i>@lang('Output Tax')</a>
                    </li>

                    <li>
                        <a id="tab_btn" data-show="expense" class="tab_btn" href="#">
                            <i class="fas fa-scroll"></i>@lang('Expense Tax')</a>
                    </li>
                </ul>
            </div>

            <div class="tab_contant purchase mt-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="display data_tbl data__table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Invoice ID')</th>
                                        <th>@lang('Supplier')</th>
                                        <th>@lang('Tax Number')</th>
                                        <th>@lang('Total Amount')</th>
                                        <th>@lang('Discount')</th>
                                        <th>@lang('Tax Percent')</th>
                                        <th>@lang('Tax Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPurchaseAmount = 0;
                                        $totalPurchaseTax = 0;
                                    @endphp
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($purchase->date)) }}</td>
                                            <td>{{ $purchase->invoice_id }}</td>
                                            <td>{{ $purchase->supplier_name }}</td>
                                            <td>{{ $purchase->tax_number }}</td>
                                            <td>{{ json_decode($generalSettings->business, true)['currency'] . ' ' . $purchase->net_total_amount }}
                                            </td>
                                            <td>{{ json_decode($generalSettings->business, true)['currency'] . ' ' . $purchase->order_discount_amount }}
                                            </td>
                                            <td>({{ $purchase->purchase_tax_percent }}%)</td>
                                            <td>{{ json_decode($generalSettings->business, true)['currency'] . ' ' . $purchase->purchase_tax_amount }}
                                            </td>
                                            @php
                                                $totalPurchaseAmount += $purchase->net_total_amount;
                                                $totalPurchaseTax += $purchase->purchase_tax_amount;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th colspan="4" class="text-white"><b>@lang('Total') :</b></th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $totalPurchaseAmount, 2, '.', '') }}</b>
                                        </th>
                                        <th  class="text-white"> </th>
                                        <th  class="text-white"> </th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $totalPurchaseTax, 2, '.', '') }}</b>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab_contant sale d-none mt-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="display data_tbl data__table table-striped" id="s-tax">
                                <thead>
                                    <tr class="bg-navey-blue">
                                        <th>@lang('Date')</th>
                                        <th>@lang('Invoice ID')</th>
                                        <th>@lang('Customer')</th>
                                        <th>@lang('Tax Number')</th>
                                        <th>@lang('Total Amount')</th>
                                        <th>@lang('Discount')</th>
                                        <th>@lang('Tax Percent')</th>
                                        <th>@lang('Tax Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSaleAmount = 0;
                                        $TotalsaleTax = 0;
                                    @endphp
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td class="text-navy-blue">{{ date('d/m/Y', strtotime($sale->date)) }}</td>

                                            <td class="text-navy-blue">{{ $sale->invoice_id }}</td>

                                            <td class="text-navy-blue">{{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}</td>
                                            <td class="text-navy-blue">{{ $sale->tax_number }}</td>
                                            <td class="text-navy-blue">
                                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $sale->net_total_amount }}
                                            </td>
                                            <td class="text-navy-blue">
                                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $sale->order_discount_amount }}
                                            </td>
                                            <td class="text-navy-blue">
                                                ({{ $sale->order_tax_percent }}%)
                                            </td>
                                            <td class="text-navy-blue">
                                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $sale->order_tax_amount }}
                                                @php
                                                    $totalSaleAmount += $sale->net_total_amount;
                                                    $TotalsaleTax += $sale->order_tax_amount;
                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th colspan="4" class="text-center text-white"><b>@lang('Total') :</b></th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $totalSaleAmount, 2, '.', '') }}</b>
                                        </th>
                                        <th  class="text-white"> </th>
                                        <th  class="text-white"> </th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $TotalsaleTax, 2, '.', '') }}</b>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab_contant expense d-none mt-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="display data_tbl data__table table-striped" id="e-tax">
                                <thead>
                                    <tr class="bg-navey-blue">
                                        <th>@lang('Date')</th>
                                        <th>@lang('Invoice ID')</th>
                                        <th>@lang('Total Amount')</th>
                                        <th>@lang('Tax Percent')</th>
                                        <th>@lang('Tax Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalExpense = 0;
                                        $totalExpenseTax = 0;
                                    @endphp
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td class="text-navy-blue">{{ date('d/m/Y', strtotime($expense->date)) }}</td>
                                            <td class="text-navy-blue">{{ $expense->invoice_id }}</td>
                                            <td class="text-navy-blue">
                                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $expense->total_amount }}
                                            </td>
                                            <td class="text-navy-blue">({{ $expense->tax_percent }}%)</td>
                                            @php
                                                $taxAmount = $expense->total_amount / 100 * $expense->tax_percent;
                                            @endphp
                                            <td class="text-navy-blue">
                                                {{ json_decode($generalSettings->business, true)['currency'].' '.$taxAmount}}
                                                @php
                                                    $totalExpense += $expense->total_amount;
                                                    $totalExpenseTax += $taxAmount;
                                                @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th colspan="2" class="text-center text-white"><b>@lang('Total') :</b></th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $totalExpense, 2, '.', '') }}</b>
                                        </th>
                                        <th  class="text-white"> </th>
                                        <th  class="text-white">
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ number_format((float) $totalExpenseTax, 2, '.', '') }}</b>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@php
    $tax_sum = $TotalsaleTax - $totalPurchaseTax - $totalExpenseTax;
@endphp
<script>
    $('.data_tbl').DataTable();
    $('#s-tax').DataTable();
    $('#e-tax').DataTable();
</script>
<!--Data table js active link end-->
<script>
    document.getElementById('tax_sum').innerHTML = parseFloat({{$tax_sum}}).toFixed(2);
</script>
