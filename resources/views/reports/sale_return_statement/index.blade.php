@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'Sale Return Statements - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('Sale Return Statements')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Customer') :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        <option value="NULL">@lang('Walk-In-Customer')</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
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

                                                <div class="col-md-2">
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
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end" id="print_return_statement_report"><i class="fas fa-print "></i> @lang('Print')</a>
                                                            </div>
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
                                    <h6>@lang('Sale Return Statement List')</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    {{-- <table class="display data_tbl data__table table-hover"> --}}
                                        <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Return Invoice ID')</th>
                                                <th>@lang('Parent Sale')</th>
                                                <th>@lang('Stock Location')</th>
                                                <th>@lang('Customer')</th>
                                                <th>@lang('Entered By')</th>
                                                <th>@lang('Total Item')</th>
                                                <th>@lang('Total Qty')</th>
                                                <th>@lang('Net Total Amt').</th>
                                                <th>@lang('Return Discount')</th>
                                                <th>@lang('Order Tax')</th>
                                                <th>@lang('Total Return Amt').</th>
                                                <th>@lang('Total Refunded Amt').</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="6" class="text-white text-end">
                                                    Total : ({{ json_decode($generalSettings->business, true)['currency'] }})
                                                </th>

                                                <th id="total_item" class="text-white text-end"></th>
                                                <th id="total_qty" class="text-white text-end"></th>
                                                <th id="net_total_amount" class="text-white text-end"></th>
                                                <th id="return_discount_amount" class="text-white text-end"></th>
                                                <th id="return_tax_amount" class="text-white text-end"></th>
                                                <th id="total_return_amount" class="text-white text-end"></th>
                                                <th id="total_return_due_pay" class="text-white text-end"></th>
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

        var return_statements_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
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
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('reports.sale.return.statement.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'sale_returns.invoice_id'},
                {data: 'parent_sale', name: 'sales.invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'customer', name: 'customers.name'},
                {data: 'created_by', name: 'admin_and_users.name'},
                {data: 'total_item', name: 'total_item', className: 'text-end'},
                {data: 'total_qty', name: 'total_qty', className: 'text-end'},
                {data: 'net_total_amount', name: 'net_total_amount', className: 'text-end'},
                {data: 'return_discount_amount', name: 'return_discount_amount', className: 'text-end'},
                {data: 'return_tax_amount', name: 'return_tax_amount', className: 'text-end'},
                {data: 'total_return_amount', name: 'total_return_amount', className: 'text-end'},
                {data: 'total_return_due_pay', name: 'total_return_due_pay', className: 'text-end'},

            ],fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));

                var return_discount_amount = sum_table_col($('.data_tbl'), 'return_discount_amount');
                $('#return_discount_amount').text(bdFormat(return_discount_amount));

                var return_tax_amount = sum_table_col($('.data_tbl'), 'return_tax_amount');
                $('#return_tax_amount').text(bdFormat(return_tax_amount));

                var total_return_amount = sum_table_col($('.data_tbl'), 'total_return_amount');
                $('#total_return_amount').text(bdFormat(total_return_amount));

                var total_return_due_pay = sum_table_col($('.data_tbl'), 'total_return_due_pay');
                $('#total_return_due_pay').text(bdFormat(total_return_due_pay));

                $('.data_preloader').hide();
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
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            return_statements_table.ajax.reload();
        });

         //Print purchase Payment report
        $(document).on('click', '#print_return_statement_report', function (e) {
            e.preventDefault();

            var url = "{{ route('reports.sale.return.statement.print') }}";

            var branch_id = $('#branch_id').val();
            var customer_id = $('#customer_id').val();
            var payment_status = $('#payment_status').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();

            $.ajax({
                url:url,
                type:'get',
                data: {branch_id, customer_id, payment_status,from_date, to_date},
                success:function(data){

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                        formValues: false,
                        canvas: false,
                        beforePrint: null,
                        afterPrint: null
                    });
                }
            });
        });

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
@endpush
