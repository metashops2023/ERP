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
        <h6 style="margin-top: 10px;">@lang('Payroll Report')</h6>
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
                        <th class="text-start">@lang('Department')</th>
                        <th class="text-start">@lang('Month/Year')</th>
                        <th class="text-start">@lang('Reference No')</th>
                        <th class="text-start">@lang('Gross Amount')</th>
                        <th class="text-start">@lang('Paid')</th>
                        <th class="text-start">@lang('Due')</th>
                        <th class="text-start">@lang('Payment Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_gross = 0;
                        $total_paid = 0;
                        $total_due = 0;
                    @endphp
                    @foreach ($payrolls as $row)
                        @php
                            $total_gross += $row->gross_amount;
                            $total_paid += $row->paid;
                            $total_due += $row->due;
                        @endphp
                        <tr>
                            <td class="text-start">{{ date('d/m/Y', strtotime($row->date)) }}</td>
                            <td class="text-start">{{ $row->emp_prefix.' '.$row->emp_name.' '.$row->emp_last_name }}-{{ $row->emp_id }}</h6></td>
                            <td class="text-start">{{ $row->department_name }}</td>
                            <td class="text-start">{{ $row->month }}/{{ $row->year }}</td>
                            <td class="text-start">{{ $row->reference_no }}</td>
                            <td class="text-start">{{ $row->gross_amount }}</td>
                            <td class="text-start">{{ $row->paid }}</td>
                            <td class="text-start">{{ $row->due }}</td>
                            <td class="text-start">
                                @if ($row->due <= 0)
                                    Paid
                                @elseif($row->due > 0 && $row->due < $row->gross_amount)
                                    Partial
                                @elseif($row->gross_amount == $row->due)
                                    Due
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4"></th>
                        <th>@lang('Total') : </th>
                        <th>${{ bcadd($total_gross, 0, 2) }}</th>
                        <th>${{ bcadd($total_paid, 0, 2) }}</th>
                        <th>${{ bcadd($total_due, 0, 2) }}</th>
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
