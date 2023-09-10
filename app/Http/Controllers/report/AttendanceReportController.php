<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AttendanceReportController extends Controller
{
    public function attendanceReport(Request $request)
    {
        if ($request->ajax()) {
            $attendances = '';
            $query = DB::table('hrm_attendances')
                ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
                ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
                ->leftJoin('hrm_shifts', 'admin_and_users.shift_id', 'hrm_shifts.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('admin_and_users.branch_id', NULL);
                } else {
                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

            if ($request->department_id) {

                $query->where('admin_and_users.department_id', $request->department_id);
            }

            if ($request->from_date) {

                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('hrm_attendances.at_date_ts', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $attendances = $query->select(
                    'hrm_attendances.*',
                    'hrm_department.department_name',
                    'hrm_shifts.shift_name',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'admin_and_users.emp_id',
                )->orderBy('hrm_attendances.id', 'desc');
            } else {
                
                $attendances = $query->select(
                    'hrm_attendances.*',
                    'hrm_department.department_name',
                    'hrm_shifts.shift_name',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                    'admin_and_users.emp_id',
                )->where('branch_id', auth()->user()->branch_id)->orderBy('hrm_attendances.id', 'desc');
            }

            return DataTables::of($attendances)
                ->editColumn('name', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->at_date));
                })
                ->editColumn('clock_in_out', function ($row) {
                    $clockOut = $row->clock_out_ts ? ' - ' . date('h:i a', strtotime($row->clock_out)) : '';
                    return ' <b>' . date('h:i a', strtotime($row->clock_in)) . $clockOut . ' </b>';
                })
                ->editColumn('work_duration', function ($row) {
                    if ($row->clock_out_ts) {
                        $startTime = Carbon::parse($row->clock_in);
                        $endTime = Carbon::parse($row->clock_out);
                        // $totalDuration = $startTime->diffForHumans($endTime);
                        $totalDuration = $endTime->diff($startTime)->format("%H:%I:%S");
                        return $totalDuration;
                    } else {
                        return 'Clock-Out-does-not-exists';
                    }
                })
                ->rawColumns(['action', 'date', 'clock_in_out', 'work_duration'])
                ->make(true);
        }
        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $employee = DB::table('admin_and_users')
            ->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.attendance_report.attendance_report', compact('employee', 'departments', 'branches'));
    }

    public function attendanceReportPrint(Request $request)
    {
        $branch_id = '';
        $s_date = '';
        $e_date = '';
        $attendances = '';
        $query = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->leftJoin('hrm_department', 'admin_and_users.department_id', 'hrm_department.id')
            ->leftJoin('hrm_shifts', 'admin_and_users.shift_id', 'hrm_shifts.id');

        if ($request->branch_id) {

            $branch_id = $request->branch_id;
            if ($request->branch_id == 'NULL') {

                $query->where('admin_and_users.branch_id', NULL);
            } else {

                $query->where('admin_and_users.branch_id', $request->branch_id);
            }
        }

        if ($request->department_id) {
            $query->where('admin_and_users.department_id', $request->department_id);
        }

        if ($request->from_date) {
            $s_date = date('Y-m-d', strtotime($request->from_date));
            $e_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $s_date;
            //$date_range = [$s_date . ' 00:00:00', $e_date . ' 00:00:00'];
            $date_range = [Carbon::parse($s_date), Carbon::parse($e_date)->endOfDay()];
            $query->whereBetween('hrm_attendances.at_date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            
            $attendances = $query->select(
                'hrm_attendances.*',
                'hrm_department.department_name',
                'hrm_shifts.shift_name',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
                'admin_and_users.emp_id',
            )->orderBy('hrm_attendances.id', 'desc')->get();
        } else {
            $attendances = $query->select(
                'hrm_attendances.*',
                'hrm_department.department_name',
                'hrm_shifts.shift_name',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
                'admin_and_users.emp_id',
            )->where('branch_id', auth()->user()->branch_id)->orderBy('hrm_attendances.id', 'desc')->get();
        }

        return view('reports.attendance_report.ajax_view.attendance_report_print', compact('attendances', 'branch_id', 's_date', 'e_date'));
    }
}
