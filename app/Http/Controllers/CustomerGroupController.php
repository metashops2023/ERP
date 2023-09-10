<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Customer main page/index page
    // public function index()
    // {
    //     $branches = getUserBranches();
    //     return view('contacts.customer_group.index', compact('branches'));
    // }

    // Get all customer group by ajax
    // public function allBanks()
    // {
    //     $branches = getUserBranches();

    //     if (auth()->user()->role_type == 1) {
    //         $groups = CustomerGroup::orderBy('id', 'DESC')->get();
    //     } else if (auth()->user()->role_type == 2) {
    //         $groups = CustomerGroup::where('admin_user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
    //     } else {
    //         $groups = CustomerGroup::where('branch_id', auth()->user()->branch_id)->orderBy('id', 'DESC')->get();
    //     }
    //     // $groups = CustomerGroup::orderBy('id', 'DESC')->get();
    //     return view('contacts.customer_group.ajax_view.group_list', compact('groups', 'branches'));
    // }


    public function index(Request $request)
    {
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
                    $groups = DB::table('customer_groups')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $groups = DB::table('customer_groups')
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $groups = DB::table('customer_groups')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $groups = DB::table('customer_groups')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $groups = DB::table('customer_groups')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $units = DB::table('units')->orderBy('id', 'DESC')->get();
            // $units = $units->where('status',1)->get();
            return DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('customers.group.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->active == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('customers.group.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('customers.group.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('active', function ($row) {

               if ($row->active == 1) {

                   return '<span class="text-success">Active</span>';
               } else {

                   return '<span class="text-danger">Inactive</span>';
               }
           })
           ->filter(function($query) use($request){
            // dd($request->active);
            if($request->active=="false"){
                $query->where('active',1);
            }else{
            $query->where('active',0);
        }
           })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }


        return view('contacts.customer_group.index', compact('branches'));
    }
    // Store customer group
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'add_branch_id' => 'required',
        ]);

        $branchUser = getBranchUser($request->add_branch_id);

        CustomerGroup::insert([
            'group_name' => $request->name,
            'admin_user_id' => $branchUser->id,
            'branch_id' => $request->add_branch_id,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);

            return response()->json(__('Customer group created successfully'));


    }


    public function edit($id)
    {
        $data = DB::table('customer_groups')->where('id', $id)->first();

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('contacts.customer_group.ajax_view.edit', compact('data', 'branches'));
    }

    // Update customer group
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateBank = CustomerGroup::where('id', $request->id)->first();
        $updateBank->update([
            'group_name' => $request->name,
            'calc_percentage' => $request->calculation_percent ? $request->calculation_percent : 0.00,
        ]);
        return response()->json(__('Customer group updated successfully'));
    }

    public function changeStatus($id)
    {
        $statusChange = CustomerGroup::where('id', $id)->first();
        if ($statusChange->active == 1) {

            $statusChange->active = 0;
            $statusChange->save();

            return response()->json(__('Customer Group is deactivated Successfully'));

        } else {

            $statusChange->active = 1;
            $statusChange->save();
            return response()->json(__('Customer Group is activated Successfully'));

        }
    }

    // delete customer group
    public function delete(Request $request, $groupId)
    {
        $deleteCustomerGroup = CustomerGroup::find($groupId);
        if (!is_null($deleteCustomerGroup)) {
            $deleteCustomerGroup->delete();
        }
        return response()->json(__('Customer group deleted successfully'));
    }
}
