<?php

namespace Database\Seeders;

use App\Models\AdminAndUser;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $general_settings = array(
            array(
                'id' => '1',
                'business' => '{"shop_name":"MetaShops","address":"MetaShops HQ","phone":"1234567890","email":"info@metashops.com.sa","start_date":"01-01-2023","default_profit":0,"currency":"\\ufdfc","currency_placement":null,"date_format":"d-m-Y","financial_year_start":"Januaray","time_format":"12","business_logo":"63c2e439376b5-.png","timezone":"Asia\\/Dhaka"}',
                'tax' => '{"tax_1_name":"Tax","stock_accounting_method":"1","tax_1_no":"1","tax_2_name":"GST","tax_2_no":"2","is_tax_en_purchase_sale":1}',
                'product' => '{"product_code_prefix":null,"default_unit_id":"3","is_enable_brands":1,"is_enable_categories":1,"is_enable_sub_categories":1,"is_enable_price_tax":1,"is_enable_warranty":1}',
                'sale' => '{"default_sale_discount":"0.00","default_tax_id":"null","sales_cmsn_agnt":"select_form_cmsn_list","default_price_group_id":"7"}',
                'pos' => '{"is_enabled_multiple_pay":1,"is_enabled_draft":1,"is_enabled_quotation":1,"is_enabled_suspend":1,"is_enabled_discount":1,"is_enabled_order_tax":1,"is_show_recent_transactions":1,"is_enabled_credit_full_sale":1,"is_enabled_hold_invoice":1}',
                'purchase' => '{"is_edit_pro_price":1,"is_enable_status":1,"is_enable_lot_no":1}',
                'dashboard' => '{"view_stock_expiry_alert_for":"31"}',
                'system' => '{"theme_color":"dark-theme","datatable_page_entry":"50"}',
                'prefix' => '{"purchase_invoice":"PI","sale_invoice":"SI","purchase_return":"PRI","stock_transfer":"STI","stock_djustment":"SAR","sale_return":"SRI","expenses":"EXI","supplier_id":"SID","customer_id":null,"purchase_payment":"PPI","sale_payment":"SPI","expense_payment":"EXPI"}',
                'send_es_settings' => '{"send_inv_via_email":0,"send_notice_via_sms":0,"cmr_due_rmdr_via_email":0,"cmr_due_rmdr_via_sms":0}',
                'email_setting' => '[]',
                'sms_setting' => '[]',
                'modules' => '{"purchases":1,"add_sale":1,"pos":1,"transfer_stock":1,"stock_adjustment":1,"expenses":1,"accounting":1,"contacts":1,"hrms":1,"requisite":1}',
                'reward_poing_settings' => '{"enable_cus_point":1,"point_display_name":"Reward Point","amount_for_unit_rp":"10","min_order_total_for_rp":"100","max_rp_per_order":"","redeem_amount_per_unit_rp":"0.10","min_order_total_for_redeem":"","min_redeem_point":"","max_redeem_point":""}',
                'multi_branches' => '0',
                'hrm' => '0',
                'services' => '0',
                // 'menufacturing' => '0',
                'projects' => '0',
                'essentials' => '0',
                'e_commerce' => '0',
                //'contact_default_cr_limit' => '50000000.00',
                'created_at' => NULL,
                'updated_at' => '2021-09-02 13:31:41'
            )
        );

        DB::table('general_settings')->insert($general_settings);

        $role_permissions  = array(
            array(
                'id' => 1,
                'user' => '{"user_view":1,"user_add":1,"user_edit":1,"user_delete":1,"role_view":1,"role_add":1,"role_edit":1,"role_delete":1}',
                'contact' => '{"supplier_all":1,"supplier_add":1,"supplier_import":1,"supplier_edit":1,"supplier_delete":1,"customer_all":1,"customer_add":1,"customer_import":1,"customer_edit":1,"customer_delete":1,"customer_group":1,"customer_report":1,"supplier_report":1}',
                'product' => '{"product_all":1,"product_add":1,"product_edit":1,"openingStock_add":1,"product_delete":1,"categories":1,"brand":1,"units":1,"variant":1,"warranties":1,"selling_price_group":1,"generate_barcode":1,"product_settings":1,"stock_report":1,"stock_in_out_report":1}',
                'purchase' => '{"purchase_all":1,"purchase_add":1,"purchase_edit":1,"purchase_delete":1,"purchase_payment":1,"purchase_return":1,"status_update":1,"purchase_settings":1,"purchase_statements":1,"purchase_sale_report":1,"pro_purchase_report":1,"purchase_payment_report":1}',
                's_adjust' => '{"adjustment_all":1,"adjustment_add_from_location":1,"adjustment_add_from_warehouse":1,"adjustment_delete":1,"stock_adjustment_report":1}',
                'expense' => '{"view_expense":1,"add_expense":1,"edit_expense":1,"delete_expense":1,"expense_category":1,"category_wise_expense":1,"expense_report":1}',
                'sale' => '{"pos_all":1,"pos_add":1,"pos_edit":1,"pos_delete":1,"pos_sale_settings":1,"create_add_sale":1,"view_add_sale":1,"edit_add_sale":1,"delete_add_sale":1,"add_sale_settings":1,"sale_draft":1,"sale_quotation":1,"sale_payment":1,"edit_price_sale_screen":1,"edit_price_pos_screen":1,"edit_discount_sale_screen":1,"edit_discount_pos_screen":1,"shipment_access":1,"view_product_cost_is_sale_screed":1,"view_own_sale":1,"return_access":1,"discounts":1,"sale_statements":1,"sale_return_statements":1,"pro_sale_report":1,"sale_payment_report":1,"c_register_report":1,"sale_representative_report":1}',
                'register' => '{"register_view":1,"register_close":1,"another_register_close":1}',
                'report' => '{"loss_profit_report":1,"purchase_sale_report":1,"tax_report":1,"customer_report":1,"supplier_report":1,"stock_report":1,"stock_adjustment_report":1,"pro_purchase_report":1,"pro_sale_report":1,"purchase_payment_report":1,"sale_payment_report":1,"expense_report":1,"c_register_report":1,"sale_representative_report":1,"payroll_report":1,"payroll_payment_report":1,"attendance_report":1,"production_report":1,"financial_report":1}',
                'setup' => '{"tax":1,"branch":1,"warehouse":1,"g_settings":1,"p_settings":1,"inv_sc":1,"inv_lay":1,"barcode_settings":1,"cash_counters":1}',
                'dashboard' => '{"dash_data":1}',
                'accounting' => '{"ac_access":1}',
                'hrms' => '{"hrm_dashboard":1,"leave_type":1,"leave_assign":1,"shift":1,"attendance":1,"view_allowance_and_deduction":1,"payroll":1,"holiday":1,"department":1,"designation":1,"payroll_report":1,"payroll_payment_report":1,"attendance_report":1}',
                'essential' => '{"assign_todo":1,"work_space":1,"memo":1,"msg":1}',
                'manufacturing' => '{"process_view":1,"process_add":1,"process_edit":1,"process_delete":1,"production_view":1,"production_add":1,"production_edit":1,"production_delete":1,"manuf_settings":1,"manuf_report":1}',
                'project' => '{"proj_view":1,"proj_create":1,"proj_edit":1,"proj_delete":1}',
                'repair' => '{"ripe_add_invo":1,"ripe_edit_invo":1,"ripe_view_invo":1,"ripe_delete_invo":1,"change_invo_status":1,"ripe_jop_sheet_status":1,"ripe_jop_sheet_add":1,"ripe_jop_sheet_edit":1,"ripe_jop_sheet_delete":1,"ripe_only_assinged_job_sheet":1,"ripe_view_all_job_sheet":1}',
                'superadmin' => '{"superadmin_access_pack_subscrip":1}',
                'e_commerce' => '{"e_com_sync_pro_cate":1,"e_com_sync_pro":1,"e_com_sync_order":1,"e_com_map_tax_rate":1}',
                'others' => '{"today_summery":1,"communication":1}',
                'is_super_admin_role' => 1,
            )
        );
        DB::table('role_permissions')->insert($role_permissions);

        // $user = AdminAndUser::create([
        //     'id' => 1,
        //     'name' => 'Super Admin',
        //     'email' => 'superadmin@metashops.com.sa',
        //     'username' => 'superadmin',
        //     'password' => bcrypt('12345'),
        //     'gender' => 'Male',
        //     'photo' => 'default.png',
        //     'allow_login' => 1,
        //     'role_type' => 1,
        //     'role_permission_id' => 1
        // ]);
    }
}
