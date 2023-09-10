
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
    <div class="col-md-12 text-center">
        @if (auth()->user()->branch_id)
            <h6>{{ auth()->user()->branch->name }}</h6>
            <p style="width: 60%; margin:0 auto;">{{ auth()->user()->branch->city.', '.auth()->user()->branch->state.', '.auth()->user()->branch->zip_code.','.auth()->user()->branch->country }}</p>
        @else
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('Date') :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif

        <p><b>@lang('Account Ledger') </b></p>
    </div>
</div>

<div class="account_details_area mt-1">
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
                <li><strong>@lang('Account Type') : </strong> {{ App\Utils\Util::accountType($account->account_type) }}</li>
                <li><strong>@lang('Account Name') : </strong> {{ $account->name }}</li>
                <li><strong>@lang('Bank') : </strong> {{ $account->bank_name }}</li>
                <li><strong>@lang('Balance') : </strong> {{ App\Utils\Converter::format_in_bdt($account->balance) }}</li>
            </ul>
        </div>
    </div>
</div>
@php

    $balanceType = $accountUtil->accountBalanceType($account->account_type);

    $totalDebit = 0;
    $totalCredit = 0;
@endphp
<div class="row mt-1">
    <div class="col-12" >
        <table class="table modal-table table-sm table-bordered" >
            <thead>
                <tr>
                    <th class="text-start">@lang('Date')</th>
                    <th class="text-start">@lang('Perticulars')</th>
                    <th class="text-start">@lang('Voucher/Invoice')</th>
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

                        if($i == 0) {

                            if ($balanceType == 'debit') {

                                $previousBalance = $debit - $credit;
                            }elseif($balanceType == 'credit') {

                                $previousBalance = $credit - $debit;
                            }
                        } else {

                            if ($balanceType == 'debit') {

                                $previousBalance = $previousBalance + ($debit - $credit);
                            }elseif($balanceType == 'credit') {

                                $previousBalance = $previousBalance + ($credit - $debit);
                            }
                        }
                    @endphp

                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ date($__date_format, strtotime($row->date)) }}
                        </td>

                        <td class="text-start">
                            @php
                                $type = $accountUtil->voucherType($row->voucher_type);
                                $des = $row->{$type['pur']} ? '/' . $row->{$type['pur']} : '';
                                $receiver_ac = $row->receiver_acn ? '/To:<b>'.$row->receiver_acn.'</b>' : '';
                                $sender_ac = $row->sender_acn ? '/From:<b>'.$row->sender_acn.'</b>' : '';
                            @endphp

                            {!! '<b>' . $type['name'] . '</b>' .$receiver_ac.$sender_ac.$des !!}
                        </td>

                        <td class="text-start">
                            @php
                                $type = $accountUtil->voucherType($row->voucher_type);
                            @endphp

                            {{ $row->{$type['voucher_no']} }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->debit) }}
                            @php
                                $totalDebit += $row->debit;
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
                        <strong>@lang('Total Debit') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                    </td>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalDebit) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('Total Credit') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                    </td>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalCredit) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Closing Balance') :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}</td>
                    <td class="text-end">
                        @php

                            $closingBalance = 0;

                            if ($balanceType == 'debit') {

                                $closingBalance = $totalDebit - $totalCredit;
                            }elseif ($balanceType == 'credit') {

                                $closingBalance = $totalCredit - $totalDebit;
                            }
                        @endphp

                        {{ App\Utils\Converter::format_in_bdt($closingBalance) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
