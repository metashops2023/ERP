@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
        .form-control { padding: 4px!important; }
        .message_area {height: 61vh; overflow-y: scroll;}
        .message_area .user {font-size: 12px;font-weight: 600;color: #1669af;}
        .message-box {margin-bottom: 9px;border-bottom: 1px solid lightgray;padding: 3px 0px;}
        .message-box .message-time {font-size: 10px;margin: 0px!important;}
        .message-text p {font-size: 12px;color: black;margin-top: 3px;}
        .delete_message {color: red;font-weight: 700;}
        .message_area:last-child{border-bottom: 0px solid black;}
    </style>
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
{{-- @section('title', 'User Messages - ') --}}
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
                                                <a href="{{ route('messages.index') }}" class="text-white"><i class="fas fa-envelope text-primary"></i> <b>@lang('menu.message')</b></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Messages') </h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="row">
                                        <div class="message_area" id="chat-box">

                                        </div>
                                    </div>

                                    <div class="px-2 py-1 form-header">
                                        <div class="col-md-12">
                                            <form id="add_message_form" action="{{ route('messages.store') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        {{-- <input required type="text" name="task_name" id="task_name" class="form-control" placeholder="@lang('Wright task and press enter')">  --}}

                                                        <input required type="text" name="description" id="description" class="form-control form-control-sm" placeholder="@lang('Type Message')" autofocus>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="c-btn button-success me-0 float-start submit_button">
                                                            <i class="fas fa-spinner ts_preloader d-none" id="ts_preloader"></i>
                                                       @lang('Send')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
@endsection
@push('scripts')
<script src="/backend/asset/js/select2.min.js"></script>
<script>
    // Get all messages by ajax
    function message_list() {
        //$('.data_preloader').show();
        $.ajax({
            url: "{{ route('messages.all') }}",
            type: 'get',
            success: function(data) {
                $('.message_area').html(data);
                scroll_down_chat_div();
                //$('.data_preloader').hide();
            }
        });
    }
    message_list();

    //Add message request by ajax
    $(document).on('submit', '#add_message_form', function(e){
        e.preventDefault();
        $('#ts_preloader').removeClass('d-none');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                message_list();
                $('#add_message_form')[0].reset();
                toastr.success(data);
                $('#ts_preloader').addClass('d-none');
                scroll_down_chat_div();
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
                message_list();
                toastr.error(data);
            }
        });
    });

    function scroll_down_chat_div() {
		var msg_box = $('#chat-box');
		var height = msg_box[0].scrollHeight;
		msg_box.scrollTop(height);
	}
</script>

@endpush
