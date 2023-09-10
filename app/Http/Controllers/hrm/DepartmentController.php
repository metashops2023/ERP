<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //department showing page method
    public function index()
    {
        return view('hrm.department.index');
    }

    //department ajax data show method
    public function allDepartment()
    {
        $department = Department::orderBy('id', 'DESC')->get();
        return view('hrm.department.ajax.department_list', compact('department'));
    }

    //store department method
    public function storeDepartment(Request $request)
    {
        $this->validate($request, [
            'department_name' => 'required',
            'department_id' => 'required|unique:hrm_department',
        ]);

        Department::insert([
            'department_name' => $request->department_name,
            'department_id' => $request->department_id,
            'description' => $request->description,
        ]);

        return response()->json('Successfully Department Added!');
    }

    //update departments method
    public function updateDepartments(Request $request)
    {
        $this->validate($request, [
            'department_name' => 'required',
            'department_id' => 'required',
        ]);
        
        $updateDepartment = Department::where('id', $request->id)->first();
        $updateDepartment->update([
            'department_name' => $request->department_name,
            'department_id' => $request->department_id,
            'description' => $request->description,
        ]);
        return response()->json('Successfully Department Updated!');
    }

    //destroy single department
    public function deleteDepartment($departmentId)
    {
        $deleteDepartment = Department::find($departmentId);
        $deleteDepartment->delete();
        return response()->json('Successfully Department Deleted');
    }
}
