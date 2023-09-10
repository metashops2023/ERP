<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Designation;
use Illuminate\Support\Facades\Cache;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //show designation page only
    public function index()
    {
        return view('hrm.designation.index');
    }

    //ajax request for all designation
    public function allDesignation()
    {
        $designation = Designation::orderBy('id', 'DESC')->get();
        return view('hrm.designation.ajax.designation_list', compact('designation'));
    }

    //designations store
    public function storeDesignation(Request $request)
    {
        $this->validate($request, [
            'designation_name' => 'required|unique:hrm_designations',
        ]);

        Designation::insert([
            'designation_name' => $request->designation_name,
            'description' => $request->description,
        ]);

            return response()->json(__('Successfully Designation Added!'));

    }

    //designations update
    public function updateDesignation(Request $request)
    {
        $this->validate($request, [
            'designation_name' => 'required',
        ]);
        $updateDesignation = Designation::where('id', $request->id)->first();
        $updateDesignation->update([
            'designation_name' => $request->designation_name,
            'description' => $request->description,
        ]);

        return response()->json(__('Successfully Designation updated!'));
    }

    //destroy designation
    public function deleteDesignation(Request $request, $designationId)
    {
        $deleteCategory = Designation::find($designationId);
        $deleteCategory->delete();
        // Cache::forget('all-categories');
        // Cache::forget('all-main_categories');
        // Cache::forget('all-products');

        return response()->json(__('Successfully Designation deleted!'));
    }
}
