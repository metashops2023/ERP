<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;
use App\Models\AdminUserBranch;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class CustomerImportController extends Controller
{
    public function create()
    {
        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }
        return view('contacts.import_customer.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
            'add_branch_id' => 'required',
        ]);

        Excel::import(new CustomerImport($request), $request->import_file);
        return redirect()->back();
    }
}
