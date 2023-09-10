<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Bank main page/index page
    public function index()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        return view('accounting.banks.index');
    }

    // Get all banks by ajax
    public function allBanks()
    {
        $banks = Bank::orderBy('id', 'DESC')->get();
        return view('accounting.banks.ajax_view.bank_list', compact('banks'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_name' => 'required',
        ]);

        $addBank = Bank::create([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 16,
            data_obj: $addBank
        );

            return response()->json(__('Bank created successfully'));

    }

    // Update bank
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'branch_name' => 'required',
        ]);

        $updateBank = Bank::where('id', $request->id)->first();
        $updateBank->update([
            'name' => $request->name,
            'branch_name' => $request->branch_name,
            'address' => $request->address,
        ]);

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 16,
            data_obj: $updateBank
        );

        return response()->json(__('Bank updated successfully'));
    }

    public function delete(Request $request, $bankId)
    {
        // return response()->json('Feature is disabled in this demo');

        $deleteBank = Bank::find($bankId);

        if (!is_null($deleteBank)) {

            if(count($deleteBank->accounts) > 0) {


                return response()->json(__('Can not be deleted, This bank has one or more account.'));


            }

            $this->userActivityLogUtil->addLog(
                action: 3,
                subject_type: 16,
                data_obj: $deleteBank
            );

            $deleteBank->delete();
        }

        return response()->json(__('Bank deleted successfully'));
    }
}
