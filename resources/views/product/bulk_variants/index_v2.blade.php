@extends('layout.master')
@push('stylesheets')@endpush
{{-- @section('title', 'All Variant - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>@lang('Variants')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Add Variant') </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_variant_form" action="{{ route('product.variants.store') }}">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                                                <input type="text" name="variant_name" class="form-control add_input"
                                                    data-name="Variant name" id="variant_name" placeholder="@lang('Variant Name')" />
                                                <span class="error error_variant_name"></span>
                                            </div>

                                        </div>

                                        <div class="form-group row mt-1">
                                            <label><b>@lang('Variant Childs') </b>(Values) : <span class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <input required type="text" name="variant_child[]" class="form-control"
                                                    placeholder="@lang('Variant child')" />
                                            </div>

                                            <div class="col-md-2 text-end">
                                                <a class="btn btn-sm btn-primary add_more_for_add" href="#">+</a>
                                            </div>
                                        </div>

                                        <div class="form-group more_variant_child_area">

                                        </div>

                                        <div class="form-group row mt-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i
                                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                                <button type="submit" class="c-btn button-success float-end me-0 submit_button">@lang('Save')</button>
                                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card" style="display:none;" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Edit Variant') </h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Variant')</h6>
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
                                                    <th class="text-start">@lang('Name')</th>
                                                    <th class="text-start">@lang('Childs')</th>
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
                url: "{{ route('product.variants.index') }}",
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
                    data: 'bulk_variant_name',
                    name: 'name'
                },
                {
                    data: 'child_name',
                    name: 'child_name'
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
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    var add_more_index = 0;
    $(document).ready(function() {
        // add more variant child field
        $('.add_more_for_add').on('click', function(e) {
            e.preventDefault();
            var index = add_more_index++;
            var html = '<div class="more_variant_child mt-2 more' + index + '">';
            html += '<div class="row">';
            html += '<div class="col-md-10"> ';
            html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="@lang('Variant child')"/>';
            html += '</div>';

            html += '<div class="col-md-2 text-end">';
            html += '<a class="btn btn-sm btn-danger delete_more_for_add" data-index="' + index +
                '" href="#">X</a>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('.more_variant_child_area').append(html);
        });

        // delete add more field for adding
        $(document).on('click', '.delete_more_for_add', function(e) {
            var index = $(this).data('index');
            $('.more' + index).remove();
        })

        // Add variant by ajax
        $('#add_variant_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');

                var idValue = $('#' + inputId).val()

                if (inputId !== 'parent_category' && inputId !== 'photo') {

                    if (idValue == '') {

                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
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
                    $('#add_variant_form')[0].reset();
                    $('.loading_button').hide();
                    // getAllVariant();
                    table.ajax.reload();
                    $('.more_variant_child_area').empty();
                    $('.submit_button').prop('type', 'submit');
                },error: function(err) {
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.error').html('');
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
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

        // pass editable data to edit modal fields
        var add_more_index_for_edit = 0;
        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            add_more_index_for_edit = 0
            var url = $(this).attr('href');
                $.get(url, function(data) {
                    console.log(data)
                    $("#edit_form").html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                })
            $('.form-control').removeClass('is-invalid');
            $('.error').html('');
            var variantInfo = $(this).closest('tr').data('info');
            console.log(variantInfo);
            $('#id').val(variantInfo.id);
            $('#e_variant_name').val(variantInfo.bulk_variant_name);
            $('#e_variant_child_id').val(variantInfo.bulk_variant_child[0].id);
            $('#e_variant_child').val(variantInfo.bulk_variant_child[0].child_name);
            $('.more_variant_child_area_edit').empty();
            $.each(variantInfo.bulk_variant_child, function(key, bulk_variant_child) {
                if (add_more_index_for_edit != 0) {
                    var html = '<div class="more_variant_child mt-2 e_more' +
                        add_more_index_for_edit + '">';
                    html += '<div class="row">';
                    html += '<div class="col-md-10"> ';
                    html += '<input type="hidden" name="variant_child_ids[]" value="' +
                        bulk_variant_child.id + '"/>';
                    html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="@lang('Variant child')" value="' + bulk_variant_child.child_name + '"/>';
                    html += '</div>';
                    html += '<div class="col-md-2 text-end">';
                    html +='<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' + add_more_index_for_edit + '" href="#">X</a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.more_variant_child_area_edit').append(html);
                }
                add_more_index_for_edit++;
            });
            $('#add_form').hide();
            $('#edit_form').show();
            document.getElementById('e_variant_name').focus();
        });

        $('.add_more_for_edit').on('click', function(e) {
            e.preventDefault();
            var index = add_more_index_for_edit++;
            var html = '<div class="more_variant_child mt-2 e_more' + index + '">';
            html += '<div class="row">';
            html += '<div class="col-md-10"> ';
            html += '<input type="hidden" name="variant_child_ids[]" value="noid"/>';
            html += '<input required type="text" name="variant_child[]" class="form-control " placeholder="@lang('Variant child')"/>';
            html += ' </div>';

            html += '<div class="col-md-2 text-end">';
            html += '<a class="btn btn-sm btn-danger delete_more_for_edit" data-index="' + index +
                '" href="#">X</a>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('.more_variant_child_area_edit').append(html);
        });


        // delete add more field for adding
        $(document).on('click', '.delete_more_for_edit', function(e) {
            var index = $(this).data('index');
            $('.e_more' + index).remove();
        })

        // edit brand by ajax
        $('#edit_variant_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
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
                    $('#add_form').show();
                    $('#edit_form').hide();
                    getAllVariant();
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
                data: request,
                success: function(data) {
                    getAllVariant();
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '#close_form', function() {
            $('#add_form').show();
            $('#edit_form').hide();
        });
    });
</script>
@endpush
