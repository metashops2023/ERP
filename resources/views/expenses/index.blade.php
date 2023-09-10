@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
{{-- @section('title', 'Expense List - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-money-bill"></span>
                                <h5>@lang('Expenses')</h5>
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
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
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
                                                    <label><strong>@lang('Expense For') :</strong></label>
                                                    <select name="admin_id" class="form-control submit_able" id="admin_id" >
                                                        <option value="">@lang('All')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Category') :</strong></label>
                                                    <select name="cate_id" class="form-control submit_able" id="cate_id" >
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($ex_cates as $cate)
                                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
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

                                                        <div class="col-md-6 mt-3">
                                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i> @lang('Print')</a>
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

                    <div class="row margin_row mt-2">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>@lang('All Expense')</h6>
                                </div>
                                @if (auth()->user()->permission->expense['add_expense'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('expenses.create') }}"><i class="fas fa-plus-square"></i> @lang('Add')</a>
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
                                                <th class="text-start">@lang('Actions')</th>
                                                <th class="text-start">@lang('Date')</th>
                                                <th class="text-start">@lang('Reference ID')</th>
                                                <th class="text-start">@lang('B.Location')</th>
                                                <th class="text-start">@lang('Description')</th>
                                                <th class="text-start">@lang('Expense For')</th>
                                                <th class="text-start">@lang('Payment Status')</th>
                                                <th class="text-start">@lang('Tax')</th>
                                                <th class="text-start">@lang('Net Total')</th>
                                                <th class="text-start">@lang('Payment Due')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="7" class="text-end text-white">@lang('Total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                <th class="text-white">---</th>
                                                <th id="net_total_amount" class="text-white"></th>
                                                <th id="due" class="text-white"></th>
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

    <!--Payment list modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Payment List')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_view_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Payment list modal-->

    <!--Add Payment modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">@lang('Payment')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment-modal-body"></div>
            </div>
        </div>
    </div>
    <!--Add Payment modal-->

    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content payment_details_contant">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Payment Details') (<span
                            class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="payment_details_area"></div>

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
                                    <button type="submit" id="print_payment" class="c-btn button-success">@lang('Print')</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript" src="/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        @if (Session::has('errorMsg'))
            toastr.error("{{ session('errorMsg') }}");
        @endif

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo : "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty : "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu : "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('expenses.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.admin_id = $('#admin_id').val();
                    d.cate_id = $('#cate_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{"targets": [0, 3, 4, 6],"orderable": false,"searchable": false}],
            columns: [
                { data: 'action', },
                { data: 'date', name: 'date' },
                { data: 'invoice_id', name: 'invoice_id'},
                { data: 'from', name: 'branches.name' },
                { data: 'descriptions', name: 'descriptions' },
                { data: 'user_name', name: 'admin_and_users.name' },
                { data: 'payment_status', name: 'payment_status', className: 'text-end'},
                { data: 'tax_percent', name: 'tax_percent'},
                { data: 'net_total_amount', name: 'net_total_amount', className: 'text-end'},
                { data: 'due', name: 'due', className: 'text-end'},
            ],fnDrawCallback: function() {
                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));
                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));
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
            table.ajax.reload();
        });

        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#payment_heading').html('Add Payment');
                $('#payment-modal-body').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('.modal_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');

            $.get(url, function(data) {

                $('.modal_preloader').hide();
                $('#payment-modal-body').html(data);
                $('#paymentModal').modal('show');
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
           $('.data_preloader').show();
            $.get(url, function(data) {

                $('#payment_view_modal_body').html(data);
                $('.data_preloader').hide();
                $('#paymentViewModal').modal('show');
            });
        });

            //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           $('.modal_preloader').show();
           var url = $(this).attr('href');

            $.get(url, function(data) {

                $('.payment_details_area').html(data);
                $('.modal_preloader').hide();
                $('#paymentDetailsModal').modal('show');
            });
        });

        //Add sale payment request by ajax
        $(document).on('submit', '#payment_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var available_amount = $('#available_amount').val();
            var paying_amount = $('#p_amount').val();

            if (parseFloat(paying_amount)  > parseFloat(available_amount)) {

                $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                $('.loading_button').hide();
                return;
            }

            var url = $(this).attr('action');
            var inputs = $('.p_input');
                $('.error').html('');
                var countErrorField = 0;
            $.each(inputs, function(key, val){

                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){

                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){

                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){

                    if(!$.isEmptyObject(data.errorMsg)){

                        toastr.error(data.errorMsg,'ERROR');
                        $('.loading_button').hide();
                    }else{

                        $('.loading_button').hide();
                        $('#paymentModal').modal('hide');
                        $('#paymentViewModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(data);
                    }
                }
            });
        });

        // Set accounts in payment and payment edit form
        $(document).on('change', '#branch_id', function (e) {

            var branchId = $(this).val();

            $.ajax({
                url:"{{ url('common/ajax/call/branch/users/') }}"+"/"+branchId,
                type:'get',
                success:function(data){

                    $('#admin_id').empty();
                    $('#admin_id').append('<option value="">@lang('All')</option>');
                    $.each(data, function (key, val) {

                        var userPrefix = val.prefix != null ? val.prefix : '';
                        var userLastName = val.last_name != null ? val.last_name : '';
                        $('#admin_id').append('<option value="'+val.id+'">'+userPrefix+' '+val.name+' '+userLastName+'</option>');
                    });
                }
            })
        });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var button = $(this);
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

        $(document).on('submit', '#payment_deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    table.ajax.reload();
                    toastr.error(data);
                    $('#paymentViewModal').modal('hide');
                }
            });
        });

        // Print single payment details
        $('#print_payment').on('click', function (e) {
           e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
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
                async: false,
                data: request,
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {
                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    } else {
                        toastr.error(data.errorMsg);
                    }
                }
            });
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function (e) {
            e.preventDefault();
            var url = "{{ route('reports.expenses.print') }}";
            var branch_id = $('#branch_id').val();
            var admin_id = $('#admin_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url:url,
                type:'get',
                data: {branch_id, admin_id, from_date, to_date},
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
    </script>

    <script type="text/javascript">
        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
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
