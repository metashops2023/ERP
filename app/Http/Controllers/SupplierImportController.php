<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SupplierImport;
use App\Models\AdminUserBranch;
use App\Models\Branch;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SupplierImportController extends Controller
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
        return view('contacts.import_supplier.create', compact('branches'));
    }

    public function store(Request $request)
    {
        // C:\xampp81\htdocs\pos\public\imported\laravel-excel-9L2eurxBYvesmwUYHZRwFDeFAWeDycBN.36
        // C:\xampp81\htdocs\pos\storage\framework/laravel-excel\laravel-excel-eXBDPdfbbBLv5Hof4y34exfBbUBKUFsa.36
        // if (is_dir('C:\xampp81\htdocs\pos\storage\framework/laravel-excel')) {
        //     try {
        //         touch('C:\xampp81\htdocs\pos\storage\framework/laravel-excel\laravel-excel-eXBDPdfbbBLv5Hof4y34exfBbUBKUFsa.36');
        //     } catch (Exception $e) {
        //         echo '<pre>';
        //         print_r($e->getMessage());
        //         exit;
        //     }
        // }
        $this->validate($request, [
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
            'add_branch_id' => 'required'
        ]);

        Excel::import(new SupplierImport($request), $request->import_file);
        return redirect()->back();
    }
}
