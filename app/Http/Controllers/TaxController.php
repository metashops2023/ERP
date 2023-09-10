<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->setup['tax'] == '0') {
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
                    $taxes = DB::table('taxes')
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $taxes = DB::table('taxes')
                        ->orderBy('id', 'DESC');
                }
            } else if (auth()->user()->role_type == 2) {
                if (isset($request->branch_id) && $request->branch_id != null) {
                    $taxes = DB::table('taxes')
                        ->where('admin_user_id', auth()->user()->id)
                        ->where('branch_id', $request->branch_id)
                        ->orderBy('id', 'DESC');
                } else {
                    $taxes = DB::table('taxes')
                        ->where('admin_user_id', auth()->user()->id)
                        ->orderBy('id', 'DESC');
                }
            } else {
                $taxes = DB::table('taxes')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'DESC');
            }

            // $taxes = DB::table('taxes')->orderBy('id', 'DESC')->get();
            // $taxes = $taxes->where('status',1)->get();
            return DataTables::of($taxes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.taxes.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('settings.taxes.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('settings.taxes.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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

        return view('settings.taxes.index', compact('branches'));
    }

    public function getAllVat()
    {
        if (auth()->user()->role_type == 1) {
            $taxes = Tax::all();
        } else if (auth()->user()->role_type == 2) {
            $taxes = DB::table('taxes')
                ->where('admin_user_id', auth()->user()->id)
                ->orderBy('id', 'DESC')->get();
        } else {
            $taxes = DB::table('taxes')
                ->where('branch_id', auth()->user()->branch_id)
                ->orderBy('id', 'DESC')->get();
        }
        // $taxes = Tax::all();
        return view('settings.taxes.ajax_view.tax_list', compact('taxes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tax_name' => 'required',
            'tax_percent' => 'required',
            'add_branch_id' => 'required',
        ]);
        $branchUser = getBranchUser($request->add_branch_id);

        $addTax = new Tax();
        $addTax->tax_name = $request->tax_name;
        $addTax->tax_percent = $request->tax_percent;
        $addTax->admin_user_id = $branchUser->id;
        $addTax->branch_id = $request->add_branch_id;
        $addTax->save();

            return response()->json(__('Tax added successfully'));


    }

    public function edit($id)
    {
        $data = DB::table('taxes')->where('id', $id)->first();

        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('settings.taxes.ajax_view.edit', compact('data', 'branches'));
    }


    public function update(Request $request)
    {
        $this->validate($request, [
            'tax_name' => 'required',
            'tax_percent' => 'required',
            'update_branch_id' => 'required',
        ]);
        $branchUser = getBranchUser($request->update_branch_id);

        $updateTax = Tax::where('id', $request->id)->first();
        $updateTax->tax_name = $request->tax_name;
        $updateTax->tax_percent = $request->tax_percent;
        $updateTax->admin_user_id = $branchUser->id;
        $updateTax->branch_id = $request->update_branch_id;
        $updateTax->save();
        return response()->json(__('Tax updated successfully'));
    }

    public function changeStatus($taxId)
    {
        $statusChange = Tax::where('id', $taxId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Tax is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Tax is activated Successfully'));

        }
    }


    public function delete(Request $request, $taxId)
    {
        $deleteVat = Tax::where('id', $taxId)->first();
        if (!is_null($deleteVat)) {
            $deleteVat->delete();
        }
        return response()->json(__('Tax deleted successfully'));
    }
}
