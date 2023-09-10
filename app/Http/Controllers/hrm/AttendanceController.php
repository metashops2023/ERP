<?php

namespace App\Http\Controllers\hrm;

use Carbon\Carbon;
use App\Models\Hrm\Shift;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\Hrm\Attendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //attendance index page
    public function index(Request $request)
    {
        // $origin = date_create('2009-10-11');
        // $target = date_create('2009-10-13');
        // $interval = date_diff($origin, $target);
        // return  $interval->format('%R%a days');

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->select('business')->first();
			$attendances = '';
			$query = DB::table('hrm_attendances')
				->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
				->leftJoin('hrm_shifts', 'admin_and_users.shift_id', 'hrm_shifts.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('admin_and_users.branch_id', NULL);
                } else {

                    $query->where('admin_and_users.branch_id', $request->branch_id);
                }
            }

			if ($request->user_id) {

				$query->where('hrm_attendances.user_id', $request->user_id);
			}

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('hrm_attendances.at_date_ts', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $attendances = $query->select(
                    'hrm_attendances.*',
                    'hrm_shifts.shift_name',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                );
            }else {
                $attendances = $query->select(
                    'hrm_attendances.*',
                    'hrm_shifts.shift_name',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                )->where('branch_id', auth()->user()->branch_id);
            }

			return DataTables::of($attendances)
				->addColumn('action', function ($row) {
					$html = '';
					$html .= '<div class="dropdown table-dropdown">';
					$html .= '<a href="' . route('hrm.attendance.edit', [$row->id]) . '" class="btn btn-sm btn-primary me-1" id="edit_attendance" title="Edit">';
					$html .= '<i class="la la-edit"></i> Edit';
					$html .= '</a>';

					$html .= '<a href="' . route('hrm.attendance.delete', [$row->id]) . '" class="btn btn-sm btn-danger" id="delete">';
					$html .= '<i class="la la-trash"></i> Delete';
					$html .= '</a>';
					$html .= '</div>';
					return $html;
				})
                ->editColumn('name', function ($row) {

					return $row->prefix.' '.$row->name.' '.$row->last_name;
				})
				->editColumn('date', function ($row) use ($generalSettings) {

					return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->at_date));
				})
				->editColumn('clock_in_out', function ($row) {

                    $clockOut = $row->clock_out_ts ? ' - ' . date('h:i a', strtotime($row->clock_out)) : '';
                    return ' <b>'.date('h:i a', strtotime($row->clock_in)) .$clockOut.' </b>';
				})
				->editColumn('work_duration', function ($row) {

					if ($row->clock_out_ts){

                        $startTime = Carbon::parse($row->clock_in);
                        $endTime = Carbon::parse($row->clock_out);
                        // $totalDuration = $startTime->diffForHumans($endTime);
                        $totalDuration = $endTime->diff($startTime)->format("%H:%I:%S");
                        return $totalDuration;
                    }else{

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
        return view('hrm.attendance.index', compact('employee', 'departments', 'branches'));
    }

    //attendance store method
    public function storeAttendance(Request $request)
    {
        //date('Y-m-d h:i:s');
        //return date('h:i:s', strtotime('10:12 PM'));
        //return $request->all();
        if ($request->user_ids == null) {
            return response()->json([ 'errorMsg' => 'Select employee first for attendance.']);
        }

        foreach ($request->user_ids as $key => $user_id) {
            $updateAttendance = Attendance::whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
                ->where('user_id', $user_id)
                ->where('is_completed', 0)
                ->orderBy('id', 'desc')
                ->first();
            if ($updateAttendance) {
                // $updateAttendance->user_id = $user_id;
                // $updateAttendance->at_date_ts = date('Y-m-d');
                // $updateAttendance->clock_in = $request->clock_ins[$key];
                // $updateAttendance->clock_in_ts = date('Y-m-d ') . $request->clock_ins[$key];
                $updateAttendance->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $updateAttendance->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $updateAttendance->is_completed = 1;
                }
                $updateAttendance->shift_id = $request->shift_ids[$key];
                $updateAttendance->clock_in_note = $request->clock_in_notes[$key];
                $updateAttendance->clock_out_note = $request->clock_out_notes[$key];
                $updateAttendance->save();
            } else {
                $data = new Attendance();
                $data->user_id = $user_id;
                $data->at_date = date('d-m-Y');
                $data->at_date_ts = date('Y-m-d');
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = date('Y-m-d ') . $request->clock_ins[$key];
                $data->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $data->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $data->is_completed = 1;
                }
                $data->clock_in_note = $request->clock_in_notes[$key];
                $data->clock_out_note = $request->clock_out_notes[$key];
                $data->month = date('F');
                $data->year = date('Y');
                $data->save();
            }
        }
        return response()->json('Attendance Added Successfully!');
    }

    // Edit modal with data
    public function edit($attendanceId)
    {
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->where('hrm_attendances.id', $attendanceId)
            ->select(
                'hrm_attendances.*',
                'admin_and_users.id as user_id',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name'
            )
            ->first();
        return view('hrm.attendance.ajax_view.edit_attendance_modal', compact('attendance'));
    }

    // Update attendance
    public function update(Request $request)
    {
        $updateAttendance = Attendance::where('id', $request->id)->first();
        if ($updateAttendance) {
            $updateAttendance->at_date_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)).$request->clock_in;
            $updateAttendance->clock_in = $request->clock_in;
            $updateAttendance->clock_in_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)).$request->clock_in;

            if ($request->clock_out) {
                if ($updateAttendance->clock_out) {
                    $updateAttendance->clock_out = $request->clock_out;
                    $filteredDate = explode(' ', $updateAttendance->clock_out_ts);
                    $updateAttendance->clock_out_ts = $filteredDate[0].' '.$request->clock_out;
                }else {
                    $updateAttendance->clock_out = $request->clock_out;
                    $updateAttendance->clock_out_ts = date('Y-m-d ').$request->clock_out;
                    $updateAttendance->is_completed = 1;
                }
            }

            $updateAttendance->clock_in_note = $request->clock_in_note;
            $updateAttendance->clock_out_note = $request->clock_out_note;
            $updateAttendance->save();
        }

        return response()->json('Attendances updated successfully!');
    }

    // Delete attendance
    public function delete(Request $request, $attendanceId)
    {
        $deleteAttendance = Attendance::find($attendanceId);
        if (!is_null($deleteAttendance)) {
            $deleteAttendance->delete();
        }
        return response()->json('Attendance deleted successfully');
    }

    // Get Employee/User attendance row **requested by ajax**
    public function getUserAttendanceRow($userId)
    {
        // $startTime = Carbon::parse('2020-02-11 04:04:26');
        // $endTime = Carbon::parse('2020-02-11 04:36:56');

        // $totalDuration = $endTime->diffForHumans($startTime);
        // dd($totalDuration);

        // $startTime = Carbon::parse('2020-02-11 04:04:26');
        // $endTime = Carbon::parse('2020-02-11 04:36:56');

        // $totalDuration =  $startTime->diff($endTime)->format('%H:%I:%S')." Minutes";
        // dd($totalDuration);

        $shifts = DB::table('hrm_shifts')->get();
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
            ->where('hrm_attendances.user_id', $userId)
            ->where('is_completed', 0)
            ->select(
                'hrm_attendances.*',
                'admin_and_users.id as user_id',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            )
            ->orderBy('hrm_attendances.id', 'desc')
            ->first();
        $employee = DB::table('admin_and_users')->where('id', $userId)->first();
        return view('hrm.attendance.ajax_view.attendance_row', compact('attendance', 'shifts', 'employee'));
    }
}
