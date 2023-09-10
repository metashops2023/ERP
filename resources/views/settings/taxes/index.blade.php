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
                            <span class="fas fa-hand-holding-usd"></span>
                            <h5>@lang('Texes')</h5>
                        </div>
                    </div>
                </div>
                <!-- =========================================top section button=================== -->

                <div class="container-fluid">
                    <div class="row">
                        <div class="form_element">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('All Tax')</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                    </div>
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
                                                <th class="text-start">@lang('Serial')</th>
                                                <th class="text-start">@lang('Tax Name')</th>
                                                <th class="text-start">@lang('Tax Percent')</th>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Tax')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_tax_form" action="{{ route('settings.taxes.store') }}">
                    <div class="form-group">
                        <label><b>@lang('Tax Name') :</b> <span class="text-danger">*</span></label>
                        <input type="text" name="tax_name" class="form-control form-control-sm add_input" data-name="Tax name" id="tax_name" placeholder="@lang('Tax Name')" />
                        <span class="error error_tax_name"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('Tax Percent') :</b> <span class="text-danger">*</span></label>
                        <input type="number" name="tax_percent" class="form-control form-control-sm add_input" data-name="Tax percent" id="tax_percent" placeholder="@lang('Tax percent')" />
                        <span class="error error_tax_percent"></span>
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

                    <div class="form-group text-right mt-3">
                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                        <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Tax')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="edit_modal_body">
                <!--begin::Form-->

            </div>
        </div>
    </div>
</div>
<!-- Modal-->
@endsection
@push('scripts')
<script>
    // Get all branch by ajax
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
                url: "{{ route('settings.taxes.index') }}",
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
                    data: 'tax_name',
                    name: 'tax_name'
                },
                {
                    data: 'tax_percent',
                    name: 'tax_percent'
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
        // Add branch by ajax
        $('#add_tax_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });
            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('#add_tax_form')[0].reset();
                    $('.loading_button').hide();
                    // getAllUnit();
                    $('#addModal').modal('hide');
                    table.ajax.reload();
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            // $('#edit_tax_form')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var taxInfo = $(this).closest('tr').data('info');
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
            $("#update_branch_id option[value='" + taxInfo.branch_id + "']").attr("selected", true);
            $('#id').val(taxInfo.id);
            $('#e_tax_name').val(taxInfo.tax_name);
            $('#e_tax_percent').val(taxInfo.tax_percent);
            // $('#editModal').modal('show');
        });

        // edit branch by ajax
        $(document).on('submit', '#edit_tax_form',function(e){
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

        // Show sweet alert for delete
        // $(document).on('click', '#delete',function(e){
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#deleted_form').attr('action', url);
        //     swal({
        //         title: "Are you sure?",
        //         icon: "warning",
        //         buttons: true,
        //         dangerMode: true,
        //     })
        //     .then((willDelete) => {
        //         if (willDelete) {
        //             $('#deleted_form').submit();
        //         }
        //     });
        // });

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
                        toastr.error(data.errorMsg);
                    }
                }
            });
        });
    });
</script>
@endpush
