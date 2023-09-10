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
    
            @if ($s_date && $e_date)
                <p><b>@lang('Date') :</b>
                    {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($s_date)) }}
                    <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($e_date)) }}
                </p>
            @endif
            <h6 style="margin-top: 10px;">@lang('Payroll Payment Report')</h6>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">@lang('Date')</th>
                        <th class="text-start">@lang('Employee')</th>
                        <th class="text-start">@lang('Payment Voucher No')</th>
                        <th class="text-start">@lang('Paid')</th>
                        <th class="text-start">@lang('Pay For(Payroll)')</th>
                        <th class="text-start">@lang('Paid By')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_paid = 0;
                    @endphp
                    @foreach ($payrollPayments as $row)
                        @php
                            $total_paid += $row->paid;
                        @endphp
                        <tr>
                            <td class="text-start">{{ date('d/m/Y', strtotime($row->date)) }}</td>
                            <td class="text-start">{{ $row->prefix.' '.$row->name.' '.$row->last_name }}-{{ $row->emp_id }}</h6></td>
                            <td class="text-start">{{ $row->voucher_no }}</td>
                            <td class="text-start">{{ $row->paid }}</td>
                            <td class="text-start">{{ $row->reference_no }}</td>
                            <td class="text-start">{{ $row->pb_prefix.' '.$row->pb_name.' '.$row->pb_last_name }}-{{ $row->emp_id }}</h6></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-start"></th>
                        <th class="text-end">@lang('Total') : </th>
                        <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($total_paid, 0, 2) }}</th>
                        <th>--</th>
                        <th>--</th>
                    </tr>
                </tfoot>
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
