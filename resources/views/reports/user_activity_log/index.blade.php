@php
   $userActivityLogUtil = new App\Utils\UserActivityLogUtil();
@endphp
@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
    <style>.log_table td {font-size: 9px!important;font-weight: 500!important;}</style>
@endpush
{{-- @section('title', 'User Activities Log - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('User Activities Log')</h5>
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
                                                                class="form-control" id="branch_id" autofocus>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <input type="hidden" id="branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : NULL }}">
                                                    @endif
                                                @else
                                                    <input type="hidden" id="branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : NULL }}">
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Action By') :</strong></label>
                                                    <select name="user_id" class="form-control" id="user_id" autofocus>
                                                        <option value="">@lang('All')</option>

                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Action Name') :</strong></label>
                                                    <select name="action" class="form-control" id="action" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($userActivityLogUtil->actions() as $key => $action)
                                                            <option value="{{ $key }}">{{ $action }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Subject Type') :</strong></label>
                                                    <select name="subject_type" class="form-control select2" id="subject_type" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($userActivityLogUtil->subjectTypes() as $key => $subjectTypes)
                                                            <option value="{{ $key }}">{{ $subjectTypes }}</option>
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
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-end mt-1"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
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
                                    <h6>@lang('User Activity Logs')</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    {{-- <table class="display data_tbl data__table table-hover"> --}}
                                        <table class="log_table display data_tbl modal-table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Business Location')</th>
                                                <th>@lang('Action By')</th>
                                                <th>@lang('Action Name')</th>
                                                <th>@lang('Subject Type')</th>
                                                <th>@lang('Description')</th>
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

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/backend/asset/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        var log_table = $('.data_tbl').DataTable({
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
                {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary'},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('reports.user.activities.log.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.user_id = $('#user_id').val();
                    d.action = $('#action').val();
                    d.subject_type = $('#subject_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [3, 4],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'branch', name: 'branches.name'},
                {data: 'action_by', name: 'admin_and_users.name'},
                {data: 'action', name: 'action'},
                {data: 'subject_type', name: 'subject_type'},
                {data: 'descriptions', name: 'descriptions'},
            ],fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            log_table.ajax.reload();
        });

        $(document).on('change', '#branch_id', function (e) {

            var branch_id = $(this).val();
            getBrandAllowLoginUsers(branch_id)
        });

        function getBrandAllowLoginUsers(branchId) {

            var branchId = branchId ? branchId : 'NULL';
            $.ajax({
                url:"{{ url('common/ajax/call/branch/allow/login/users/') }}"+"/"+branchId,
                type:'get',
                success:function(data){

                    $('#user_id').empty();
                    $('#user_id').append('<option value="">@lang('All')</option>');
                    $.each(data, function (key, val) {

                        var userPrefix = val.prefix != null ? val.prefix : '';
                        var userLastName = val.last_name != null ? val.last_name : '';
                        $('#user_id').append('<option value="'+val.id+'">'+userPrefix+' '+val.name+' '+userLastName+'</option>');
                    });
                }
            })
        }

        getBrandAllowLoginUsers($('#branch_id').val());
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
