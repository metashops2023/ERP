@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
        .form-control { padding: 4px!important; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
{{-- @section('title', 'All Todo - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul class="list-unstyled">
                                        @if (auth()->user()->permission->essential['assign_todo'] == '1')
                                            <li>
                                                <a href="{{ route('todo.index') }}" class="text-white"><i class="fas fa-th-list text-primary"></i> <b>@lang('menu.todo')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->essential['work_space'] == '1')
                                            <li>
                                                <a href="{{ route('workspace.index') }}" class="text-white"><i class="fas fa-th-large"></i> <b>@lang('menu.work_space')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->essential['memo'] == '1')
                                            <li>
                                                <a href="{{ route('memos.index') }}" class="text-white"><i class="fas fa-file-alt"></i> <b>@lang('menu.memo')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->essential['msg'] == '1')
                                            <li>
                                                <a href="{{ route('messages.index') }}" class="text-white"><i class="fas fa-envelope"></i> <b>@lang('menu.message')</b></a>
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
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' .$branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Priority') : </strong></label>
                                                    <select name="priority"
                                                        class="form-control submit_able" id="priority" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        <option value="Low">@lang('Low')</option>
                                                        <option value="Medium">@lang('Medium')</option>
                                                        <option value="High">@lang('High')</option>
                                                        <option value="Urgent">@lang('Urgent')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Status') : </strong></label>
                                                    <select name="status"
                                                        class="form-control submit_able" id="status" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        <option value="New">@lang('New')</option>
                                                        <option value="In-Progress">@lang('In-Progress')</option>
                                                        <option value="On-Hold">@lang('On-Hold')</option>
                                                        <option value="Complated">@lang('Completed')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
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
                                                                    class="fas fa-calendar-week input_i"></i></span>
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

                    <div class="row mt-1">
                        <div class="col-md-3">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Add Todo') </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_todo_form" action="{{ route('todo.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label><b>@lang('Task') :</b></label>
                                                <input required type="text" name="task" class="form-control" placeholder="@lang('Task')">
                                            </div>
                                        </div>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12">
                                                <label><b>@lang('Assigned To') :</b></label>
                                                <select required name="user_ids[]" class="form-control select2" multiple="multiple">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-1">
                                            <div class="col-md-6">
                                                <label><b>@lang('Priority') : </b></label>
                                                <select required name="priority" class="form-control">
                                                    <option value="">@lang('Select Priority')</option>
                                                    <option value="Low">@lang('Low')</option>
                                                    <option value="Medium">@lang('Medium')</option>
                                                    <option value="High">@lang('High')</option>
                                                    <option value="Urgent">@lang('Urgent')</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label><strong>@lang('Status') : </strong></label>
                                                <select required name="status" class="form-control">
                                                    <option value="">@lang('Select Status')</option>
                                                    <option value="New">@lang('New')</option>
                                                    <option value="In-Progress">@lang('In-Progress')</option>
                                                    <option value="On-Hold">@lang('On-Hold')</option>
                                                    <option value="Complated">@lang('Completed')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12">
                                                <label><b>@lang('Due Date') : </b></label>
                                                <input required type="text" name="due_date" class="form-control" id="due_date" placeholder="@lang('DD-MM-YYYY')" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12">
                                                <label><b>@lang('Description') : </b></label>
                                                <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="@lang('Workspace Description.')"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                                <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card" style="display:none;" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Edit Todo')</h6>
                                    </div>
                                </div>

                                <div class="form-area px-2 pb-2" id="edit_form_body">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('All Todo') </h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Todo ID')</th>
                                                    <th>@lang('Task')</th>
                                                    <th>@lang('Location')</th>
                                                    <th>@lang('Priority')</th>
                                                    <th>@lang('Status')</th>
                                                    <th>@lang('Due Date')</th>
                                                    <th>@lang('Assigned To')</th>
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
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
      aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Change Status')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="change_status_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

     <!-- Add Modal -->
     <div class="modal fade" id="showModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="staticBackdrop" aria-hidden="true">
       <div class="modal-dialog col-55-modal" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h6 class="modal-title" id="exampleModalLabel">@lang('View Task')</h6>
                   <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
               </div>
               <div class="modal-body" id="show_modal_body">

               </div>
           </div>
       </div>
   </div>
   <!-- Add Modal End-->
@endsection
@push('scripts')
<script src="/backend/asset/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
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
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[0, 'desc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.priority = $('#priority').val();
                d.status = $('#status').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columnDefs: [{"targets": [0],"orderable": false,"searchable": false}],
        columns: [
            {data: 'todo_id', name: 'todo_id'},
            {data: 'task', name: 'task'},
            {data: 'from', name: 'branches.name'},
            {data: 'priority', name: 'priority'},
            {data: 'status', name: 'status'},
            {data: 'due_date', name: 'due_date'},
            {data: 'assigned_by', name: 'admin_and_users.name'},
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

    //Add Todo request by ajax
    $(document).on('submit', '#add_todo_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {
                    toastr.error(data.errorMsg);
                }else{
                    $('#add_todo_form')[0].reset();
                    $(".select2").select2().val('').trigger('change');
                    toastr.success(data);
                    table.ajax.reload();
                }
            }
        });
    });

    $(document).on('click', '#edit', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#edit_form_body').html(data);
                $('#add_form').hide();
                $('#edit_form').show();
                $('.data_preloader').hide();
            }
        });
    });

    //Edit Todo request by ajax
    $(document).on('submit', '#edit_todo_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.loading_button').hide();
                toastr.success(data);
                table.ajax.reload();
                $('#add_form').show();
                $('#edit_form').hide();
            }
        });
    });

    $(document).on('click', '#change_status', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#change_status_modal_body').html(data);
                $('#changeStatusModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    $(document).on('click', '#show', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#show_modal_body').html(data);
                $('#showModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    //Edit Todo request by ajax
    $(document).on('submit', '#changes_status_form', function(e){
        e.preventDefault();
        $('.loading_button2').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.loading_button2').hide();
                toastr.success(data);
                $('#changeStatusModal').modal('hide');
                table.ajax.reload();
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

<script type="text/javascript">
    $('.select2').select2();
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('due_date'),
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
        format: _expectedDateFormat,
    });
</script>
@endpush
