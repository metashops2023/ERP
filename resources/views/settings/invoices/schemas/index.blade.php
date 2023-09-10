@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-receipt"></span>
                                <h5>@lang('Invoice Schemas')</h5>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Invoice Schemas')</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="padding: 5px;">
                                    <div class="custom-control custom-switch pull-right">
                                        <input class="custom-control-input" type="checkbox" id="chkActive">
                                        <label for="chkActive"
                                            class="custom-control-label">{{ __('Show Cancelled') }}</label>
                                    </div>
                                </div>

                                    <div class="widget_content">
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start">@lang('Name')</th>
                                                        <th class="text-start">@lang('Prefix')</th>
                                                        <th class="text-start">@lang('Start From')</th>
                                                        <th class="text-start">@lang('Status')</th>
                                                        <th class="text-start">@lang('Actions')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
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
    </div>

     <!-- Add Modal -->
     <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Invoice Schema')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_schema_form" action="{{ route('invoices.schemas.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><b>@lang('Preview') : <span id="schema_preview"></span></label>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="@lang('Schema name')"/>
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <p class="checkbox_input_wrap mt-4"> <input type="checkbox" name="set_as_default" autocomplete="off" id="set_as_default">&nbsp;&nbsp;<b>@lang('Set as default').</b></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('Format') :</b> <span class="text-danger">*</span></label>
                                <select name="format" class="form-control form-control-sm" id="format">
                                    <option value="1">@lang('FORMAT-XXXX')</option>
                                    <option value="2">@lang('FORMAT')-{{ date('Y') }}<XXXX</option>
                                </select>
                                <span class="error error_format"></span>
                            </div>

                            <div class="col-md-6">
                                <label><b>@lang('Prefix') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="prefix" class="form-control form-control-sm" id="prefix" placeholder="@lang('Prefix')"/>
                                <span class="error error_prefix"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>@lang('Start From') :</b></label>
                                <input type="number" name="start_from" class="form-control form-control-sm" id="start_from" placeholder="@lang('Start From')" value="0"/>
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <!-- Edit Modal -->
     <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Invoice Schema')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End-->

@endsection
@push('scripts')
    <script>
         $('#chkActive').change(function() {
            table.draw();
        });
        var table = $('.data_tbl').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[3, 'asc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
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
            ajax: {
                url: "{{ route('invoices.schemas.index') }}",
                data: function(d) {
                    d = Object.assign(d, {
                        active: $("#chkActive").is(':checked'),
                    });
                },
            },

            columns: [
                {data: 'name', name: 'name'},
                {data: 'prefix', name: 'prefix'},
                {data: 'start_from', name: 'start_from'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
        });


        $(document).on('change', '#format', function () {
            var val = $(this).val();
            if (val == 2) {
                $('#prefix').val("{{ date('Y') }}"+'/');
                $('#prefix').prop('readonly', true);
            }else{
                $('#prefix').val("");
                $('#prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('change', '#e_format', function () {
            var val = $(this).val();
            if (val == 2) {
                $('#e_prefix').val("{{ date('Y') }}"+'/');
                $('#e_prefix').prop('readonly', true);
            }else{
                $('#e_prefix').val("");
                $('#e_prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('input', '#prefix', function () {
            previewInvoieId();
        });

        $(document).on('input', '#e_prefix', function () {
            previewInvoieId();
        });

        $(document).on('input', '#start_from', function () {
            previewInvoieId();
        });

        $(document).on('input', '#e_start_from', function () {
            previewInvoieId();
        });

        function previewInvoieId() {
            var prefix = $('#prefix').val();
            var start_from = $('#start_from').val();
            $('#schema_preview').html('#'+prefix+start_from);

            var prefix = $('#e_prefix').val();
            var start_from = $('#e_start_from').val();
            $('#e_schema_preview').html('#'+prefix+start_from);
        }

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function(){
            // Add category by ajax
            $(document).on('submit', '#add_schema_form',function(e){
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
                        $('#add_schema_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                        $('#schema_preview').html('');
                        $('#prefix').prop('readonly', false);
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e){
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        $('.data_preloader').hide();
                        $('#edit_modal_body').html(data);
                        $('#editModal').modal('show');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#set_default_btn', function(e){
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        table.ajax.reload();
                        toastr.success(data);
                        $('.data_preloader').hide();
                    }
                });
            });

            // edit category by ajax
            $(document).on('submit', '#edit_schema_form',function(e){
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
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_e_' + key + '').html(error[0]);
                        });
                    }
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
                    async:false,
                    data:request,
                    success:function(data){
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
