@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
        .form-control { padding: 4px!important; }
    </style>
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
{{-- @section('title', 'All Memos -') --}}
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
                                                <a href="{{ route('memos.index') }}" class="text-white"><i class="fas fa-file-alt text-primary"></i> <b>@lang('menu.memo')</b></a>
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
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-5">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Add Memo') </h6>
                                    </div>
                                </div>

                                <div class="form-area px-2 pb-2">
                                    <form id="add_memo_form" action="{{ route('memos.store') }}">
                                        @csrf
                                        <div class="from-group">
                                            <label><b>@lang('Heading') :</b></label>
                                            <input required type="text" class="form-control" name="heading" placeholder="@lang('Memo Heading')">
                                        </div>

                                        <div class="from-group mt-1">
                                            <label><b>@lang('Description') :</b></label>
                                            <textarea required name="description" class="form-control" cols="10" rows="4" placeholder="@lang('Memo Description')"></textarea>
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
                                        <h6>@lang('Edit Memo')</h6>
                                    </div>
                                </div>

                                <div class="form-area px-2 pb-2">
                                    <form id="edit_memo_form" action="{{ route('memos.update') }}">
                                        @csrf
                                        <input type="hidden" id="id" name="id">
                                        <div class="from-group">
                                            <label><b>@lang('Heading') :</b></label>
                                            <input required type="text" class="form-control" name="heading" id="heading" placeholder="@lang('Memo Heading')">
                                        </div>

                                        <div class="from-group mt-1">
                                            <label><b>@lang('Description') :</b></label>
                                            <textarea required name="description" class="form-control" id="description" cols="10" rows="4" placeholder="@lang('Memo Description')"></textarea>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>

                                                <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Changes')</button>
                                                <button type="button" class="c-btn btn_orange float-end">@lang('Close')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('All Memos') </h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Heading')</th>
                                                    <th>@lang('Description')</th>
                                                    <th>@lang('Created Date')</th>
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
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Share Memo')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_user_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->

    <!-- Add Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content" id="view_content">

            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
<script src="/backend/asset/js/select2.min.js"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
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
        processing: true,
        serverSide: true,
        aaSorting: [[2, 'desc']],
        ajax: "{{ route('memos.index') }}",
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        columns: [
            {data: 'heading', name: 'heading'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'},
        ]
    });

    // //Show payment view modal with data
    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#view_content').html(data);
                $('#showModal').modal('show');
                $('.data_preloader').hide();
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
                console.log(data);
                $('#id').val(data.id);
                $('#heading').val(data.heading);
                $('#description').val(data.description);
                $('#add_form').hide();
                $('#edit_form').show();
                $('.data_preloader').hide();
            }
        });
    });

    //Add workspace request by ajax
    $(document).on('submit', '#add_memo_form', function(e){
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
                    $('#add_memo_form')[0].reset();
                    $('.loading_button').hide();
                    toastr.success(data);
                    table.ajax.reload();
                }
            }
        });
    });

    //Edit workspace request by ajax
    $(document).on('submit', '#edit_memo_form', function(e){
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
                $('#add_memo_form')[0].reset();
                $('#add_form').show();
                $('#edit_form').hide();
                toastr.success(data);
                table.ajax.reload();
            }
        });
    });

    $(document).on('click', '#add_user_btn', function (e) {
       e.preventDefault();
       $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#addUserModal').modal('show');
                $('#add_user_modal_body').html(data)
                $('.data_preloader').hide();
            }
        });
    });

    $(document).on('submit', '#add_user_form', function (e) {
       e.preventDefault();
       $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                toastr.success(data);
                $('#addUserModal').modal('hide');
                $('.loading_button').hide();
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

    $('.select2').select2();
</script>

@endpush
