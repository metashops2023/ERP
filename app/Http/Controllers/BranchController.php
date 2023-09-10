<?php

namespace App\Http\Controllers;

use App\Utils\BranchUtil;
use App\Models\Branch;
use App\Models\Account;
use App\Models\AdminAndUser;
use App\Models\AdminUserBranch;
use Illuminate\Http\Request;
use App\Models\InvoiceSchema;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    protected $branchUtil;

    public function __construct(BranchUtil $branchUtil)
    {
        $this->branchUtil = $branchUtil;

        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {

            abort(403, 'Access Forbidden.');
        }

        if (auth()->user()->permission->setup['branch'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {


                    $branches = DB::table('branches')
                        ->orderBy('id', 'DESC');


            // $branches = DB::table('branches')->orderBy('id', 'DESC')->get();

            return DataTables::of($branches)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('settings.branches.edit', [$row->id]) . '" class="action-btn c-edit edit"><span class="fas fa-edit"></span></a>';
                    // $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    if ($row->status == 1) {
                        $html .= '<a class="table-dropdown" title="'.__("Cancel").'" id="change_status" href="' . route('settings.branches.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i></a>';
                    } else {
                        $html .= '<a class="table-dropdown" title="'.__("Undo").'" id="change_status" href="' . route('settings.branches.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i></a>';
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


        return view('settings.branches.index');
    }

    public function getAllBranch()
    {
        $addons = DB::table('addons')->select('branches')->first();

        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

        $branches = '';
        if (auth()->user()->role_type == 1) {
            $branches = Branch::all();
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select('branch_id')->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = Branch::whereIn('id', $branchIds)->get();
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get();
        }

        return view('settings.branches.ajax_view.branch_list', compact('branches'));
    }

    public function create()
    {
        $invSchemas = DB::table('invoice_schemas')->select('id', 'name')->get();
        $invLayouts = DB::table('invoice_layouts')->select('id', 'name')->get();

        $roles = DB::table('roles')->select('id', 'name')->get();

        return view('settings.branches.ajax_view.create', compact('invSchemas', 'invLayouts', 'roles'));
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('branches', 'branch_limit')->first();

        $branch_limit = $addons->branch_limit;

        if ($addons->branches == 0) {

            abort(403, 'Access Forbidden.');
        }

        $branchCount = DB::table('branches')->count();

        if ($branch_limit <= $branchCount && $branch_limit != '0') {

            return response()->json(["errorMsg" => "Business Location limit is ${branch_limit}"]);
        }

        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
            'invoice_schema_id' => 'required',
            'pos_sale_invoice_layout_id' => 'required',
            'add_sale_invoice_layout_id' => 'required',
        ]);

        if ($request->add_opening_user) {
            $this->validate($request, [
                'first_name' => 'required',
                'user_phone' => 'required',
                'role_id' => 'required',
                'username' => 'required|unique:admin_and_users,username',
                'password' => 'required|confirmed',
            ]);
        }

        $branchLogoName = '';
        if ($request->hasFile('logo')) {
            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
        }

        $addBranchGetId = Branch::insertGetId([
            'name' => $request->name,
            'branch_code' => $request->code,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'alternate_phone_number' => $request->alternate_phone_number,
            'email' => $request->email,
            'website' => $request->website,
            'purchase_permission' => $request->purchase_permission ? $request->purchase_permission : 0,
            'invoice_schema_id' => $request->invoice_schema_id,
            'add_sale_invoice_layout_id' => $request->add_sale_invoice_layout_id,
            'pos_sale_invoice_layout_id' => $request->pos_sale_invoice_layout_id,
            'logo' => $branchLogoName ? $branchLogoName : 'default.png',
        ]);
        // 43 stands for Vendor
        if (auth()->user()->role_type == 2 && auth()->user()->role_id == 43) {
            $auBranch = new AdminUserBranch();
            $auBranch->admin_user_id = auth()->user()->id;
            $auBranch->branch_id = $addBranchGetId;
            $auBranch->save();
        }

        $this->branchUtil->addBranchDefaultAccounts($addBranchGetId);

        $this->branchUtil->addBranchDefaultCashCounter($addBranchGetId);

        if ($request->add_opening_user) {

            $this->branchUtil->addBranchOpeningUser($request, $addBranchGetId);
        }


            return response()->json(__('Business Location created successfully'));


    }

    public function edit($branchId)
    {
        $branch = DB::table('branches')->where('id', $branchId)->first();
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        $invSchemas = DB::table('invoice_schemas')->select('id', 'name')->get();
        $invLayouts = DB::table('invoice_layouts')->select('id', 'name')->get();
        return view('settings.branches.ajax_view.edit', compact('branch', 'accounts', 'invSchemas', 'invLayouts'));
    }

    public function update(Request $request, $branchId)
    {
        $addons = DB::table('addons')->select('branches')->first();
        if ($addons->branches == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'logo' => 'sometimes|image|max:2048',
        ]);

        $updateBranch = Branch::where('id', $branchId)->first();
        $updateBranch->name = $request->name;
        $updateBranch->branch_code = $request->code;
        $updateBranch->phone = $request->phone;
        $updateBranch->city = $request->city;
        $updateBranch->state = $request->state;
        $updateBranch->zip_code = $request->zip_code;
        $updateBranch->country = $request->country;
        $updateBranch->alternate_phone_number = $request->alternate_phone_number;
        $updateBranch->email = $request->email;
        $updateBranch->website = $request->website;
        $updateBranch->purchase_permission = $request->purchase_permission ? $request->purchase_permission : 0;
        $updateBranch->invoice_schema_id = $request->invoice_schema_id;
        $updateBranch->add_sale_invoice_layout_id = $request->add_sale_invoice_layout_id;
        $updateBranch->pos_sale_invoice_layout_id = $request->pos_sale_invoice_layout_id;
        $updateBranch->default_account_id = $request->default_account_id;

        if ($request->hasFile('logo')) {
            if ($updateBranch->logo != 'default.png') {
                if (file_exists(public_path('uploads/branch_logo/' . $updateBranch->logo))) {
                    unlink(public_path('uploads/branch_logo/' . $updateBranch->logo));
                }
            }

            $branchLogo = $request->file('logo');
            $branchLogoName = uniqid() . '-' . '.' . $branchLogo->getClientOriginalExtension();
            $branchLogo->move(public_path('uploads/branch_logo/'), $branchLogoName);
            $updateBranch->logo = $branchLogoName;
        }

        $updateBranch->save();
        return response()->json(__('Business Location updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        $addons = DB::table('addons')->select('branches')->first();

        if ($addons->branches == 0) {

            abort(403, 'Access Forbidden.');
        }

        $deleteBranch = Branch::with(['sales', 'purchases'])->where('id', $id)->first();

        if (count($deleteBranch->sales) > 0) {
            return response()->json('Can not delete this business location. This location has one or more sales.');
        }

        if (count($deleteBranch->purchases) > 0) {

            return response()->json('Can not delete this business location. This location has one or more purchases.');
        }

        if ($deleteBranch->logo != 'default.png') {

            if (file_exists(public_path('uploads/branch_logo/' . $deleteBranch->logo))) {

                unlink(public_path('uploads/branch_logo/' . $deleteBranch->logo));
            }
        }

        $deleteBranch->delete();
        $mBranch = AdminUserBranch::where('branch_id', $id)->delete();


            return response()->json(__('Business location deleted successfully'));

    }

    public function changeStatus($id)
    {
        $statusChange = Branch::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Branch is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Branch is activated Successfully'));

        }
    }

    public function getAllAccounts()
    {
        $accounts = DB::table('accounts')->select('id', 'name', 'account_number')->get();
        return response()->json($accounts);
    }

    public function quickInvoiceSchemaModal()
    {
        return view('settings.branches.ajax_view.add_quick_invoice_schema');
    }

    public function quickInvoiceSchemaStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:invoice_schemas,name',
            'prefix' => 'required',
        ]);

        $addSchema = new InvoiceSchema();
        $addSchema->name = $request->name;
        $addSchema->format = $request->format;
        $addSchema->prefix = $request->prefix;
        $addSchema->start_from = $request->start_from;
        $addSchema->save();

        $invoiceSchemas = DB::table('invoice_schemas')->get();
        if (count($invoiceSchemas) == 1) {
            $defaultSchema = InvoiceSchema::first();
            $defaultSchema->is_default = 1;
            $defaultSchema->save();
        }

        return response()->json($addSchema);
    }
}
