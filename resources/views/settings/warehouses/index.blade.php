@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-warehouse"></span>
                                <h5>@lang('Warehouses')</h5>
                            </div>
                        </div>

                        @if ($addons->branches == 1)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="sec-name">
                                        <div class="col-md-12">
                                            <form action="" method="get" class="px-2">
                                                <div class="form-group row">
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able"
                                                                id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option selected value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-4">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Add Warehouse') </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_warehouse_form" action="{{ route('settings.warehouses.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label><b>@lang('Warehouse Name') :</b>  <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control add_input" data-name="Warehouse name" id="name" placeholder="@lang('Warehouse name')"/>
                                            <span class="error error_name"></span>
                                        </div>

                                        <div class="form-group mt-1">
                                            <label><b>@lang('Warehouse Code') :</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Warehouse code must be unique." class="fas fa-info-circle tp"></i></label>
                                            <input type="text" name="code" class="form-control add_input" data-name="Warehouse code" id="code" placeholder="@lang('Warehouse code')"/>
                                            <span class="error error_code"></span>
                                        </div>

                                        <div class="form-group mt-1">
                                            <label><b>@lang('Phone') :</b>  <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control add_input" data-name="Phone number" id="phone" placeholder="@lang('Phone number')"/>
                                            <span class="error error_phone"></span>
                                        </div>

                                        <div class="form-group mt-1">
                                            <label><b>@lang('Address') :</b>  </label>
                                            <textarea name="address" class="form-control" placeholder="@lang('Warehouse address')" rows="3"></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label><strong>@lang('Under Business Location') :</strong></label>
                                            <select name="branch_ids[]" id="branch_id" class="form-control select2" multiple="multiple">
                                                <option value="NULL">
                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (HO)
                                                </option>

                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_business_location"></span>
                                        </div>

                                        <div class="form-group text-end mt-3">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                            <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
                                            <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card d-none" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>@lang('Edit Warehouse') </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2" id="edit_form_body">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Warehouse')</h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">@lang('Name')</th>
                                                    <th class="text-start">@lang('Business Location')</th>
                                                    <th class="text-start">@lang('Warehouse Code')</th>
                                                    <th class="text-start">@lang('Phone')</th>
                                                    <th class="text-start">@lang('Address')</th>
                                                    <th class="text-start">@lang('Actions')</th>
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
<script src="backend/asset/js/select2.min.js"></script>
<script>
    //  $('.select2').select2({
    //     placeholder: "Select under business location",
    //     allowClear: true
    // });

    var table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
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
        dom: "lBfrtip",
        buttons: [
            //{extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[2, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('settings.warehouses.index') }}",
            "data": function(d) {d.branch_id = $('#branch_id').val();}
        },
        columnDefs: [{"targets": [0, 6],"orderable": false,"searchable": false}],
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'warehouses.warehouse_name'},
            {data: 'branch',name: 'branches.name'},
            {data: 'code',name: 'warehouses.warehouse_code'},
            {data: 'phone',name: 'phone'},
            {data: 'address',name: 'address'},
            {data: 'action',name: 'action'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
    });

    // Setup CSRF Token for ajax request
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add Warehouse by ajax
        $('#add_warehouse_form').on('submit', function(e){
            e.preventDefault();
             $('.loading_button').show();
             $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;

            $.each(inputs, function(key, val){

                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val()

                if(idValue == ''){

                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){

                 $('.loading_button').hide();
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    toastr.success(data);
                    $('#add_warehouse_form')[0].reset();
                    $('.loading_button').hide();
                    table.ajax.reload();
                    $(".select2").select2().val('').trigger('change');
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_form_body').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var id = $(this).data('id');
            $('#deleted_form').attr('action', url);
            $('#deleteId').val(id);
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
                type:'delete',
                data:request,
                success:function(data){

                    if($.isEmptyObject(data.errorMsg)){

                        toastr.error(data);
                        table.ajax.reload();
                    }else{

                        toastr.error(data.errorMsg, 'Error');
                    }
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
