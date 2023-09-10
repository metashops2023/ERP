@extends('layout.master')
@push('stylesheets')
<link href="/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
{{-- @section('title', 'Assets - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>@lang('Assets')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_tax_report_form" action="" method="get">
                                            @csrf
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="filter_branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $br)
                                                                    <option value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                    @endif
                                                @endif

                                                <div class="col-md-3">
                                                    <label><strong>@lang('Asset Type') :</strong></label>
                                                    <select name="type_id" class="form-control submit_able" id="filter_type_id" autofocus>

                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row px-3 mt-1">
                            <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                            <div class="card">
                                <div class="card-body">
                                    <!--begin: Datatable-->
                                    <div class="tab_list_area">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a id="tab_btn" data-show="asset_type" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> @lang('Asset Types')</a>
                                            </li>

                                            <li>
                                                <a id="tab_btn" data-show="assets" class="tab_btn" href="#">
                                                <i class="fas fa-scroll"></i> @lang('Assets')</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="tab_contant asset_type">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="btn_30_blue float-end">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addAssetTypeModal"><i
                                                            class="fas fa-plus-square"></i> @lang('Add Type')</a>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive" >
                                                    <table class="display data_tbl data__table asset_type_table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('S/L')</th>
                                                                <th>@lang('Type')</th>
                                                                <th>@lang('Type Code')</th>
                                                                <th>@lang('Action')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <form id="deleted_asset_type_form" action="" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab_contant assets">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="btn_30_blue float-end">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addAssetModal"><i
                                                            class="fas fa-plus-square"></i> @lang('Add Asset')</a>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="display data_tbl data__table asset_table w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('S/L')</th>
                                                                <th>@lang('Asset')</th>
                                                                <th>@lang('Type')</th>
                                                                <th>@lang('Available Loaction')</th>
                                                                <th>@lang('Quantity')</th>
                                                                <th>@lang('Per Unit Value')</th>
                                                                <th>@lang('Total Value')</th>
                                                                <th>@lang('Action')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                    <form id="deleted_asset_form" action="" method="post">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Add Asset Type Modal -->
    <div class="modal fade" id="addAssetTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Asset Type')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_assset_type_form" action="{{ route('accounting.assets.asset.type.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('Type Name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="asset_type_name" class="form-control" id="asset_type_name"
                                    placeholder="@lang('Asset Type name')" />
                                <span class="error error_asset_type_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Type Code') :</b> </label>
                                <input type="text" name="asset_type_code" class="form-control" placeholder="@lang('Asset Type Code')"/>
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
            </div>
        </div>
    </div>
    <!-- Add Asset Type Modal -->

    <!-- Edit Asset Type Modal -->
    <div class="modal fade" id="editAssetTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Asset Type')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_asset_type_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!-- Edit Asset Type Modal -->

     <!-- Add Asset Modal -->
    <div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Asset')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_assset_form" action="{{ route('accounting.assets.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('Asset Name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="asset_name" class="form-control" id="asset_name"
                                    placeholder="@lang('Asset Type name')" autofocus/>
                                <span class="error error_asset_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Asset Type') :</b> <span class="text-danger">*</span></label>
                                <select name="type_id" class="form-control" id="type_id" >
                                <option value="">@lang('Select Asset Type')</option>
                                </select>
                            <span class="error error_type_id"></span>
                            </div>
                        </div>

                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                            <div class="form-group row mt-1">
                                <div class="col-md-12">
                                    <label><b>@lang('Branch') :</b> <span class="text-danger">*</span></label>
                                    <select name="branch_id" class="form-control" id="branch_id">
                                        <option value="">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                        @foreach ($branches as $br)
                                            <option value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_branch_id"></span>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Quantity') :</b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="quantity" class="form-control" id="quantity"
                                    placeholder="@lang('Asset Quantity')"/>
                                <span class="error error_quantity"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Per Unit Value') :</b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="per_unit_value" class="form-control" id="per_unit_value"
                                    placeholder="@lang('Per Unit Value')"/>
                                <span class="error error_per_unit_value"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Total Value') :</b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="total_value" class="form-control" id="total_value"
                                    placeholder="@lang('Total Asset Value')" />
                                <span class="error error_total_value"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <!-- Add Asset Modal -->

    <!-- Add Asset Modal -->
    <div class="modal fade" id="editAssetModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Asset')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_asset_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
 <!-- Add Asset Modal -->
@endsection
@push('scripts')
<script>
    var asset_type_table = $('.asset_type_table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf', messageTop: 'Asset types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")', messageTop: '<b>@lang('Asset types')</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
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
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        ajax: "{{ route('accounting.assets.index') }}",
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'asset_type_name',name: 'asset_type_name'},
            {data: 'asset_type_code',name: 'asset_type_code'},
            {data: 'action',name: 'action'},
        ],
    });

    $(document).on('submit', '#add_assset_type_form', function(e) {
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
                $('#add_assset_type_form')[0].reset();
                $('.loading_button').hide();
                $('#addAssetTypeModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                asset_type_table.ajax.reload();
                getFormAssetTypes();
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
                $('#edit_asset_type_modal_body').html(data);
                $('#editAssetTypeModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#edit_assset_type_form', function(e) {
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
                $('#editAssetTypeModal').modal('hide');
                $('.error').html('');
                asset_type_table.ajax.reload();
                getFormAssetTypes();
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

    $(document).on('click', '#delete_type',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_asset_type_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_asset_type_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_asset_type_form', function(e) {
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
                asset_type_table.ajax.reload();
                getFormAssetTypes();
                $('#deleted_asset_type_form')[0].reset();
            }
        });
    });

    function getFormAssetTypes(){
        $.ajax({
            url:"{{route('accounting.assets.form.asset.type')}}",
            success:function(types){
                $('#type_id').empty();
                $('#filter_type_id').empty();
                $('#type_id').append('<option value="">@lang('Select Asset Type')</option>');
                $('#filter_type_id').append('<option value="">@lang('All')</option>');
                $.each(types, function(key, val){
                    $('#type_id').append('<option value="'+val.id+'">'+val.asset_type_name+'</option>');
                    $('#filter_type_id').append('<option value="'+val.id+'">'+val.asset_type_name+'</option>');
                });
            }
        });
    }
    getFormAssetTypes();
</script>

<script>
    var asset_table = $('.asset_table').DataTable({
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
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        "ajax": {
            "url": "{{ route('accounting.assets.all') }}",
            "data": function(d) {
                d.branch_id = $('#filter_branch_id').val();
                d.type_id = $('#filter_type_id').val();
            }
        },
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'asset_name',name: 'asset_name'},
            {data: 'asset_type',name: 'asset_type'},
            {data: 'branch',name: 'branch'},
            {data: 'quantity',name: 'quantity'},
            {data: 'per_unit_value',name: 'per_unit_value'},
            {data: 'total_value',name: 'total_value'},
            {data: 'action',name: 'action'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        asset_table.ajax.reload();
    });

    $(document).on('submit', '#add_assset_form', function(e) {
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
                $('#add_assset_form')[0].reset();
                $('.loading_button').hide();
                $('#addAssetModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                asset_table.ajax.reload();
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
    $(document).on('click', '#edit_asset', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#edit_asset_modal_body').html(data);
                $('#editAssetModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#edit_assset_form', function(e) {
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
                $('#editAssetModal').modal('hide');
                $('.error').html('');
                asset_table.ajax.reload();
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

    $(document).on('click', '#delete_asset',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_asset_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_asset_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_asset_form', function(e) {
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
                asset_table.ajax.reload();
                $('#deleted_asset_form')[0].reset();
            }
        });
    });

    function calculateAddAssetValue() {
        var asset_qty = $('#quantity').val() ? $('#quantity').val() : 0;
        var per_unit_value = $('#per_unit_value').val() ? $('#per_unit_value').val() : 0;
        var total_value = parseFloat(asset_qty) * parseFloat(per_unit_value);
        $('#total_value').val(parseFloat(total_value).toFixed(2))
    }

    $('#quantity').on('input', function () {
        calculateAddAssetValue();
    });

    $('#per_unit_value').on('input', function () {
        calculateAddAssetValue();
    });

    function calculateEditAssetValue() {
        var asset_qty = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var per_unit_value = $('#e_per_unit_value').val() ? $('#e_per_unit_value').val() : 0;
        var total_value = parseFloat(asset_qty) * parseFloat(per_unit_value);
        $('#e_total_value').val(parseFloat(total_value).toFixed(2))
    }

    $(document).on('input', '#e_quantity', function () {
        calculateEditAssetValue();
    });

    $(document).on('input', '#e_per_unit_value', function () {
        calculateEditAssetValue();
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });
</script>
@endpush
