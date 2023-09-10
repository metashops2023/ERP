@extends('layout.master')
@push('stylesheets') @endpush
{{-- @section('title', 'User List - ') --}}
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <!-- =====================================================================BODY CONTENT================== -->
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-user"></span>
                            <h5>@lang('Users')</h5>
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
                                            <div class="col-md-4">
                                                <label><strong>@lang('Business Location') :</strong></label>
                                                <select name="branch_id" class="form-control submit_able" id="branch_id">
                                                    <option value="">@lang('All')</option>
                                                    <!-- <option value="NULL"> {{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option> -->
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

                <div class="row margin_row mt-1">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-10">
                                <h6>@lang('All User') </h6>
                            </div>
                            @if (auth()->user()->permission->user['user_add'] == '1')
                            <div class="col-md-2">
                                <div class="btn_30_blue float-end">
                                    <a href="{{ route('users.create') }}"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="widget_content">
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('Username')</th>
                                            <th class="text-start">@lang('Name')</th>
                                            <th class="text-start">@lang('B.Location')</th>
                                            <th class="text-start">@lang('Role')</th>
                                            <th class="text-start">@lang('Department')</th>
                                            <th class="text-start">@lang('Designation')</th>
                                            <th class="text-start">@lang('Email')</th>
                                            <th class="text-start">@lang('Salary')</th>
                                            <th class="text-start">@lang('Action')</th>
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
@endsection
@push('scripts')

<script>
    // Show session message by toster alert.
    @if(Session::has('successMsg'))
    toastr.success('{{ session('
        successMsg ') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
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
                text: '<i class="fas fa-print"></i> @lang("Print")',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
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
        aaSorting: [
            [8, 'asc']
        ],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('users.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        },
        columns: [{
                data: 'username',
                name: 'username'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'role_name',
                name: 'roles.name'
            },
            {
                data: 'dep_name',
                name: 'hrm_department.department_name'
            },
            {
                data: 'des_name',
                name: 'hrm_designations.designation_name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'salary',
                name: 'salary'
            },
            {
                data: 'action'
            },
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function() {
        table.ajax.reload();
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
            data: request,
            success: function(data) {
                table.ajax.reload();
                toastr.error(data);
            }
        });
    });
</script>
@endpush
