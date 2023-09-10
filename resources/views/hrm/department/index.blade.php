@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
{{-- @section('title', 'HRM Departments - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        @if (auth()->user()->permission->hrms['hrm_dashboard'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.dashboard.index') }}" class="text-white"><i class="fas fa-tachometer-alt"></i> <b>@lang('menu.hrm')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave.type') }}" class="text-white "><i class="fas fa-th-large"></i> <b>@lang('Leave Types')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['leave_assign'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.leave') }}" class="text-white"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['shift'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.attendance.shift') }}" class="text-white"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['attendance'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.attendance') }}" class="text-white"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['view_allowance_and_deduction'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.allowance') }}" class="text-white"><i class="fas fa-plus text-primary"></i> <b>@lang('menu.allowance_deduction')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['payroll'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.payroll.index') }}" class="text-white "><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['holiday'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.holidays') }}" class="text-white "><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['department'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building text-primary"></i> <b>@lang('menu.department')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->hrms['designation'] == '1')
                                            <li>
                                                <a href="{{ route('hrm.designations') }}" class="text-white "><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('Departments')</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Serial')</th>
                                                    <th>@lang('Photo')</th>
                                                    <th>@lang('Name')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Department')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_department_form" action="{{ route('hrm.departments.store') }}">
                        <div class="form-group">
                            <label><b>@lang('Department Name') :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" class="form-control" data-name="Department name" placeholder="@lang('Department name')" required/>
                        </div>

                        <div class="form-group">
                            <label><b>@lang('Department ID') :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="department_id" class="form-control" data-name="Department ID" placeholder="@lang('Department name')" required/>
                            <small class="text-danger">@lang('Department ID must be Unique')</small>
                        </div>

                        <div class="form-group mt-1">
                            <div class="form-group">
                                <label><b>@lang('Department Details') :</b> </label>
                                <textarea name="description" class="form-control" placeholder="@lang('Department Details')"></textarea>
                            </div>
                        </div>

                        <div class="form-group  mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Department')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_department_form" action="{{ route('hrm.departments.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><b>@lang('Department Name') :</b>  <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" class="form-control" data-name="Department name" placeholder="@lang('Department name')" id="e_department_name" required/>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Department ID') :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="department_id" class="form-control" data-name="Department ID" placeholder="@lang('Department name')" id="e_department_id" required/>
                            <small class="text-danger">@lang('Department id must be Unique')</small>
                        </div>

                        <div class="form-group mt-1">
                            <div class="form-group">
                                <label><b>@lang('Department Details') :</b> </label>
                                <textarea name="description" class="form-control" placeholder="@lang('Department Details')" id="e_description"></textarea>
                            </div>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save Change')</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Get all category by ajax
    function getAllDepartment(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('hrm.departments.all') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllDepartment();

     // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add department by ajax
        $('#add_department_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').hide();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_department_form')[0].reset();
                    $('.loading_button').hide();
                    getAllDepartment();
                    $('#addModal').modal('hide');
                }
            });
        });


        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            var departmentInfo = $(this).closest('tr').data('info');
            $('#id').val(departmentInfo.id);
            $('#e_department_name').val(departmentInfo.department_name);
            $('#e_department_id').val(departmentInfo.department_id);
            $('#e_description').val(departmentInfo.description);
            $('#editModal').modal('show');
        });

        // edit category by ajax
        $('#edit_department_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            $('.submit_button').hide();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                    getAllDepartment();
                    $('#editModal').modal('hide');
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
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                async:false,
                data:request,
                success:function(data){
                    getAllDepartment();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>
@endpush
