<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Models\Warranty;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarrantyController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }
    // Warranty main page/index page
    // public function index()
    // {
    //     if (auth()->user()->permission->product['warranties'] == '0') {

    //         abort(403, 'Access Forbidden.');
    //     }

    //     if (auth()->user()->role_type == 1) {
    //         $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
    //     } else if (auth()->user()->role_type == 2) {
    //         $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
    //         $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
    //     } else {
    //         $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
    //     }

    //     return view('product.warranties.index', compact('branches'));
    // }

    // // Get all warranty by ajax
    // public function allWarranty()
    // {
    //     if (auth()->user()->role_type == 1) {
    //         $warranties = Warranty::orderBy('id', 'DESC')->get();
    //     } else if (auth()->user()->role_type == 2) {
    //         $warranties = DB::table('warranties')
    //             ->where('admin_user_id', auth()->user()->id)
    //             ->orderBy('id', 'DESC')->get();
    //     } else {
    //         $warranties = DB::table('warranties')
    //             ->where('branch_id', auth()->user()->branch_id)
    //             ->orderBy('id', 'DESC')->get();
    //     }
    //     // $warranties = Warranty::orderBy('id', 'DESC')->get();
    //     return view('product.warranties.ajax_view.warranty_list', compact('warranties'));
    // }


     public function index(Request $request)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {

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
                    $warranties = DB::table('warranties')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $warranties = DB::table('warranties')
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $warranties = DB::table('warranties')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $warranties = DB::table('warranties')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $warranties = DB::table('warranties')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $units = DB::table('units')->orderBy('id', 'DESC')->get();
            // $units = $units->where('status',1)->get();
            return DataTables::of($warranties)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.warranties.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('product.warranties.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('product.warranties.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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


        return view('product.warranties.index', compact('branches'));
    }

    // Store warranty
    public function store(Request $request)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
            'add_branch_id' => 'required',
        ]);
        $branchUser = getBranchUser($request->add_branch_id);
        $addWarranty = Warranty::create([
            'admin_user_id' => $branchUser->id,
            'branch_id' => $request->add_branch_id,
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        if ($addWarranty) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 25, data_obj: $addWarranty);
        }

            return response()->json(__('Warranty is created Successfully'));

    }

    public function edit($id)
    {
        $data = DB::table('warranties')->where('id', $id)->first();

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('product.warranties.ajax_view.edit', compact('data', 'branches'));
    }

    // Update warranty
    public function update(Request $request)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {

            return response()->json('Access Denied');
        }

        $branchUser = getBranchUser($request->update_branch_id);

        $this->validate($request, [
            // 'admin_user_id' => $branchUser->id,
            // 'branch_id' => $request->update_branch_id,
            'name' => 'required',
            'duration' => 'required',
            'update_branch_id' => 'required',
        ]);

        $updateWarranty = Warranty::where('id', $request->id)->first();

        $updateWarranty->update([
            'admin_user_id' => $branchUser->id,
            'branch_id' => $request->update_branch_id,
            'name' => $request->name,
            'type' => $request->type,
            'duration' => $request->duration,
            'duration_type' => $request->duration_type,
            'description' => $request->description,
        ]);

        if ($updateWarranty) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 25, data_obj: $updateWarranty);
        }

        return response()->json(__('Warranty is updated Successfully'));
    }

    public function changeStatus($id)
    {
        $statusChange = Warranty::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Warranty is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Warranty is activated Successfully'));

        }
    }

    // Delete warranty
    public function delete(Request $request, $warrantyId)
    {
        if (auth()->user()->permission->product['warranties'] == '0') {

            return response()->json('Access Denied');
        }
        $deleteWarranty = Warranty::find($warrantyId);

        if (!is_null($deleteWarranty)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 25, data_obj: $deleteWarranty);

            $deleteWarranty->delete();
        }
        return response()->json(__('Warranty is deleted Successfully'));
    }
}
