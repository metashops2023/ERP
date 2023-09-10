@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-sort-amount-up"></span>
                            <h5>@lang('Units')</h5>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Add Unit')</h6>
                                </div>
                            </div>

                            <form id="add_unit_form" class="p-2" action="{{ route('settings.units.store') }}">
                                <div class="form-group">
                                    <label><b>@lang('Unit Name') :</b> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" data-name="Name" id="name" placeholder="@lang('Unit Name')" />
                                    <span class="error error_name"></span>
                                </div>

                                <div class="form-group mt-1">
                                    <label><b>@lang('Short Name') :</b> <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" data-name="Code name" id="code" placeholder="@lang('Short name')" />
                                    <span class="error error_code"></span>
                                </div>

                                <div class="form-group mt-1">
                                    <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
                                    <select name="add_branch_id" id="add_branch_id" class="form-control">
                                        @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->name . '/' . $branch->branch_code }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
                                </div>

                                <div class="form-group text-end mt-3">
                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
                                    <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                                </div>
                            </form>
                        </div>

                        <div class="card" style="display:none;" id="edit_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Edit Unit')</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('All Units')</h6>
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
                                                <th>@lang('Short Name')</th>
                                                <th>@lang('Code Name')</th>
                                                <th>@lang('Status')</th>
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
                url: "{{ route('settings.units.index') }}",
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
                    data: 'code_name',
                    name: 'code_name'
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

    // call jquery method
    $(document).ready(function() {
        // Add Unit by ajax
        $(document).on('submit', '#add_unit_form', function(e) {
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
                    $('#add_unit_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    // getAllUnit();
                    table.ajax.reload();
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

        // pass editable data to edit modal fields
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
                $.get(url, function(data) {
                    console.log(data)
                    $("#edit_form").html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                })

            // $('#edit_unit_form')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var unitInfo = $(this).closest('tr').data('info');
            // console.log(unitInfo)
;            // $("#update_branch_id option[value='" + unitInfo.branch_id + "']").attr("selected", true);
            // $('#id').val(unitInfo.id);
            // $('#e_name').val(unitInfo.name);
            // $('#e_code').val(unitInfo.code_name);
            $('')
            $('#add_form').hide();
            $('#edit_form').show();
            // document.getElementById('e_name').focus();
        });

        // edit Unit by ajax
        $(document).on('submit', '#edit_unit_form', function(e) {
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
                    // getAllUnit();
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

        $(document).on('click', '#delete', function(e) {
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
                type: 'delete',
                data: request,
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {
                        getAllUnit();
                        toastr.error(data);
                    } else {
                        toastr.error(data.errorMsg, 'Error');
                    }
                }
            });
        });

        $(document).on('click', '#close_form', function() {
            $('#add_form').show();
            $('#edit_form').hide();
            $('.error').html('');
        });
    });
</script>
@endpush
