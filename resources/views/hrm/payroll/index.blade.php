@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'HRM Payrolls - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        @if (auth()->user()->permission->hrms['hrm_dashboard'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.dashboard.index') }}" class="text-white"><i class="fas fa-tachometer-alt"></i> <b>@lang('menu.hrm')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave.type') }}" class="text-white "><i class="fas fa-th-large"></i> <b>@lang('Leave Types')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['leave_assign'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave') }}" class="text-white"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['shift'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.attendance.shift') }}" class="text-white"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['attendance'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.attendance') }}" class="text-white"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['view_allowance_and_deduction'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.allowance') }}" class="text-white"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['payroll'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.payroll.index') }}" class="text-white "><i class="far fa-money-bill-alt text-primary"></i> <b>@lang('menu.payroll')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['holiday'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.holidays') }}" class="text-white "><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['department'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['designation'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.designations') }}" class="text-white "><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
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
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Users/Employees') :</strong></label>
                                                    <select name="user_id"
                                                        class="form-control submit_able" id="user_id" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        @foreach($employee as $row)
                                                            <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Payrolls') <i data-bs-toggle="tooltip" data-bs-placement="right" title="Note: Initially current year's data is available here, if need another year's data go to the data filter." class="fas fa-info-circle tp"></i></h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                    class="fas fa-plus-square"></i> @lang('Add')</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Employee')</th>
                                                    <th>@lang('Department')</th>
                                                    <th>@lang('Designation')</th>
                                                    <th>@lang('Month/Year')</th>
                                                    <th>@lang('Referance No')</th>
                                                    <th>@lang('Total Amount')</th>
                                                    <th>@lang('Payment Status')</th>
                                                    <th>@lang('Created By')</th>
                                                    <th>@lang('Actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Select Employee') & Month</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form  action="{{ route('hrm.payroll.create') }}" method="get">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="text-navy-blue"><b>@lang('Department') :</b></label>
                                <select  class="form-control employee" required="" id="department_id">
                                    <option> @lang('Select Employee') </option>
                                    @foreach($departments as $dep)
                                       <option value="{{ $dep->id }}">{{$dep->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="text-navy-blue"><b>@lang('Employee') :</b></label>
                                <select required name="user_id" class="form-control" id="employee">
                                    <option value=""> @lang('Select Employee') </option>
                                    @foreach($employee as $row)
                                       <option value="{{ $row->id }}">{{$row->prefix.' '.$row->name.' '.$row->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('Month/Year') :</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                                    </div>
                                    <input required type="month" name="month_year" class="form-control" autocomplete="off" placeholder="@lang('Month-Year')">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">@lang('Close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!--Payment View modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Payroll Payments')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_view_modal_body"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="modal-title" id="modal_title">@lang('Add Payment')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="payment_modal_body"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewPayrollModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content" id="view_payroll_modal_content"></div>
        </div>
    </div>

    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Payment Details')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="payment_details_modal_body"></div>

                <div class="modal-footer">
                    <div class="form-group text-end">
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
                        <button type="submit" class="c-btn button-success" id="payment_details_print">@lang('Print')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/assets/plugins/custom/print_this/printThis.js"></script>
<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "processing": true,
        "serverSide": true,
        "searching" : true,
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
        aaSorting: [[1, 'asc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('hrm.payroll.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columns: [
            {data: 'employee', name: 'employee'},
            {data: 'department_name', name: 'department_name'},
            {data: 'designation_name', name: 'designation_name'},
            {data: 'month_year', name: 'month_year'},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'gross_amount', name: 'total_amount'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'created_by', name: 'created_by'},
            {data: 'action'},
        ],fnDrawCallback: function() {
            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    $('#department_id').on('change', function(e){
        e.preventDefault();

        var department_id = $(this).val();

        $.ajax({
            url:"{{ url('hrm/leave/department/employees/') }}"+"/"+department_id,
            type:'get',
            success:function(employees){
                $('#employee').empty();
                $('#employee').append('<option value="">@lang('Select Employee')</option>');
                $.each(employees, function (key, emp) {
                    $('#employee').append('<option value="'+emp.id+'">'+ emp.prefix+' '+emp.name+' '+emp.last_name +'</option>');
                });
            }
        });
    });

    // Show add payment modal with date
    $(document).on('click', '#add_payment', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#modal_title').html('Add Payment');
               $('#payment_modal_body').html(data);
               $('.data_preloader').hide();
               $('#paymentModal').modal('show');
            }
        });
    });

    // //Show payment view modal with data
    $(document).on('click', '#view_payment', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(date){
                $('#payment_view_modal_body').html(date);
                $('#paymentViewModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    // Show payment list modal with date
    $(document).on('click', '#payment_details', function (e) {
        e.preventDefault();
        $('#payment_list_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#payment_details_modal_body').html(data);
               $('#payment_list_preloader').hide();
               $('#paymentDetailsModal').modal('show');
            }
        });
    });

    // Show add payment modal with date
    $(document).on('click', '#edit_payment', function (e) {
        e.preventDefault();
        $('#payment_list_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#payment_modal_body').html(data);
               $('#modal_title').html('Edit Payment');
               $('#payment_list_preloader').hide();
               $('#paymentModal').modal('show');
            }
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

    // Show add payment modal with date
    $(document).on('click', '#view_payroll', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
               $('#view_payroll_modal_content').html(data);
               $('.data_preloader').hide();
               $('#viewPayrollModal').modal('show');
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form',function(e){
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
    $(document).on('submit', '#payment_deleted_form',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data:request,
            success:function(data){
                toastr.error(data);
                $('#paymentViewModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    $(document).on('click', '.print_payroll',function (e) {
       e.preventDefault();
        var body = $('.payroll_print_area').html();
        var footer = $('.signature_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/payroll.print.css')}}",
            removeInline: true,
            printDelay: 500,
            header : null,
            footer : footer,
        });
    });

    $(document).on('click', '#payment_details_print',function (e) {
        e.preventDefault();
        var body = $('.payroll_payment_print_area').html();
        var footer = $('.signature_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/payroll.print.css')}}",
            removeInline: true,
            printDelay: 500,
            header : null,
            footer : footer,
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
@endpush
