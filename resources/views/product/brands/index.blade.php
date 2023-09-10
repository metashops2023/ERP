@extends('layout.master')
@push('stylesheets')
@endpush
{{-- @section('title', 'All Brand - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class=" border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-band-aid"></span>
                                <h5>@lang('Brands')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        @if (auth()->user()->permission->product['brand'] == '1')
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>@lang('Add Brand') </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2">
                                        <form id="add_brand_form" action="{{ route('product.brands.store') }}">
                                            <div class="form-group">
                                                <label><b>@lang('brand.name') :</b> <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control  add_input"
                                                    data-name="Brand name" id="name" placeholder="@lang('Brand Name')" />
                                                <span class="error error_name"></span>
                                            </div>

                                            <div class="form-group mt-1">
                                                <label><b>@lang('brand.brand_photo') :</b></label>
                                                <input type="file" name="photo" class="form-control"
                                                    data-max-file-size="2M" id="photo" accept=".jpg, .jpeg, .png, .gif">
                                                <span class="error error_photo"></span>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn loading_button d-none"><i
                                                            class="fas fa-spinner text-primary"></i><b>
                                                            @lang('Loading')...</b></button>
                                                    <button type="submit"
                                                        class="c-btn button-success float-end submit_button me-0">@lang('Save')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card d-none" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>@lang('Edit Brand') </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2" id="edit_form_body"></div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Brand')</h6>
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
                                                    <th>@lang('Photo')</th>
                                                    <th>@lang('Name')</th>
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
                url: "{{ route('product.brands.index') }}",
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
                    data: 'photo',
                    name: 'photo'
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

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add brand by ajax
            $('#add_brand_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').removeClass('d-none');
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
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_brand_form')[0].reset();
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        table.ajax.reload();
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $("#edit_form_body").html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                })
            });

            // edit brand by ajax
            $(document).on('submit', '#edit_brand_form', function(e) {
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
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('.error').html('');
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#add_form').show();
                        $('#edit_form').hide();
                    }
                });
            });

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': '@lang('brand.delete_alert')',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-modal-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-danger',
                            'action': function() {
                                console.log('Deleted canceled.');
                            }
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
                        $('.data_tbl').DataTable().ajax.reload();
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
