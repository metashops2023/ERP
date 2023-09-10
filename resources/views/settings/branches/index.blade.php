@extends('layout.master')
@push('stylesheets')
@endpush
{{-- @section('title', 'Business Location List - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-code-branch"></span>
                                <h5>@lang('Business Locations')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('All Business Locations')</h6>
                                </div>

                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a id="create" href="{{ route('settings.branches.create') }}">
                                                <i class="fas fa-plus-square"> @lang('Business location') </i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
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
                                                <th class="text-white">@lang('B.Location')</th>
                                                <th class="text-white">@lang('Branch Code')</th>
                                                <th class="text-white">@lang('Phone')</th>
                                                <th class="text-white">@lang('City')</th>
                                                <th class="text-white">@lang('State')</th>
                                                <th class="text-white">@lang('Zip-Code')</th>
                                                <th class="text-white">@lang('Country')</th>
                                                <th class="text-white">@lang('Email')</th>
                                                <th class="text-white">@lang('Status')</th>
                                                <th class="text-white">@lang('Actions')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Business Location')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_branch_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="quickInvSchemaModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Invoice Schema')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="quick_schema_add_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Business Location')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="edit-modal-body"></div>
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
                url: "{{ route('settings.branches.index') }}",
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
                    data: 'branch_code',
                    name: 'branch_code'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'zip_code',
                    name: 'zip_code'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'email',
                    name: 'email'
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
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}} );

    // call jquery method
    $(document).ready(function(){

        // pass editable data to edit modal fields
        $(document).on('click', '.edit', function(e){
            e.preventDefault();
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
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#edit-modal-body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#create', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#add_branch_modal_body').html(data);
                    $('#addBranchModal').modal('show');
                    $('.data_preloader').hide();
                },error:function(err){
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });



        $(document).on('click', '#add_inv_schema', function(e) {
            e.preventDefault();
            console.log('Clicked');
            $('.data_preloader').show();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#quick_schema_add_modal_body').html(data);
                    $('#quickInvSchemaModal').modal('show');
                    $('.data_preloader').hide();
                    table.ajax.reload();
                },error:function(err){
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
                    }
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
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'delete',
                data:request,
                success:function(data){
                    getAllBranch();
                    toastr.error(data);
                }
            });
        });
    });
</script>
@endpush
