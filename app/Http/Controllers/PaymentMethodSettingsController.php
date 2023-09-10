<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethodSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'banks.name as b_name']);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('settings.payment_settings.index', compact('accounts', 'methods'));
    }

    public function update(Request $request)
    {
        if (isset($request->method_ids)) {

            $index = 0;
            foreach ($request->method_ids as $method_id) {

                $updateSetting = PaymentMethodSetting::where('branch_id', auth()->user()->branch_id)
                    ->where('payment_method_id', $method_id)->first();

                if (!$updateSetting) {

                    $add = new PaymentMethodSetting();
                    $add->payment_method_id = $method_id;
                    $add->account_id = $request->account_ids[$index];
                    $add->branch_id = auth()->user()->branch_id;
                    $add->save();
                } else {

                    $updateSetting->payment_method_id = $method_id;
                    $updateSetting->account_id = $request->account_ids[$index];
                    $updateSetting->branch_id = auth()->user()->branch_id;
                    $updateSetting->save();
                }

                $index++;
            }
        } else {

            return response()->json(['errorMsg' => 'Failed! Payment method is empty.']);
        }

        return response()->json('Successfully Payment method settings is updated.');
    }
}
