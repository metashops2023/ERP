<?php

namespace App\Http\Controllers\hrm;

use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\Hrm\Allowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Hrm\AllowanceEmployee;

class AllowanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //index methods allwoance get page
    public function index()
    {
        $employee = AdminAndUser::where('status', 1)->get();
        return view('hrm.allowance.index', compact('employee'));
    }

    //get data for table ajax request
    public function allallowance()
    {
        $allowance = Allowance::with(['allowance_employees', 'allowance_employees.employee'])->orderBy('id', 'DESC')->get();
        return view('hrm.allowance.ajax.list', compact('allowance'));
    }

    //store allowance
    public function storeallowance(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|unique:hrm_allowance,description',
            'amount' => 'required',
        ]);

        $addAllowance = Allowance::insertGetId([
            'description' => $request->description,
            'type' => $request->type,
            'amount_type' => $request->amount_type,
            'amount' => $request->amount,
            'applicable_date' => $request->applicable_date,
        ]);

        return response()->json('Successfully Allowance is Added!');
    }

    //Edit allowance
    public function edit($alowanceId)
    {
        $employees = DB::table('admin_and_users')->where('status', 1)->get();
        $allowance = Allowance::with('allowance_employees')->where('id', $alowanceId)->first();
        return view('hrm.allowance.ajax.edit_modal_form', compact('allowance', 'employees'));
    }

    //get all employee for edit form
    public function GetEmployee()
    {
        $admins = DB::table('admin_and_users')->where('status', 1)->get();
        return response()->json($admins);
    }

    //update allowance
    public function updateallowance(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|unique:hrm_allowance,description,'.$request->id,
            'amount' => 'required',
        ]);

        $allowance = Allowance::where('id', $request->id)->first();

        $allowance->update([
            'description' => $request->description,
            'type' => $request->type,
            'amount_type' => $request->amount_type,
            'amount' => $request->amount,
        ]);

        return response()->json('Successfully Allowance is Updated!');
    }

    //delete allowance
    public function deleteAllowance(Request $request, $id)
    {
        $deleteAllowance = Allowance::find($id);
        $deleteAllowance->delete();
        return response()->json('Successfully Allowance is Deleted');
    }
}
