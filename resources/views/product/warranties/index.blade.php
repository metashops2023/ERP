@extends('layout.master')
@push('stylesheets')
@endpush
{{-- @section('title', 'Warrantites - ') --}}
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-desktop"></span>
                            <h5> @lang('Warranties/Guaranties')</h5>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <div class="card" id="add_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Add Warranty/Guarantee')</h6>
                                </div>
                            </div>

                            <div class="form-area px-3 pb-2">
                                <form id="add_warranty_form" action="{{ route('product.warranties.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <strong>@lang('Name') :</strong> <span class="text-danger">*</span>
                                        <input type="text" name="name" class="form-control add_input" data-name="Warranty name" id="name" placeholder="@lang('Warranty name')" />
                                        <span class="error error_name"></span>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-lg-4">
                                            <strong>@lang('Type') :</strong> <span class="text-danger">*</span>
                                            <select name="type" class="form-control" id="type">
                                                <option value="1">@lang('Warranty')</option>
                                                <option value="2">@lang('Guarantee')</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-8">
                                            <strong>@lang('Duration') :</strong> <span class="text-danger">*</span>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" name="duration" class="form-control add_input" data-name="Warranty duration" id="duration" placeholder="@lang('Warranty duration')">
                                                    <span class="error error_duration"></span>
                                                </div>

                                                <div class="col-md-6">
                                                    <select name="duration_type" class="form-control" id="duration_type">
                                                        <option value="Months">@lang('Months')</option>
                                                        <option value="Days">@lang('Days')</option>
                                                        <option value="Years">@lang('Years')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <strong>@lang('Description') :</strong>
                                        <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="@lang('Warranty description')"></textarea>
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

                                    <div class="form-group row mt-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                            <button type="submit" class="c-btn button-success float-end me-0 submit_button">@lang('Save')</button>
                                            <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card" style="display:none;" id="edit_form">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Edit Warranty/Guarantee')</h6>
                                </div>
                            </div>

                            <div class="form-area px-3 pb-2">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Warranty/Guaranty List')</h6>
                                </div>
                            </div>
                            <!--begin: Datatable-->
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
                                            <tr class="text-center">
                                                <th>SL</th>
                                                <th>@lang('Name')</th>
                                                <th>@lang('Duration')</th>
                                                <th>@lang('Type')</th>
                                                <th>@lang('Description')</th>
                                                <th>@lang('Status')</th>
                                                <th>@lang('Action')</th>
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
                url: "{{ route('product.warranties.index') }}",
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

                    data: null,
                "render": function(data, type, full, meta){
                return full["duration"] + " " + full["duration_type"];

                 }
                },
                {

                data: null,
                "render": function(data, type, full, meta){
                return full["type"]==1 ?  "Warranty" : "Guarantee";

            }
            },
                {
                    data: 'description',
                    name: 'description'
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
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add Customar group by ajax
        $('#add_warranty_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();

                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('#add_warranty_form')[0].reset();
                    $('.loading_button').hide();
                    // getAllWarranty();
                    $('.submit_button').prop('type', 'submit');
                    table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });

        // Pass editable data to edit modal fields
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

            $('.error').html('');
            var warranty = $(this).closest('tr').data('info');
            $("#update_branch_id option[value='" + warranty.branch_id + "']").attr("selected", true);
            $('#id').val(warranty.id);
            $('#e_name').val(warranty.name);
            $('#e_type').val(warranty.type);
            $('#e_duration').val(warranty.duration);
            $('#e_duration_type').val(warranty.duration_type);
            $('#e_description').val(warranty.description);
            $('#add_form').hide();
            $('#edit_form').show();
            document.getElementById('e_name').focus();
        });

        // edit bank by ajax
        $(document).on('submit', '#edit_warranty_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
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
                    $('.loading_button').hide();
                    // getAllWarranty();
                    $('#add_form').show();
                    $('#edit_form').hide();
                    table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error, Please contact to the support team.');
                    }
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
                type: 'post',
                async: false,
                data: request,
                success: function(data) {
                    // getAllWarranty();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                },
                error: function(err) {
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
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
