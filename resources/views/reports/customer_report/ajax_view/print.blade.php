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
    $allTotalSale = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</h5>
        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <h6 style="margin-top: 10px;"><b>@lang('Customer Report') </b></h6>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Customer')</th>
                    <th class="text-end">@lang('Total Sale')</th>
                    <th class="text-end">@lang('Total Paid')</th>
                    <th class="text-end">@lang('Opening Balance Due')</th>
                    <th class="text-end">@lang('Total Due')</th>
                    <th class="text-end">@lang('Total Return Due')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($customerReports as $report)
                    @php
                        $allTotalSale += $report->total_sale;
                        $allTotalPaid += $report->total_paid;
                        $allTotalOpDue += $report->opening_balance;
                        $allTotalDue += $report->total_sale_due;
                        $allTotalReturnDue += $report->total_sale_return_due;
                    @endphp
                    <tr>
                        <td class="text-start">{!! $report->name.'(<b>'.$report->phone.'</b>)' !!}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_sale) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_paid) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->opening_balance) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_sale_due) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($report->total_sale_return_due) }}</td>
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
            <tbody>
                <tr>
                    <th class="text-end">@lang('Opening Balance Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalOpDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalSale) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Sale Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('Total Returnable Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturnDue) }}</td>
                </tr>
            </tbody>
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