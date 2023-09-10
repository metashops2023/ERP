@php
   use Carbon\Carbon;
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, }
        td    { font-size: 10px!important; }
        th    { font-size: 10px!important; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
    /* .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;} */
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
        <h6 style="margin-top: 10px;"><b>@lang('Cash Register Report') </b></h6>
    </div>
</div>
<br>
@php
    $totalSaleAmount = 0;
    $totalReceivedAmount = 0;
    $totalDueAmount = 0;
    $totalClosedAmount = 0;
@endphp
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Open Time')</th>
                    <th class="text-start">@lang('Closed Time')</th>
                    <th class="text-start">@lang('Business Location')</th>
                    <th class="text-start">@lang('User')</th>
                    <th class="text-start">@lang('Status')</th>
                    <th class="text-end">@lang('Total Sale')</th>
                    <th class="text-end">@lang('Total Paid')</th>
                    <th class="text-end">@lang('Total Due')</th>
                    <th class="text-end">@lang('Closing Amount')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($cashRegisters as $row)
                    <tr>
                        <td class="text-start">
                            {{ Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('jS M, Y h:i A') }}
                        </td>

                        <td class="text-start">
                            @if ($row->closed_at)
                                 {{ Carbon::createFromFormat('Y-m-d H:i:s', $row->closed_at)->format('jS M, Y h:i A') }}
                            @endif
                        </td>

                        <td class="text-start">
                            @if ($row->b_name)

                                {!! $row->b_name.'/'.$row->b_code.'(<b>BL</b>)' !!}
                            @else

                                {!! json_decode($generalSettings->business, true)['shop_name'].'(<b>HO</b>)' !!}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ $row->u_prefix . ' ' . $row->u_first_name . ' ' . $row->u_last_name }}
                        </td>

                        <td class="text-start">
                            @if ($row->status == 1 )
                                <span class="text-success">@lang('Open')</span>
                            @else
                                <span class="text-danger">@lang('Closed')</span>
                            @endif
                        </td>

                        <td class="text-end">
                            @php
                                $__totalSale = $row->total_sale ? $row->total_sale : 0;
                                $totalSaleAmount += $__totalSale;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalSale) }}
                        </td>

                        <td class="text-end">
                            @php
                                $__totalPaid = $row->total_paid ? $row->total_paid : 0;
                                $totalReceivedAmount += $__totalPaid;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalPaid) }}
                        </td>

                        <td class="text-end">
                            @php
                                $__totalDue= $row->total_due ? $row->total_due : 0;
                                $totalDueAmount += $__totalDue;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalDue) }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->closed_amount) }}
                            @php
                                $totalClosedAmount += $row->closed_amount;
                            @endphp
                        </td>
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
                    <th class="text-end"> @lang('All Total Sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalSaleAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('All Total Paid') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('All Total Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalDueAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('All Total Closing Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalClosedAmount) }}
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
