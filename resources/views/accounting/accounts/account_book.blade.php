@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
{{-- @section('title', 'Account Book - ') --}}
@section('content')
    @php
        $balanceType = $accountUtil->accountBalanceType($account->account_type);
    @endphp
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-book"></span>
                                <h5>@lang('Account Book')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <table class="table modal-table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td class="text-start"> <strong>@lang('Bank') :</strong> </td>
                                                    <td class="bank_name text-start">{{ $account->bank ? $account->bank->name .'('.$account->bank->branch_name.')' : '' }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start"> <strong>@lang('A/C Name') :</strong> </td>
                                                    <td class="account_name text-start">{{ $account->name }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start"><strong>@lang('A/C No'). :</strong></td>
                                                    <td class="account_number text-start">{{ $account->account_number }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start"><strong>@lang('A/C Type') :</strong></td>
                                                    <td class="account_type text-start">{{ App\Utils\Util::accountType($account->account_type) }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start"><strong>@lang('Balance') :</strong> </td>
                                                    <td class="account_balance text-start">{{ App\Utils\Converter::format_in_bdt($account->balance) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="sec-name mt-1 t">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>@lang('Filter')</b>
                                        <form id="filter_account_ledgers" method="get" class="px-2">
                                            <div class="form-group row mt-4">
                                                <div class="col-md-2">
                                                    <label><strong>@lang('Transaction Type') :</strong></label>
                                                    <select name="transaction_type" class="form-control submit_able" id="transaction_type" autofocus>
                                                        <option value=""><strong>@lang('All')</strong></option>
                                                        <option value="debit"><strong>@lang('Debit')</strong></option>
                                                        <option value="credit">@lang('Credit')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Voucher Type') :</strong></label>
                                                    <select name="voucher_type" class="form-control submit_able" id="voucher_type" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        @foreach (App\Utils\AccountUtil::voucherTypes() as $key => $type)
                                                            <option value="{{ $key }}">{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5 mt-3">
                                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report">
                                                                <i class="fas fa-print "></i> Print
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>@lang('Account Ledgers')</h6>
                                </div>
                            </div>
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('Date')</th>
                                                <th class="text-start">@lang('Perticulars')</th>
                                                <th class="text-start">@lang('Voucher/Invoice')</th>
                                                <th class="text-start">@lang('Debit')</th>
                                                <th class="text-start">@lang('Credit')</th>
                                                <th class="text-start">@lang('Running Balance')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="2" class="text-white text-end">@lang('Total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th class="text-white text-end"></th>
                                                <th id="debit" class="text-white text-end"></th>
                                                <th id="credit" class="text-white text-end"></th>
                                                <th id="due" class="text-white text-end">---</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        var account_ledger_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "searching" : false,
            language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo: "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty: "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu: "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary'},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary'},
            ],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('accounting.accounts.book', $account->id) }}",
                "data": function(d) {
                    d.voucher_type = $('#voucher_type').val();
                    d.transaction_type = $('#transaction_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [
                {data: 'date', name: 'account_ledgers.date'},
                {data: 'particulars', name: 'particulars'},
                {data: 'voucher_no', name: 'voucher_no'},
                {data: 'debit', name: 'account_ledgers.debit', className: 'text-end'},
                {data: 'credit', name: 'account_ledgers.credit', className: 'text-end'},
                {data: 'running_balance', name: 'account_ledgers.running_balance', className: 'text-end'},
            ],fnDrawCallback: function() {

                var debit = sum_table_col($('.data_tbl'), 'debit');
                $('#debit').text(bdFormat(debit));

                var credit = sum_table_col($('.data_tbl'), 'credit');
                $('#credit').text(bdFormat(credit));

                $('.data_preloader').hide();
                getRunningBalance();
            }
        });

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {
                if (parseFloat($(this).find('.' + class_name).data('value'))) {
                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_account_ledgers', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            account_ledger_table.ajax.reload();
        });

        //Print account ledger
        $(document).on('click', '#print_report', function (e) {
            e.preventDefault();
            var url = "{{ route('accounting.accounts.ledger.print', $account->id) }}";
            var voucher_type = $('#voucher_type').val();
            var transaction_type = $('#transaction_type').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: { voucher_type, transaction_type, from_date, to_date },
                success:function(data){
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker2'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>

    @if ($balanceType == 'debit')

        <script>
            function getRunningBalance() {

                // var i=0;
                // var previousBalance=0;
                // $('.data_tbl').find('tbody').find('tr').each(function() {

                //     var debit = parseFloat($(this).find('.debit').data('value'));
                //     var credit = parseFloat($(this).find('.credit').data('value'));

                //     if(parseFloat(i) == 0) {

                //         previousBalance = parseFloat(debit) - parseFloat(credit);
                //     }else {

                //         previousBalance = parseFloat(previousBalance) + (parseFloat(debit) - parseFloat(credit));
                //     }

                //     i++;

                //     $(this).find('.running_balance').html(bdFormat(previousBalance));
                // });
            }
        </script>
    @elseif($balanceType == 'credit')

        <script>
            function getRunningBalance() {

                // var i=0;
                // var previousBalance=0;
                // $('.data_tbl').find('tbody').find('tr').each(function() {

                //     var debit = parseFloat($(this).find('.debit').data('value'));
                //     var credit = parseFloat($(this).find('.credit').data('value'));

                //     if(parseFloat(i) == 0) {

                //         previousBalance = parseFloat(credit) - parseFloat(debit);
                //     }else {

                //         previousBalance = parseFloat(previousBalance) + (parseFloat(credit) - parseFloat(debit));
                //     }

                //     i++;

                //     $(this).find('.running_balance').html(parseFloat(previousBalance).toFixed(2));
                // });
            }
        </script>
    @endif
@endpush
