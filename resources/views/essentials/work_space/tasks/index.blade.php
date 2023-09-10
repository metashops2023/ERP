@extends('layout.master')
@push('stylesheets')
    {{-- <link rel="stylesheet" type="text/css" href="/assets/plugins/custom/pace-master/themes/red/pace-theme-fill-left.css"/> --}}
    <style>
        b{font-weight: 600;font-family: Arial, Helvetica, sans-serif;}
        th.task-name {width: 75%;}
        th.task-assign-to {width: 15%;}
        th.task-status {width: 10%;}
        .custom-modify {padding: 3px 5px!important;line-height: 31px!important;}
    </style>
@endpush
{{-- @section('title', 'Manage Tasks - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-tasks"></span>
                                <h5>@lang('Manage Task')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <i class="fas fa-paperclip ms-2"></i> <b class="text-danger">({{ $ws->ws_id }})</b> {{ $ws->name }}
                                        <div class="px-2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <ul class="list-unstyled ws_description">
                                                        <li><b>@lang('Start Date') :</b> {{ date('d-m-Y', strtotime($ws->start_date)) }}</li>
                                                        <li><b>@lang('End Date') :</b> {{ date('d-m-Y', strtotime($ws->end_date)) }}</li>
                                                        <li><b>@lang('Estimated Hours') :</b> {{ $ws->estimated_hours }}</li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-4">
                                                    <ul class="list-unstyled ws_description">
                                                        <li><b>@lang('Assigned By') :</b> {{ $ws->admin->prefix.' '.$ws->admin->name.' '.$ws->admin->last_name}}</li>
                                                        <li>
                                                            <b>@lang('Assigned To') :</b>
                                                            @foreach ($ws->ws_users as $ws_user)
                                                                {{ $ws_user->user->prefix.' '.$ws_user->user->name.' '.$ws_user->user->last_name }},
                                                            @endforeach
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-4">
                                                    <ul class="list-unstyled ws_description">
                                                        <li><b>@lang('Priority')  :</b> {{ $ws->priority }}</li>
                                                        <li><b>@lang('Status') :</b> {{ $ws->status }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="card">
                            <div class="py-2 px-2 form-header">
                                <div class="col-md-12">
                                    <form id="add_task_form" action="{{ route('workspace.task.store') }}">
                                        @csrf
                                        <input type="hidden" name="ws_id" id="ws_id" value="{{ $ws->id }}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input required type="text" name="task_name" id="task_name" class="form-control form-control-sm" placeholder="@lang('Wright task and press enter')">
                                            </div>

                                            <div class="col-md-2">
                                                <select name="task_status" id="task_status" class="form-control form-control-sm">
                                                    <option value="In-Progress">@lang('In-Progress')</option>
                                                    <option value="Pending">@lang('Pending')</option>
                                                    <option value="Completed">@lang('Completed')</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="submit" class="c-btn button-success me-0 float-start submit_button">@lang('Add')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="widget_content px-1">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>

                                <table class="table modal-table table-sm">
                                    {{-- <thead class="d-none">
                                        <tr class="bg-secondary">
                                            <th class="task-name text-white text-start">@lang('Task')</th>
                                            <th class="task-assign-to text-white text-start">@lang('Assigned To')</th>
                                            <th class="task-status text-white text-start">@lang('Status')</th>
                                        </tr>
                                    </thead> --}}

                                    <tbody id="task_list">

                                    </tbody>
                                </table>
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
    {{-- <script src="/assets/plugins/custom/pace-master/pace.min.js"></script> --}}
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        // Get all customer by ajax
        function task_list() {
            $('.data_preloader').show();
            //Pace.start();
            $.ajax({
                url: "{{ route('workspace.task.list', $ws->id) }}",
                type: 'get',
                success: function(data) {
                    $('#task_list').html(data);
                    $('.data_preloader').hide();
                    //Pace.stop();
                }
            });
        }
        task_list();

        $(document).on('submit', '#add_task_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR');
                        $('.loading_button').hide();
                    }else{
                        $('#add_task_form')[0].reset();
                        $('.loading_button').hide();
                        toastr.success(data);
                        task_list();
                    }
                }
            });
        });

        var previous_value = "";
        $(document).on('click', '#edit_task_btn', function (e) {
           e.preventDefault();
           $('.task_area').show();
           $('.edit_task_name').hide();
           $(this).closest('tr').find('.edit_task_name').show();
           previous_value = $(this).closest('tr').find('#edit_task_name').val();
           $(this).closest('tr').find('.task_area').hide();
           $(this).closest('tr').find('.edit_task_name').focus();
        });

        $(document).on('keyup', '#edit_task_name',function(e) {
            // if (e.key == "Enter") $('.save').click();
            // if (e.key == "Escape") $('.cancel').click();
            if (e.key == "Escape"){
                $('.task_area').show();
                $('.edit_task_name').hide();
                $(this).closest('tr').find('#edit_task_name').val(previous_value);
            }else if (e.key == "Enter") {
                $(this).closest('tr').find('.update_task_button').click();
            }
        });

        $(document).on('click', '.update_task_button',function () {
            var value = $(this).closest('tr').find('#edit_task_name').val();
            $(this).closest('tr').find('#task_name').html(value);
            var id = $(this).closest('tr').find('.task_area').data('id');
            $('.task_area').show();
            $('.edit_task_name').hide();
            $.ajax({
                url:"{{ url('essentials/workspaces/tasks/update') }}",
                type:'post',
                data: { id, value },
                success:function(data){
                    console.log(data);
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
                task_list();
                toastr.error(data);
            }
        });
    });

    $(document).on('click', '#assign_user',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var user_id = $(this).data('user_id');
        $.ajax({
            url:url,
            type:'get',
            data: { user_id },
            success:function(data){
                console.log(data);
                task_list();
            }
        });
    });

    $(document).on('click', '#assign_user',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var user_id = $(this).data('user_id');
        $.ajax({
            url:url,
            type:'get',
            data: { user_id },
            success:function(data){
                console.log(data);
                task_list();
            }
        });
    });

    $(document).on('click', '#change_status',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var status = $(this).data('status');
        $.ajax({
            url:url,
            type:'get',
            data: { status },
            success:function(data){
                console.log(data);
                task_list();
            }
        });
    });

    $(document).on('click', '#change_priority',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var priority = $(this).data('priority');
        $.ajax({
            url:url,
            type:'get',
            data: { priority },
            success:function(data){
                task_list();
            }
        });
    });
    </script>
@endpush
