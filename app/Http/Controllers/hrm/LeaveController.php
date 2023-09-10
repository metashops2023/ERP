<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminAndUser;
use App\Models\Hrm\Leavetype;
use App\Models\Hrm\Leave;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //leave page method
    public function index()
    {
        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $leavetypes = DB::table('hrm_leavetypes')->get(['id', 'leave_type']);
        $employees = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        return view('hrm.leave.index', compact('departments', 'leavetypes', 'employees'));
    }

    //all leave data for ajax
    public function allLeave()
    {
        $leave = Leave::with(['admin_and_user', 'leave_type'])->orderBy('id', 'DESC')->get();
        return view('hrm.leave.ajax.list', compact('leave'));
    }

    //store leave
    public function storeLeave(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        Leave::insert([
            'reference_number' => hexdec(substr(uniqid(), -5)),
            'employee_id' => $request->employee_id,
            'leave_id' => $request->leave_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 0,
        ]);
        return response()->json('Leave created successfully');
    }

    //update leave
    public function updateLeave(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $leave = Leave::where('id', $request->id)->first();
        $leave->update([
            'employee_id' => $request->employee_id,
            'leave_id' => $request->leave_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);
        return response()->json('Leave Updated successfully');
    }

    //destroy leave
    public function deleteLeave(Request $request, $id)
    {
        $Leave = Leave::find($id);
        $Leave->delete();
        return response()->json('Leave Deleted successfully');
    }

    public function departmentEmployees($depId)
    {
        $employees = DB::table('admin_and_users')->where('department_id', $depId)
        ->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        return response()->json($employees);
    }
}
