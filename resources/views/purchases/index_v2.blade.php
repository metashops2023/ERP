@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'Purchase List - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span>
                                <h5>@lang('Purchases')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form">
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
                                                    <label><strong>@lang('Supplier') :</strong></label>
                                                    <select name="supplier_id"
                                                        class="form-control submit_able"
                                                        id="supplier_id" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($suppliers as $sup)
                                                            <option value="{{ $sup->id }}">{{ $sup->name.' ('.$sup->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Purchase Status') :</strong></label>
                                                    <select name="purchase_status" id="purchase_status"
                                                        class="form-control  submit_able">
                                                        <option value="">@lang('All')</option>
                                                        <option value="1">@lang('Purchased')</option>
                                                        <option value="2">@lang('Pending')</option>
                                                        <option value="3">@lang('Purchased By Order')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><b>@lang('Status') : </b></label>
                                                    <select name="status" id="status" class="form-control submit_able">
                                                        <option value="">@lang('All')</option>
                                                        <option value="1">@lang('Active')</option>
                                                        <option value="0">@lang('In-Active')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-secondary float-start">
                                                            <i class="fas fa-funnel-dollar"> @lang('Filter') </i>
                                                        </button>
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
                                    <h6>@lang('All Purchases')</h6>
                                </div>
                                @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('purchases.create') }}"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('P.Invoice ID')</th>
                                                <th>@lang('Purchase From')</th>
                                                <th>@lang('Supplier')</th>
                                                <th>@lang('Purchase Status')</th>
                                                <th>@lang('Payment Status')</th>
                                                <th>@lang('Grand Total')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('Paid')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('Payment Due')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('Return Amount')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('Return Due')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('Created By')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-end text-white">@lang('Total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                <th id="total_purchase_amount" class="text-white"></th>
                                                <th id="paid" class="text-white"></th>
                                                <th id="due" class="text-white"></th>
                                                <th id="purchase_return_amount" class="text-white"></th>
                                                <th id="purchase_return_due" class="text-white"></th>
                                                <th></th>
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

    <div id="purchase_details"></div>

    <!-- Change purchase status modal-->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Update Purchase Status')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="change_status_modal_body"></div>
            </div>
        </div>
    </div>

    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
        <!--Payment list modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('Payment List')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_list_modal_body"></div>
                </div>
            </div>
        </div>
        <!--Payment list modal-->

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
        </div>
        <!--Add Payment modal-->

        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content payment_details_contant">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('Payment Details') (<span
                                class="payment_invoice"></span>)</h6>
                            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area">

                        </div>

                        <div class="row">
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1" id="payment_attachment"></li>
                                </ul>
                            </div>

                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1">
                                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
                                        <button type="submit" id="print_payment" class="c-btn me-0 button-success">@lang('Print')</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))

            toastr.success('{{ session('successMsg')[0] }}');
        @endif
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
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
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('purchases.index_v2') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.purchase_status = $('#purchase_status').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{"targets": [0, 5, 6],"orderable": false,"searchable": false}],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'purchase_status',name: 'purchase_status'},
                {data: 'payment_status',name: 'payment_status'},
                {data: 'total_purchase_amount',name: 'total_purchase_amount', className: 'text-end'},
                {data: 'paid',name: 'paid', className: 'text-end'},
                {data: 'due',name: 'due', className: 'text-end'},
                {data: 'purchase_return_amount',name: 'purchase_return_amount', className: 'text-end'},
                {data: 'purchase_return_due',name: 'purchase_return_due', className: 'text-end'},
                {data: 'created_by',name: 'created_by.name'},
                {data: 'status', name: 'status'},
                {data: 'action'},
            ],fnDrawCallback: function() {
                var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                $('#total_purchase_amount').text(bdFormat(total_purchase_amount));
                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));
                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));
                var purchase_return_amount = sum_table_col($('.data_tbl'), 'purchase_return_amount');
                $('#purchase_return_amount').text(bdFormat(purchase_return_amount));
                var purchase_return_due = sum_table_col($('.data_tbl'), 'purchase_return_due');
                $('#purchase_return_due').text(bdFormat(purchase_return_due));
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

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {

                $('#purchase_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            })
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    table.ajax.reload();
                    toastr.error(data);
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    }else if (err.status == 500) {

                    }{

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            table.ajax.reload();
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();
            var body = $('.purchase_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#add_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#add_return_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url,  function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url,  function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();

            $.get(url,  function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_list_modal_body').html(data);
                $('#paymentViewModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#change_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        toastr.success(data);
                        table.ajax.reload();
                    }
                });
            });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);
            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#payment_deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#payment_deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            console.log(url);
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    table.ajax.reload();
                    $('#paymentViewModal').modal('hide');
                    toastr.success(data);
                },error: function(err) {
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    }else{
                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('.payment_details_area').html(data);
                $('#paymentDetailsModal').modal('show');
            });
        });

        $(document).on('click', '#print_payment', function(e) {
            e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: header,
                footer: footer
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
            format: 'DD-MM-YYYY'
        });

        function getSupplier() {}
    </script>
@endpush
