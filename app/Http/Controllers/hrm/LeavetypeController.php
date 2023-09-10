<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Leavetype;
use Illuminate\Support\Facades\Cache;

class LeavetypeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //show Leavetype page only
    public function index()
    {
    	return view('hrm.leavetype.index');
    }

    //all data pass on table
    public function allLeavType()
    {
    	$leavetype = Leavetype::orderBy('id','DESC')->get();
        return view('hrm.leavetype.ajax.type_list',compact('leavetype'));
    }

    //store leave type method
    public function storeLeavetype(Request $request)
    {
    	$this->validate($request, [
            'leave_type' => 'required',
        ]);
        Leavetype::insert([
                'leave_type' => $request->leave_type,
                'max_leave_count' => $request->max_leave_count,
                'leave_count_interval' => $request->leave_count_interval,
        ]);
        return response()->json('Leave type created successfully');
    }

    //Leavetype update method
    public function updateLeaveType(Request $request)
    {
    	$this->validate($request, [
            'leave_type' => 'required',
        ]);

        $Leavetype = Leavetype::where('id', $request->id)->first();
            $Leavetype->update([
                'leave_type' => $request->leave_type,
                'max_leave_count' => $request->max_leave_count,
                'leave_count_interval' => $request->leave_count_interval,
            ]);
        return response()->json('Leave type updated successfully');
    }

    //destroy leave type
    public function deleteLeaveType(Request $request,$id)
    {
    	$deleteCategory = Leavetype::find($id);
        $deleteCategory->delete();
        return response()->json('Leave type deleted successfully');
    }

}
