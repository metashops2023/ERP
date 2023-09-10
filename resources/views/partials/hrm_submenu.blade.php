<div class="row">
    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.dashboard.index') }}" class="bar-link">
                <span><i class="fas fa-tachometer-alt"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.hrm_dashboard')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.leave.type') }}" class="bar-link">
                <span><i class="fas fa-th-large"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.leave_type')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.leave') }}" class="bar-link">
                <span><i class="fas fa-level-down-alt"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.leave')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.attendance.shift') }}" class="bar-link">
                <span><i class="fas fa-network-wired"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.shift')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.attendance') }}" class="bar-link">
                <span><i class="fas fa-paste"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.attendance')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.allowance') }}" class="bar-link">
                <span><i class="fas fa-plus"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.allowance_deduction')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.payroll.index') }}" class="bar-link">
                <span><i class="far fa-money-bill-alt"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.payroll')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.holidays') }}" class="bar-link">
                <span><i class="fas fa-toggle-off"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.holiday')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.departments') }}" class="bar-link">
                <span><i class="far fa-building"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.department')</p>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
        <div class="switch_bar">
            <a href="{{ route('hrm.designations') }}" class="bar-link">
                <span><i class="fas fa-map-marker-alt"></i></span>
            </a>
        </div>
        <p class="switch_text">@lang('menu.designation')</p>
    </div>
</div>













<section>
    <!-- ======================BODY CONTENT================== -->
    <div class="sec-name">
        <div class="breadCrumbHolder module w-100">
            <div id="breadCrumb3" class="breadCrumb module">
                <ul>
                    <li>
                        <a href="" class="text-primary"><i class="fas fa-tachometer-alt"></i> <b>@lang('menu.hrm')</b></a>
                    </li>

                    @if (auth()->user()->permission->hrms['leave_type'] == '1')
                        <li>
                            <a href="{{ route('hrm.leave.type') }}" class="text-white "><i class="fas fa-th-large"></i> <b>@lang('Leave Types')</b></a>
                        </li>
                    @endif

                    @if (auth()->user()->permission->hrms['leave_approve'] == '1')
                        <li>
                            <a href="{{ route('hrm.leave') }}" class="text-white "><i class="fas fa-level-down-alt"></i> <b>@lang('menu.leave')</b></a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('hrm.attendance.shift') }}" class="text-white "><i class="fas fa-network-wired"></i> <b>@lang('menu.shift')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.attendance') }}" class="text-white "><i class="fas fa-paste"></i> <b>@lang('menu.attendance')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.allowance') }}" class="text-white "><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.payroll.index') }}" class="text-white "><i class="far fa-money-bill-alt"></i> <b>@lang('menu.payroll')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.holidays') }}" class="text-white "><i class="fas fa-toggle-off"></i> <b>@lang('menu.holiday')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
                    </li>

                    <li>
                        <a href="{{ route('hrm.designations') }}" class="text-white "><i class="fas fa-map-marker-alt"></i> <b>@lang('menu.designation')</b></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card pt-3 px-4 mt-1">
        <div class="card-title ps-4">
            <h5 class="text-start text-primary pl-5">
                <i class="fas fa-tachometer-alt"></i>
                <span class="">@lang('HRM')</span> Dashboard
            </h5>
        </div>

        <div class="card-title mt-1 ps-4">
            <select name="branch_id" id="branch_id" class="form-control w-25 submit_able" autofocus>
                <option value="">@lang('All Business Lacation')</option>
                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                {{-- @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                @endforeach --}}
            </select>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="preloader_area" style="position: relative;">
                        <div class="data_preloader mt-4">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                        </div>
                    </div>

                    <div class="form_element users_data">
                        <div class="section-header d-flex justify-content-between align-items-center px-3">
                            <h6><span class="fas fa-users"></span>@lang('Users')</h6>
                            <span class="badge bg-secondary text-white">
                                <div id="small-badge">@lang('Total'): 4324</div>
                            </span>
                        </div>
                        <div class="widget_content">
                            <div class="mtr-table">
                                <div class="table-responsive" id="user_data">
                                    <table id="users_table" class="display data__table data_tble stock_table compact" width="100%">
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="preloader_area" style="position: relative;">
                        <div class="data_preloader mt-4">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                        </div>
                    </div>

                    <div class="form_element today_attendance_table">
                        <div class="section-header d-flex justify-content-between align-items-center px-3">
                            <h6>
                                <span class="fas fa-user-check"></span>
                                @lang("Today's Attendance")
                            </h6>
                        </div>

                        <div class="widget_content">
                            <div class="mtr-table">
                                <div class="table-responsive" id="today_attendance_table">
                                    <table class="display data__table data_tble stock_table compact"
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="preloader_area" style="position: relative;">
                        <div class="data_preloader mt-4">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                        </div>
                    </div>
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
                                        class="display data__table data_tble stock_table compact mt-2" width="100%">
                                        <tbody class="mx-2 mt-5" id="leaves">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
