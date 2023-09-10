<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use App\Models\General_setting;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->manufacturing['manuf_settings'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('manufacturing.settings.index');
    }

    // Add tax settings
    public function store(Request $request)
    {
        if (auth()->user()->permission->manufacturing['manuf_settings'] == '0') {
            return response()->json('Access Denied');
        }

        $updateTaxSettings = General_setting::first();
        $mfSettings = [
            'production_ref_prefix' => $request->production_ref_prefix,
            'enable_editing_ingredient_qty' => isset($request->enable_editing_ingredient_qty) ? 1 : 0,
            'enable_updating_product_price' => isset($request->enable_updating_product_price) ? 1 : 0,
        ];

        $updateTaxSettings->mf_settings = json_encode($mfSettings);
        $updateTaxSettings->save();

            return response()->json(__('Manufacturing settings updated successfully'));


    }
}
