@extends('layout.master')
{{-- @section('title', 'HRM Dashboard - ') --}}
    @push('stylesheets')
        <style>
            #small-badge {font-size: 12px !important;padding: 0px !important;}
            .leave_application table.display thead th {padding: 0px 10px 0px 10px;border-top: none;border-bottom: none;}
            .leave_application .dataTables_wrapper {border-bottom: none;border-top: none;-webkit-box-shadow: none;}
        </style>
    @endpush
@section('content')
    <section>
        <div class="main__content">
            <!-- =====================================================================BODY CONTENT================== -->
            <div class="sec-name">
                <div class="breadCrumbHolder module w-100">
                    <div id="breadCrumb3" class="breadCrumb module">
                        <ul>
                            <li>
                                <a href="" class="text-primary"><i class="fas fa-tachometer-alt"></i> <b>@lang('HRM')</b></a>
                            </li>

                            @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                <li>
                                    <a href="{{ route('hrm.leave.type') }}" class="text-dark text-muted"><i class="fas fa-th-large"></i> <b>@lang('Leave Types')</b></a>
                                </li>
                            @endif

                            @if (auth()->user()->permission->hrms['leave_approve'] == '1')
                                <li>
                                    <a href="{{ route('hrm.leave') }}" class="text-dark text-muted"><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('hrm.attendance.shift') }}" class="text-dark text-muted"><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.attendance') }}" class="text-dark text-muted"><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.allowance') }}" class="text-dark text-muted"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.payroll.index') }}" class="text-dark text-muted"><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.holidays') }}" class="text-dark text-muted"><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.departments') }}" class="text-dark text-muted"><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                            </li>

                            <li>
                                <a href="{{ route('hrm.designations') }}" class="text-dark text-muted"><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card pt-3 px-4 mt-1">
            <div class="card-title mt-4 ps-4">
                <h1 class="text-start text-primary pl-5">
                    <i class="fas fa-anchor"></i>
                    <span class="">@lang('HRM')</span> Dashboard
                </h1>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="fas fa-users"></span>
                                    Users
                                </h6>
                                <span class="badge bg-secondary text-white">
                                    <div id="small-badge">
                                        Total: 4324
                                    </div>
                                </span>
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive">
                                        <table id="attendance_table"
                                            class="display data__table data_tble stock_table compact" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Department')</th>
                                                    <th>@lang('Total')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>@lang('Branch Manger')</td>
                                                    <td>125</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('HRM')</td>
                                                    <td>23</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('CRM')</td>
                                                    <td>15</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="fas fa-user-check"></span>
                                    @lang("Today's Attendance")
                                </h6>
                                {{-- <h6 class="">4324</h6> --}}
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive">
                                        <table id="users_table" class="display data__table data_tble stock_table compact"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Employee')</th>
                                                    <th>@lang('Clock-in Time')</th>
                                                    <th>@lang('Clock-out Time')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('John Doe')</td>
                                                    <td>10:00am</td>
                                                    <td>04:00pm</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="far fa-file-alt"></span>
                                    Leave Applications
                                </h6>
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive leave_application">
                                        <table id="leave_application_table"
                                            class="display data__table data_tble stock_table compact " width="100%">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        {{-- Application Links --}}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="mx-2 mt-5">
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            James Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            John Doe Leave Application Link Goes here
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form_element">
                            <div class="section-header d-flex justify-content-between align-items-center px-3">
                                <h6>
                                    <span class="far fa-file-alt"></span>
                                    Holidays
                                </h6>
                            </div>
                            <div class="widget_content">
                                <div class="px-3 pt-2">
                                    <div class="px-1"><strong>@lang('Today'):</strong></div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-success">@lang('Its')' work day</li>
                                    </ul>
                                </div>
                                <div class="px-3 pt-2 pb-2">
                                    <div class="px-1">
                                        <span><strong>@lang('Upcoming Holidays'):</strong></span>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item list-group-item-warning">A simple warning list group item
                                        </li>
                                        <li class="list-group-item list-group-item-warning">A simple warning list group item
                                        </li>
                                        <li class="list-group-item list-group-item-warning">A simple warning list group item
                                        <li class="list-group-item list-group-item-warning">A simple warning list group item
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        const usersTable = $('#users_table').DataTable({
            dom: "Bfrtip",
            buttons: ["excel", "pdf", "print"],
            pageLength: 5,
        });

        const attendanceTable = $('#attendance_table').DataTable({
            dom: "Bfrtip",
            buttons: ["excel", "pdf", "print"],
            pageLength: 5,
        });
        const leaveApplicationTable = $('#leave_application_table').DataTable({
            dom: "Bfrtip",
            pageLength: 6,
            ordering: false,
            info: false,
            // searching: false,
        });
        // const holidaysTable = $('#holidays_table').DataTable({
        //     dom: "Bfrtip",
        //     pageLength: 5,
        //     ordering: false,
        //     info: false,
        //     // searching: false,
        // });

    </script>
@endpush
