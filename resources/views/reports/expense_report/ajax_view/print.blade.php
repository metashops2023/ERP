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
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
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
            <p><b>@lang('Date') :</b>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('Expense Report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Reference No')</th>
                    <th class="text-start">@lang('Description')</th>
                    <th class="text-start">@lang('B.Location')</th>
                    <th class="text-start">@lang('Expense For')</th>
                    <th class="text-start">@lang('Total Amount')</th>
                    <th class="text-start">@lang('Paid')</th>
                    <th class="text-start">@lang('Due')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($expenses as $ex)
                    @php
                        $totalExpense += $ex->net_total_amount;
                        $totalPaid += $ex->paid;
                        $totalDue += $ex->due;
                    @endphp
                    <tr>
                        <td class="text-start">
                            {{ date($__date_format, strtotime($ex->date)) }}
                        </td>
                        
                        <td class="text-start">{{ $ex->invoice_id }}</td>

                        <td class="text-start">
                            @php
                                $expenseDescriptions = DB::table('expense_descriptions')
                                    ->where('expense_id', $ex->id)
                                    ->leftJoin('expense_categories', 'expense_descriptions.expense_category_id', 'expense_categories.id')
                                    ->select('expense_categories.name', 'expense_categories.code', 'expense_descriptions.amount')
                                    ->get();
                            @endphp
                            @foreach ($expenseDescriptions as $exDescription)
                                {!! '<b>' . $exDescription->name . '(' . $exDescription->code . '):</b>'. $exDescription->amount !!} <br>
                            @endforeach
                        </td>

                        <td class="text-start">
                            @if ($ex->branch_name)
                                {!! $ex->branch_name . '/' . $ex->branch_code . '(<b>BR</b>)' !!}
                            @else
                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td>{{ $ex->cr_prefix . ' ' . $ex->cr_name . ' ' . $ex->cr_last_name }}</td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->net_total_amount) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->paid) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->due) }}</td>
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
                    <th class="text-end">@lang('Total Expense') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Paid') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalPaid) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalDue) }}
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
