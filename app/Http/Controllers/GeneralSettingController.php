<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Unit;
use App\Models\Month;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\General_setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class GeneralSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->setup['g_settings'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $bussinessSettings = General_setting::first();

        $months = Month::select(['id', 'month'])->get();
        $currencies = Currency::all();
        $units = Unit::all();
        $timezones = DB::table('timezones')->get();

        return view('settings.general_settings.index', compact(
            'bussinessSettings',
            'months',
            'currencies',
            'timezones',
        ));
    }

    // Add business settings
    public function businessSettings(Request $request)
    {
        $updateBusinessSettings = General_setting::first();
        $business_logo = null;

        if ($request->hasFile('business_logo')) {

            if (json_decode($updateBusinessSettings->business, true)['business_logo'] != null) {

                $bLogo = json_decode($updateBusinessSettings->business, true)['business_logo'];
                if (file_exists(public_path('uploads/business_logo/' . $bLogo))) {

                    unlink(public_path('uploads/business_logo/' . $bLogo));
                }
            }

            $logo = $request->file('business_logo');
            $logoName = uniqid() . '-' . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/business_logo/'), $logoName);
            $business_logo = $logoName;
        } else {

            $business_logo = json_decode($updateBusinessSettings->business, true)['business_logo'] != null ? json_decode($updateBusinessSettings->business, true)['business_logo'] : null;
        }

        $businessSettings = [
            'shop_name' => $request->shop_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'default_profit' => $request->default_profit ? $request->default_profit : 0,
            'currency' => $request->currency,
            'currency_placement' => $request->currency_placement,
            'date_format' => $request->date_format,
            'stock_accounting_method' => $request->stock_accounting_method,
            'time_format' => $request->time_format,
            'business_logo' => $business_logo,
            'timezone' => $request->timezone,
        ];

        $updateBusinessSettings->business = json_encode($businessSettings);
        $updateBusinessSettings->save();
        return response()->json('Business settings updated successfully');
    }

    // Add tax settings
    public function taxSettings(Request $request)
    {
        $updateTaxSettings = General_setting::first();
        $taxSettings = [
            'tax_1_name' => $request->tax_1_name,
            'tax_1_no' => $request->tax_1_no,
            'tax_2_name' => $request->tax_2_name,
            'tax_2_no' => $request->tax_2_no,
            'is_tax_en_purchase_sale' => isset($request->is_tax_en_purchase_sale) ? 1 : 0,
        ];

        $updateTaxSettings->tax = json_encode($taxSettings);
        $updateTaxSettings->save();
        return response()->json('Tax settings updated successfully');
    }

    public function dashboardSettings(Request $request)
    {
        $updateDashboardSettings = General_setting::first();
        $dashboardSettings = [
            'view_stock_expiry_alert_for' => $request->view_stock_expiry_alert_for,
        ];

        $updateDashboardSettings->dashboard = json_encode($dashboardSettings);
        $updateDashboardSettings->save();
        return response()->json('Dashboard settings updated successfully.');
    }

    public function prefixSettings(Request $request)
    {
        $updatePrefixSettings = General_setting::first();
        $prefixSettings = [
            'purchase_invoice' => $request->purchase_invoice,
            'sale_invoice' => $request->sale_invoice,
            'purchase_return' => $request->purchase_return,
            'stock_transfer' => $request->stock_transfer,
            'stock_djustment' => $request->stock_djustment,
            'sale_return' => $request->sale_return,
            'expenses' => $request->expenses,
            'supplier_id' => $request->supplier_id,
            'customer_id' => $request->customer_id,
            'purchase_payment' => $request->purchase_payment,
            'sale_payment' => $request->sale_payment,
            'expense_payment' => $request->expense_payment,
        ];

        $updatePrefixSettings->prefix = json_encode($prefixSettings);
        $updatePrefixSettings->save();
        return response()->json('Prefix settings updated Successfully');
    }

    public function systemSettings(Request $request)
    {
        $updateSystemSettings = General_setting::first();
        $SystemSettings = [
            'theme_color' => $request->theme_color,
            'datatable_page_entry' => $request->datatable_page_entry,
        ];

        $updateSystemSettings->system = json_encode($SystemSettings);
        $updateSystemSettings->save();
        return response()->json('System settings updated Successfully.');
    }

    public function moduleSettings(Request $request)
    {
        $updateModuleSettings = General_setting::first();
        $moduleSettings = [
            'purchases' => isset($request->purchases) ? 1 : 0,
            'add_sale' => isset($request->add_sale) ? 1 : 0,
            'pos' => isset($request->pos) ? 1 : 0,
            'transfer_stock' => isset($request->transfer_stock) ? 1 : 0,
            'stock_adjustment' => isset($request->stock_adjustment) ? 1 : 0,
            'expenses' => isset($request->expenses) ? 1 : 0,
            'accounting' => isset($request->accounting) ? 1 : 0,
            'contacts' => isset($request->contacts) ? 1 : 0,
            'hrms' => isset($request->hrms) ? 1 : 0,
            'requisite' => isset($request->requisite) ? 1 : 0,
            'manufacturing' => isset($request->manufacturing) ? 1 : 0,
            'service' => isset($request->service) ? 1 : 0,
        ];

        $updateModuleSettings->modules = json_encode($moduleSettings);
        $updateModuleSettings->save();
        return response()->json('modules settings updated successfully');
    }

    public function SendEmailSmsSettings(Request $request)
    {
        $updateEmailSmsSettings = General_setting::first();
        $moduleSettings = [
            'send_inv_via_email' => isset($request->send_inv_via_email) ? 1 : 0,
            'send_notice_via_sms' => isset($request->send_notice_via_sms) ? 1 : 0,
            'cmr_due_rmdr_via_email' => isset($request->cmr_due_rmdr_via_email) ? 1 : 0,
            'cmr_due_rmdr_via_sms' => isset($request->cmr_due_rmdr_via_sms) ? 1 : 0,
        ];

        $updateEmailSmsSettings->send_es_settings = json_encode($moduleSettings);
        $updateEmailSmsSettings->save();
        return response()->json('Send Email & SMS settings updated successfully');
    }

    public function rewardPoingSettings(Request $request)
    {
        $updateRewardPointgSettings = General_setting::first();
        $RewardPointgSettings = [
            'enable_cus_point' => isset($request->enable_cus_point) ? 1 : 0,
            'point_display_name' => $request->point_display_name ? $request->point_display_name : 0,
            'amount_for_unit_rp' => $request->amount_for_unit_rp ? $request->amount_for_unit_rp : 0,
            'min_order_total_for_rp' => $request->min_order_total_for_rp ? $request->min_order_total_for_rp : 0,
            'max_rp_per_order' => $request->max_rp_per_order ? $request->max_rp_per_order : '',
            'redeem_amount_per_unit_rp' => $request->redeem_amount_per_unit_rp ? $request->redeem_amount_per_unit_rp : 0,
            'min_order_total_for_redeem' => $request->min_order_total_for_redeem ? $request->min_order_total_for_redeem : '',
            'min_redeem_point' => $request->min_redeem_point ? $request->min_redeem_point : '',
            'max_redeem_point' => $request->max_redeem_point ? $request->max_redeem_point : '',
        ];

        $updateRewardPointgSettings->reward_poing_settings = json_encode($RewardPointgSettings);
        $updateRewardPointgSettings->save();
        return response()->json('Reward point settings updated Successfully');
    }
}
