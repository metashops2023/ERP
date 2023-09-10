@extends('layout.master')
@push('stylesheets')
@endpush
{{-- @section('title', 'Payment Methods - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>@lang('Payment Methods')</h5>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('Add Payment Method')</h6>
                                        </div>
                                    </div>

                                    <form id="add_payment_method_form" class="p-2" action="{{ route('settings.payment.method.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label><b>@lang('Method Name') :</b> <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="@lang('Payment Method Name')" />
                                                <span class="error error_name"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i
                                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                                <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                                                <button type="reset" data-bs-dismiss="modal"
                                                    class="c-btn btn_orange float-end">@lang('Close')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="card" style="display:none;" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('Edit Payment Method')</h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2" id="edit_form_body"></div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="section-header">
                                        <div class="col-md-6">
                                            <h6>@lang('All Payment Methods')</h6>
                                        </div>
                                    </div>

                                    <div class="widget_content">
                                        <div class="data_preloader">
                                            <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                        </div>
                                        <div class="form-group" style="padding: 5px;">
                                            <div class="custom-control custom-switch pull-right">
                                                <input class="custom-control-input" type="checkbox" id="chkActive">
                                                <label for="chkActive"
                                                    class="custom-control-label">{{ __('Show Cancelled') }}</label>
                                            </div>
                                        </div>
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Serial')</th>
                                                        <th>@lang('Payment Method Name')</th>
                                                        <th>@lang('status')</th>
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
    </div>
@endsection
@push('scripts')
<script>
  $('#chkActive').change(function() {
            table.draw();
        });
        // Get all brands by ajax
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                //{extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> @lang('Print')',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            processing: true,
            serverSide: true,
            searchable: true,
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
                url: "{{ route('settings.payment.method.index') }}",
                data: function(d) {
                    d = Object.assign(d, {
                        active: $("#chkActive").is(':checked'),
                    });
                },
            },
            columnDefs: [{
                "targets": [0, 1, 3],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });


  // insert branch by ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('submit', '#add_payment_method_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#add_payment_method_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                $('#add_form').show();
                $('#edit_form').hide();
                table.ajax.reload();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
                $('.submit_button').prop('type', 'submit');
            }
        });
    });

    // pass editable data to edit modal fields
    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#edit_form_body').html(data);
                $('#add_form').hide();
                $('#edit_form').show();
            }
        });
    });

    $(document).on('submit', '#edit_payment_method_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                $('.error').html('');
                $('#add_form').show();
                $('#edit_form').hide();
                table.ajax.reload();
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
                toastr.error(data);
                table.ajax.reload();
                $('#deleted_form')[0].reset();
            }
        });
    });
</script>

@endpush
