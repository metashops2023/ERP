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
            <div class="sec-name">
                <div class="breadCrumbHolder module w-100">
                    <div id="breadCrumb3" class="breadCrumb module">
                        <ul>
                            @if (auth()->user()->permission->hrms['hrm_dashboard'] == '1')
                                <li>
                                    <a href="{{ route('hrm.dashboard.index') }}" class="text-white"><i class="fas fa-tachometer-alt text-primary"></i> <b>@lang('menu.hrm')</b></a>
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
                                    <a href="{{ route('hrm.allowance') }}" class="text-white"><i class="fas fa-plus"></i> <b>@lang('menu.allowance_deduction')</b></a>
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
                                    <a href="{{ route('hrm.departments') }}" class="text-white "><i class="far fa-building"></i> <b>@lang('menu.department')</b></a>
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

        <div class="card pt-3 px-4 mt-1">
            <div class="card-title mt-4 ps-4">
                <h1 class="text-start text-primary pl-5">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="">@lang('HRM')</span>
                </h1>
            </div>

            @if ($addons->branches == 1)
                <div class="card-title mt-2 ps-4">
                    <select name="branch_id" id="branch_id" class="form-control w-25 submit_able" autofocus>
                        <option value="">@lang('All Business Lacation')</option>
                        <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

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
                                    <span class="far fa-file-alt">@lang('Leave Applications')</span>
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
                                    <span class="far fa-file-alt"> @lang('Holidays') </span>
                                </h6>
                            </div>
                            <div class="widget_content">
                                <div class="px-3 pt-2 pb-2">
                                    <div class="px-1">
                                        <span><strong>@lang('Upcoming Holidays'):</strong></span>
                                    </div>
                                    <ul class="list-group list-group-flush upcoming_holiday_list">
                                        <li class="list-group-item list-group-item-warning">@lang('A simple warning list group item')</li>
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
        function getUserTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.user.count.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('.users_data').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getUserTable();

        function getTodayAttTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.today.attr.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('#today_attendance_table').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getTodayAttTable();

        function getLeaveTable(){
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url:"{{ route('hrm.dashboard.leave.table') }}",
                type:'get',
                data: { branch_id },
                success:function(data){
                    $('#leaves').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getLeaveTable();

        function upcomingHolidays(){
            $('.data_preloader').show();
            $.ajax({
                url:"{{ route('hrm.dashboard.upcoming.holidays') }}",
                type:'get',
                success:function(data){
                    $('.upcoming_holiday_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        upcomingHolidays();

        $(document).on('change', '.submit_able', function () {
            getUserTable();
            getTodayAttTable();
            getLeaveTable();
        });
    </script>
@endpush
