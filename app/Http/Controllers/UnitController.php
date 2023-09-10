<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // public function index()
    // {
    //     if (auth()->user()->permission->product['units'] == '0') {

    //         abort(403, 'Access Forbidden.');
    //     }

    //     $branches = getUserBranches();
    //     return view('settings.units.index', compact('branches'));
    // }

    // public function getAllUnit()
    // {
    //     $units = getUserUnits();
    //     return view('settings.units.ajax_view.unit_list', compact('units'));
    // }

    public function index(Request $request)
    {
        if (auth()->user()->permission->product['units'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }


        if ($request->ajax()) {

            if (auth()->user()->role_type == 1) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $units = DB::table('units')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $units = DB::table('units')
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $units = DB::table('units')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $units = DB::table('units')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $units = DB::table('units')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $units = DB::table('units')->orderBy('id', 'DESC')->get();
            // $units = $units->where('status',1)->get();
            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.units.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('settings.units.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('settings.units.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('status', function ($row) {

               if ($row->status == 1) {

                   return '<span class="text-success">Active</span>';
               } else {

                   return '<span class="text-danger">Inactive</span>';
               }
           })
           ->filter(function($query) use($request){
            // dd($request->active);
            if($request->active=="false"){
                $query->where('status',1);
            }else{
            $query->where('status',0);
        }
           })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('settings.units.index', compact('branches'));
    }


    public function store(Request $request)
    {
        if (auth()->user()->permission->product['units'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required|unique:units,name',
            'code' => 'required|unique:units,code_name',
            'add_branch_id' => 'required',
        ]);

        $branchUser = getBranchUser($request->add_branch_id);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->admin_user_id = $branchUser->id;
        $addUnit->branch_id = $request->add_branch_id;
        $addUnit->save();

        if ($addUnit) {
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 23, data_obj: $addUnit);
        }

        return response()->json('Successfully Unit is added');
    }

    public function edit($id)
    {
        $data = DB::table('units')->where('id', $id)->first();

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('settings.units.ajax_view.edit', compact('data', 'branches'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->product['units'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required|unique:units,name,',
            'code' => 'required|unique:units,code_name,',
            // 'update_branch_id' => 'required',
        ]);
        $branchUser = getBranchUser($request->update_branch_id);
        
        $updateUnit = Unit::where('id', $request->id)->first();
        $updateUnit->name = $request->name;
        $updateUnit->code_name = $request->code;
        $updateUnit->admin_user_id = $branchUser->id;
        $updateUnit->branch_id = $request->update_branch_id;
        $updateUnit->save();

        if ($updateUnit) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 23, data_obj: $updateUnit);
        }

        return response()->json('Successfully unit is updated');
    }

    public function changeStatus($unitId)
    {
        $statusChange = Unit::where('id', $unitId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Unit is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Unit is activated Successfully'));

        }
    }

    public function delete(Request $request, $unitId)
    {
        if (auth()->user()->permission->product['units'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteUnit = Unit::where('id', $unitId)->first();

        if (!is_null($deleteUnit)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 23, data_obj: $deleteUnit);

            $deleteUnit->delete();
        }
        return response()->json('Successfully unit is deleted');
    }
}
