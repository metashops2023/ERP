@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
        .form-control { padding: 4px!important; }
    </style>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/custom/image-previewer/jquery.magnify.min.css"/>
    <link rel="stylesheet" href="/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
{{-- @section('title', 'All Workspaces - ') --}}
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
                                                <a href="{{ route('todo.index') }}" class="text-white"><i class="fas fa-th-list"></i> <b>@lang('menu.todo')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->essential['work_space'] == '1')
                                            <li>
                                                <a href="{{ route('workspace.index') }}" class="text-white"><i class="fas fa-th-large text-primary"></i> <b>@lang('menu.work_space')</b></a>
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
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>@lang('Filter')</b>
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
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

                                                <div class="col-md-3">
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

                                                <div class="col-md-3">
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

                                                <div class="col-md-3">
                                                    <label><strong>@lang('Date Range') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range"
                                                            class="form-control daterange submit_able_input"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row px-3 mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('All Work Space') </h6>
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
                                                <th>@lang('Entry Date')</th>
                                                <th>@lang('Name')</th>
                                                <th>@lang('Workspace ID')</th>
                                                <th>@lang('Location')</th>
                                                <th>@lang('Priority')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Start Date')</th>
                                                <th>@lang('End Date')</th>
                                                <th>@lang('Estimated Hour')</th>
                                                <th>@lang('Assigned By')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Work Space')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_work_space_form" action="{{ route('workspace.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>@lang('Name') :</b></label>
                                <input required type="text" name="name" class="form-control" placeholder="@lang('Workspace Name')">
                            </div>

                            <div class="col-md-6">
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

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('Start Date') : </b></label>
                                <input required type="text" name="start_date" class="form-control datepicker" value="{{date(json_decode($generalSettings->business, true)['date_format'])}}" autocomplete="off">
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('End Date') : </b></label>
                                <input required type="text" name="end_date" class="form-control datepicker" placeholder="{{ json_decode($generalSettings->business, true)['date_format'] }}')" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Description') : </b></label>
                                <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="@lang('Workspace Description.')"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('Documents') : </b></label>
                                <input type="file" name="documents[]" class="form-control" multiple id="documents" placeholder="@lang('Workspace Description.')">
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('Estimated Hours') : </b></label>
                                <input type="text" name="estimated_hours" class="form-control" placeholder="@lang('Estimated Hours')">
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
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

    <!-- Add Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
      aria-labelledby="staticBackdrop" aria-hidden="true">
      <div class="modal-dialog col-55-modal" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Work Space')</h6>
                  <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
              </div>
              <div class="modal-body" id="edit_modal_body"></div>
          </div>
      </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="docsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
      aria-labelledby="staticBackdrop" aria-hidden="true">
      <div class="modal-dialog col-40-modal" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h6 class="modal-title" id="exampleModalLabel">@lang('Uploaded Documents')</h6>
                  <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
              </div>
              <div class="modal-body" id="document-list-modal"></div>
          </div>
      </div>
  </div>
  <!-- Add Modal End-->
@endsection
@push('scripts')
<script type="text/javascript" src="/assets/plugins/custom/moment/moment.min.js"></script>
<script src="/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script src="/backend/asset/js/select2.min.js"></script>
<script src="/assets/plugins/custom/image-previewer/jquery.magnify.min.js"></script>
<script src="/backend/asset/js/bootstrap-date-picker.min.js"></script>
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
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[0, 'desc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('workspace.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.priority = $('#priority').val();
                d.status = $('#status').val();
                d.date_range = $('#date_range').val();
            }
        },
        columnDefs: [{"targets": [0], "orderable": false, "searchable": false}],
        columns: [
            {data: 'date', name: 'date'},
            {data: 'name', name: 'name'},
            {data: 'ws_id', name: 'ws_id'},
            {data: 'from', name: 'branches.name'},
            {data: 'priority', name: 'priority'},
            {data: 'status', name: 'status'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'estimated_hours', name: 'estimated_hours'},
            {data: 'assigned_by', name: 'admin_and_users.name'},
            {data: 'action'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
    });

    //Submit filter form by date-range field blur
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            table.ajax.reload();
        }, 800);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 1000);
    });

    // //Show payment view modal with data
    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        // $.ajax({
        //     url:url,
        //     type:'get',
        //     success:function(date){

        //     }
        // });
    });

    $(document).on('click', '#docs', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('.data_preloader').hide();
                $('#document-list-modal').html(data);
                $('#docsModal').modal('show');
            }
        });
    });


    // Show add payment modal with date
    $(document).on('click', '#edit', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#edit_modal_body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    //Add workspace request by ajax
    $(document).on('submit', '#add_work_space_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
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
                    $('#add_work_space_form')[0].reset();
                    $(".select2").select2().val('').trigger('change');
                    $('.loading_button').hide();
                    $('.modal').modal('hide');
                    toastr.success(data);
                    table.ajax.reload();
                }
            }
        });
    });

    //Edit workspace request by ajax
    $(document).on('submit', '#edit_work_space_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
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
                    $('.modal').modal('hide');
                    toastr.success(data);
                    table.ajax.reload();
                }
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

    $(document).on('click', '#delete_doc',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var tr = $(this).closest('tr');
        $('#deleted_doc_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_doc_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_doc_form',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data:request,
            success:function(data){
                toastr.error(data);
            }
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        var start = moment().startOf('year');
        var end = moment().endOf('year');
        $('.daterange').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            locale: {cancelLabel: 'Clear'},
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
            }
        });
        $('.daterange').val('');
    });

    $(document).on('click', '.cancelBtn ', function () {
        $('.daterange').val('');
    });

    $('.select2').select2();
    $('[data-magnify=gallery]').magnify();

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'dd');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    $('.datepicker').datepicker({format: _expectedDateFormat});
</script>
@endpush
