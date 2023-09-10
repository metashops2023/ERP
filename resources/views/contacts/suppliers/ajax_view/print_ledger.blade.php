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
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} </h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>

            @if ($addons->branches == 1)

                <p><strong>@lang('All Business Location')</strong></p>
            @endif

        @elseif ($branch_id == 'NULL')

            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} </h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else

            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('Date') :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif
        <p><b>@lang('Supplier Ledger') </b></p>
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
@php
    $totalDebit = 0;
    $totalCredit = 0;
    $totalLess = 0;
@endphp
<div class="row mt-1">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Particulars')</th>
                    <th class="text-start">@lang('Voucher/P').Invoice</th>
                    <th class="text-end">@lang('Debit')</th>
                    <th class="text-end">@lang('Credit')</th>
                    <th class="text-end">@lang('Running Balance')</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $previousBalance = 0;
                    $i = 0;
                @endphp

                @foreach ($ledgers as $row)
                    @php
                        $debit = $row->debit;
                        $credit = $row->credit;
                        $less = $row->less_amount;

                        if($i == 0) {

                            $previousBalance =  $credit - $debit;
                        } else {

                            $previousBalance = $previousBalance + ($credit - ($debit + $less));
                        }
                    @endphp

                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ date($__date_format, strtotime($row->report_date)) }}
                        </td>

                        <td class="text-start">
                            @php
                                $type = $supplierUtil->voucherType($row->voucher_type);
                                $__agp = $row->agp_purchase ? '/' . 'AGP: ' . $row->agp_purchase : '';
                                $__less = $row->less_amount > 0 ? '/' . 'Less:(<b class="text-success">' . $row->less_amount . '</b>)' : '';
                                $particulars = '<b>' . $type['name'] . '</b>' . $__agp. $__less . ($row->{$type['par']} ? '/' . $row->{$type['par']} : '');
                            @endphp

                            {!! $particulars !!}
                        </td>

                        <td class="text-start">
                            @php
                                $type = $supplierUtil->voucherType($row->voucher_type);
                            @endphp

                            {{ $row->{$type['voucher_no']} }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->debit) }}
                            @php
                                $totalDebit += $row->debit;
                                $totalLess += $row->less_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->credit) }}
                            @php
                                $totalCredit += $row->credit;
                            @endphp
                        </td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($previousBalance) }}</td>
                    </tr>
                    @php $i++; @endphp
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
                    <td class="text-end">
                        <strong>@lang('Total Credit') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                    </td>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalCredit) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('Total Debit') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                    </td>
                    <td class="text-end">
                        ({{ App\Utils\Converter::format_in_bdt($totalDebit) }})
                    </td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('Total Less') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                    </td>
                    <td class="text-end">
                        ({{ App\Utils\Converter::format_in_bdt($totalLess) }})
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Closing Balance') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}</td>
                    <td class="text-end">
                        @php
                            $closingBalance = $totalCredit - ($totalDebit + $totalLess);
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($closingBalance) }}
                    </td>
                </tr>
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
