<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Role index view
    public function index()
    {
        if (auth()->user()->permission->user['role_view'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        return view('users.roles.index');
    }

    // Role index view
    public function allRoles()
    {
        $roles = Role::all();
        return view('users.roles.ajax_view.role_list', compact('roles'));
    }

    // Create cash role
    public function create()
    {
        if (auth()->user()->permission->user['role_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('users.roles.create');
    }

    // Add role and permission
    public function store(Request $request)
    {
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
        ]);

        $addRole = new Role();
        $addRole->name = $request->role_name;
        $addRole->save();

        $addRolePermission = new RolePermission();
        $addRolePermission->role_id = $addRole->id;
        $addRolePermission->user = $this->userPermission($request);
        $addRolePermission->contact = $this->contactPermission($request);
        $addRolePermission->product = $this->productPermission($request);
        $addRolePermission->purchase = $this->purchasePermission($request);
        $addRolePermission->s_adjust = $this->s_adjustPermission($request);
        $addRolePermission->expense = $this->expensePermissions($request);
        $addRolePermission->sale = $this->salePermission($request);
        $addRolePermission->register = $this->cashRegisterPermission($request);
        $addRolePermission->report = $this->reportPermission($request);
        $addRolePermission->setup = $this->setupPermission($request);
        $addRolePermission->dashboard = $this->dashboardPermission($request);
        $addRolePermission->accounting = $this->accountingPermission($request);
        $addRolePermission->hrms = $this->hrmsPermission($request);
        $addRolePermission->essential = $this->essentialPermission($request);
        $addRolePermission->manufacturing = $this->manufacturingPermission($request);
        $addRolePermission->project = $this->projectPermission($request);
        $addRolePermission->repair = $this->repairPermission($request);
        $addRolePermission->superadmin = $this->superadminPermission($request);
        $addRolePermission->e_commerce = $this->eCommercePermission($request);
        $addRolePermission->others = $this->othersPermission($request);
        $addRolePermission->save();

        session()->flash('successMsg', 'Successfully Role is added.');
        return redirect()->route('users.role.index');
    }

    // Add role and permission
    public function update(Request $request, $roleId)
    {
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name,' . $roleId,
        ]);

        $updateRole =  Role::where('id', $roleId)->first();
        $updateRole->name = $request->role_name;
        $updateRole->save();

        $updateRolePermission = RolePermission::where('role_id', $roleId)->first();
        $updateRolePermission->role_id = $updateRole->id;
        $updateRolePermission->user = $this->userPermission($request);
        $updateRolePermission->contact = $this->contactPermission($request);
        $updateRolePermission->product = $this->productPermission($request);
        $updateRolePermission->purchase = $this->purchasePermission($request);
        $updateRolePermission->s_adjust = $this->s_adjustPermission($request);
        $updateRolePermission->expense = $this->expensePermissions($request);
        $updateRolePermission->sale = $this->salePermission($request);
        $updateRolePermission->register = $this->cashRegisterPermission($request);
        $updateRolePermission->report = $this->reportPermission($request);
        $updateRolePermission->setup = $this->setupPermission($request);
        $updateRolePermission->dashboard = $this->dashboardPermission($request);
        $updateRolePermission->accounting = $this->accountingPermission($request);
        $updateRolePermission->hrms = $this->hrmsPermission($request);
        $updateRolePermission->essential = $this->essentialPermission($request);
        $updateRolePermission->manufacturing = $this->manufacturingPermission($request);
        $updateRolePermission->project = $this->projectPermission($request);
        $updateRolePermission->repair = $this->repairPermission($request);
        $updateRolePermission->superadmin = $this->superadminPermission($request);
        $updateRolePermission->e_commerce = $this->eCommercePermission($request);
        $updateRolePermission->others = $this->othersPermission($request);
        $updateRolePermission->save();

        session()->flash('successMsg', 'Successfully Role is updated.');
        return redirect()->route('users.role.index');
    }

    // Edit view of role
    public function edit($roleId)
    {
        if (auth()->user()->permission->user['role_edit'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $role = Role::with('permission')->where('id', $roleId)->firstOrFail();
        return view('users.roles.edit', compact('role'));
    }

    // Delete Role
    public function delete(Request $request, $roleId)
    {
        if (auth()->user()->permission->user['role_delete'] == '0') {
            return response()->json('Access Denied.');
        }

        $deleteRole = Role::find($roleId);
        if (!is_null($deleteRole)) {
            $deleteRole->delete();
        }
        return response()->json('Successfully role is deleted');
    }

    // User permissions
    private function userPermission($request)
    {
        $permissions = [
            'user_view' => isset($request->user_view) ? 1 : 0,
            'user_add' => isset($request->user_add) ? 1 : 0,
            'user_edit' => isset($request->user_edit) ? 1 : 0,
            'user_delete' => isset($request->user_delete) ? 1 : 0,
            'role_view' => isset($request->role_view) ? 1 : 0,
            'role_add' => isset($request->role_add) ? 1 : 0,
            'role_edit' => isset($request->role_edit) ? 1 : 0,
            'role_delete' => isset($request->role_delete) ? 1 : 0,
        ];

        return $permissions;
    }

    // Supplier permissions
    private function contactPermission($request)
    {
        $permissions = [
            'supplier_all' => isset($request->supplier_all) ? 1 : 0,
            'supplier_add' => isset($request->supplier_add) ? 1 : 0,
            'supplier_import' => isset($request->supplier_import) ? 1 : 0,
            'supplier_edit' => isset($request->supplier_edit) ? 1 : 0,
            'supplier_delete' => isset($request->supplier_delete) ? 1 : 0,
            'customer_all' => isset($request->customer_all) ? 1 : 0,
            'customer_add' => isset($request->customer_add) ? 1 : 0,
            'customer_import' => isset($request->customer_add) ? 1 : 0,
            'customer_edit' => isset($request->customer_edit) ? 1 : 0,
            'customer_delete' => isset($request->customer_delete) ? 1 : 0,
            'customer_group' => isset($request->customer_group) ? 1 : 0,
            'customer_report' => isset($request->customer_report) ? 1 : 0,
            'supplier_report' => isset($request->supplier_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Product permissions
    private function productPermission($request)
    {
        $permissions = [
            'product_all' => isset($request->product_all) ? 1 : 0,
            'product_add' => isset($request->product_add) ? 1 : 0,
            'product_edit' => isset($request->product_edit) ? 1 : 0,
            'openingStock_add' => isset($request->openingStock_add) ? 1 : 0,
            'product_delete' => isset($request->product_delete) ? 1 : 0,
            'categories' => isset($request->categories) ? 1 : 0,
            'brand' => isset($request->brand) ? 1 : 0,
            'units' => isset($request->units) ? 1 : 0,
            'variant' => isset($request->variant) ? 1 : 0,
            'warranties' => isset($request->warranties) ? 1 : 0,
            'selling_price_group' => isset($request->selling_price_group) ? 1 : 0,
            'generate_barcode' => isset($request->generate_barcode) ? 1 : 0,
            'product_settings' => isset($request->product_settings) ? 1 : 0,
            'stock_report' => isset($request->stock_report) ? 1 : 0,
            'stock_in_out_report' => isset($request->stock_in_out_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Purchase permissions
    private function purchasePermission($request)
    {
        $permissions = [
            'purchase_all' => isset($request->purchase_all) ? 1 : 0,
            'purchase_add' => isset($request->purchase_add) ? 1 : 0,
            'purchase_edit' => isset($request->purchase_edit) ? 1 : 0,
            'purchase_delete' => isset($request->purchase_delete) ? 1 : 0,
            'purchase_payment' => isset($request->purchase_payment) ? 1 : 0,
            'purchase_return' => isset($request->purchase_return) ? 1 : 0,
            'status_update' => isset($request->status_update) ? 1 : 0,
            'purchase_settings' => isset($request->purchase_settings) ? 1 : 0,
            'purchase_statements' => isset($request->purchase_statements) ? 1 : 0,
            'purchase_sale_report' => isset($request->purchase_sale_report) ? 1 : 0,
            'pro_purchase_report' => isset($request->pro_purchase_report) ? 1 : 0,
            'purchase_payment_report' => isset($request->purchase_payment_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Stock Adjustment permissions
    private function s_adjustPermission($request)
    {
        $permissions = [
            'adjustment_all' => isset($request->adjustment_all) ? 1 : 0,
            'adjustment_add_from_location' => isset($request->adjustment_add_from_location) ? 1 : 0,
            'adjustment_add_from_warehouse' => isset($request->adjustment_add_from_warehouse) ? 1 : 0,
            'adjustment_delete' => isset($request->adjustment_delete) ? 1 : 0,
            'stock_adjustment_report' => isset($request->stock_adjustment_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Expense permissions
    private function expensePermissions($request)
    {
        $permissions = [
            'view_expense' => isset($request->view_expense) ? 1 : 0,
            'add_expense' => isset($request->add_expense) ? 1 : 0,
            'edit_expense' => isset($request->edit_expense) ? 1 : 0,
            'delete_expense' => isset($request->delete_expense) ? 1 : 0,
            'expense_category' => isset($request->expense_category) ? 1 : 0,
            'category_wise_expense' => isset($request->category_wise_expense) ? 1 : 0,
            'expense_report' => isset($request->expense_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Sale permissions
    private function salePermission($request)
    {
        $permissions = [
            'pos_all' => isset($request->pos_all) ? 1 : 0,
            'pos_add' => isset($request->pos_add) ? 1 : 0,
            'pos_edit' => isset($request->pos_edit) ? 1 : 0,
            'pos_delete' => isset($request->pos_delete) ? 1 : 0,
            'pos_sale_settings' => isset($request->pos_sale_settings) ? 1 : 0,
            'create_add_sale' => isset($request->create_add_sale) ? 1 : 0,
            'view_add_sale' => isset($request->view_add_sale) ? 1 : 0,
            'edit_add_sale' => isset($request->edit_add_sale) ? 1 : 0,
            'delete_add_sale' => isset($request->delete_add_sale) ? 1 : 0,
            'add_sale_settings' => isset($request->add_sale_settings) ? 1 : 0,
            'sale_draft' => isset($request->sale_draft) ? 1 : 0,
            'sale_quotation' => isset($request->sale_quotation) ? 1 : 0,
            'sale_payment' => isset($request->sale_payment) ? 1 : 0,
            'edit_price_sale_screen' => isset($request->edit_price_sale_screen) ? 1 : 0,
            'edit_price_pos_screen' => isset($request->edit_price_pos_screen) ? 1 : 0,
            'edit_discount_sale_screen' => isset($request->edit_discount_sale_screen) ? 1 : 0,
            'edit_discount_pos_screen' => isset($request->edit_discount_pos_screen) ? 1 : 0,
            'shipment_access' => isset($request->shipment_access) ? 1 : 0,
            'view_product_cost_is_sale_screed' => isset($request->view_product_cost_is_sale_screed) ? 1 : 0,
            'view_own_sale' => isset($request->view_own_sale) ? 1 : 0,
            'return_access' => isset($request->return_access) ? 1 : 0,
            'discounts' => isset($request->discounts) ? 1 : 0,
            'sale_statements' => isset($request->sale_statements) ? 1 : 0,
            'sale_return_statements' => isset($request->sale_return_statements) ? 1 : 0,
            'pro_sale_report' => isset($request->pro_sale_report) ? 1 : 0,
            'sale_payment_report' => isset($request->sale_payment_report) ? 1 : 0,
            'c_register_report' => isset($request->c_register_report) ? 1 : 0,
            'sale_representative_report' => isset($request->sale_representative_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Cash Register  permissions
    private function cashRegisterPermission($request)
    {
        $permissions = [
            'register_view' => isset($request->register_view) ? 1 : 0,
            'register_close' => isset($request->register_close) ? 1 : 0,
            'another_register_close' => isset($request->another_register_close) ? 1 : 0,
        ];

        return $permissions;
    }

    // Unit permissions
    private function reportPermission($request)
    {
        $permissions = [
            'tax_report' => isset($request->tax_report) ? 1 : 0,
            'production_report' => isset($request->production_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Setup permissions
    private function setupPermission($request)
    {
        $permissions = [
            'tax' => isset($request->tax) ? 1 : 0,
            'branch' => isset($request->branch) ? 1 : 0,
            'warehouse' => isset($request->warehouse) ? 1 : 0,
            'g_settings' => isset($request->g_settings) ? 1 : 0,
            'p_settings' => isset($request->p_settings) ? 1 : 0,
            'inv_sc' => isset($request->inv_sc) ? 1 : 0,
            'inv_lay' => isset($request->inv_lay) ? 1 : 0,
            'barcode_settings' => isset($request->barcode_settings) ? 1 : 0,
            'cash_counters' => isset($request->cash_counters) ? 1 : 0,
        ];

        return $permissions;
    }

    // Dashboard permissions
    private function dashboardPermission($request)
    {
        $permissions = [
            'dash_data' => isset($request->dash_data) ? 1 : 0,
        ];
        return $permissions;
    }

    // Accounting permissions
    private function accountingPermission($request)
    {
        $permissions = [
            'ac_access' => isset($request->ac_access) ? 1 : 0,
        ];
        return $permissions;
    }

    // Human Resource management system (HRMS) permissions
    private function hrmsPermission($request)
    {
        $permissions = [
            'hrm_dashboard' => isset($request->hrm_dashboard) ? 1 : 0,
            'leave_type' => isset($request->leave_type) ? 1 : 0,
            'leave_assign' => isset($request->leave_assign) ? 1 : 0,
            'shift' => isset($request->shift) ? 1 : 0,
            'attendance' => isset($request->attendance) ? 1 : 0,
            'view_allowance_and_deduction' => isset($request->view_allowance_and_deduction) ? 1 : 0,
            'payroll' => isset($request->payroll) ? 1 : 0,
            'holiday' => isset($request->holiday) ? 1 : 0,
            'department' => isset($request->department) ? 1 : 0,
            'designation' => isset($request->designation) ? 1 : 0,
            'payroll_report' => isset($request->payroll_report) ? 1 : 0,
            'payroll_payment_report' => isset($request->payroll_payment_report) ? 1 : 0,
            'attendance_report' => isset($request->attendance_report) ? 1 : 0,
        ];

        return $permissions;
    }

    // Essentials permissions
    private function essentialPermission($request)
    {
        $permissions = [
            'assign_todo' => isset($request->assign_todo) ? 1 : 0,
            'work_space' => isset($request->work_space) ? 1 : 0,
            'memo' => isset($request->memo) ? 1 : 0,
            'msg' => isset($request->msg) ? 1 : 0,
        ];
        return $permissions;
    }

    // Manufacturing permissions
    private function manufacturingPermission($request)
    {
        $permissions = [
            'process_view' => isset($request->process_view) ? 1 : 0,
            'process_add' => isset($request->process_add) ? 1 : 0,
            'process_edit' => isset($request->process_edit) ? 1 : 0,
            'process_delete' => isset($request->process_delete) ? 1 : 0,
            'production_view' => isset($request->production_view) ? 1 : 0,
            'production_add' => isset($request->production_add) ? 1 : 0,
            'production_edit' => isset($request->production_edit) ? 1 : 0,
            'production_delete' => isset($request->production_delete) ? 1 : 0,
            'process_delete' => isset($request->process_delete) ? 1 : 0,
            'manuf_settings' => isset($request->manuf_settings) ? 1 : 0,
            'manuf_report' => isset($request->manuf_report) ? 1 : 0,
        ];
        return $permissions;
    }

    // Project permissions
    private function projectPermission($request)
    {
        $permissions = [
            'proj_view' => isset($request->proj_view) ? 1 : 0,
            'proj_create' => isset($request->proj_create) ? 1 : 0,
            'proj_edit' => isset($request->proj_edit) ? 1 : 0,
            'proj_delete' => isset($request->proj_delete) ? 1 : 0,
        ];
        return $permissions;
    }

    // Project permissions
    private function repairPermission($request)
    {
        $permissions = [
            'ripe_add_invo' => isset($request->ripe_add_invo) ? 1 : 0,
            'ripe_edit_invo' => isset($request->ripe_edit_invo) ? 1 : 0,
            'ripe_view_invo' => isset($request->ripe_view_invo) ? 1 : 0,
            'ripe_delete_invo' => isset($request->ripe_delete_invo) ? 1 : 0,
            'change_invo_status' => isset($request->ripe_delete_invo) ? 1 : 0,
            'ripe_jop_sheet_status' => isset($request->ripe_jop_sheet_status) ? 1 : 0,
            'ripe_jop_sheet_add' => isset($request->ripe_jop_sheet_add) ? 1 : 0,
            'ripe_jop_sheet_edit' => isset($request->ripe_jop_sheet_edit) ? 1 : 0,
            'ripe_jop_sheet_delete' => isset($request->ripe_jop_sheet_delete) ? 1 : 0,
            'ripe_only_assinged_job_sheet' => isset($request->ripe_only_assinged_job_sheet) ? 1 : 0,
            'ripe_view_all_job_sheet' => isset($request->ripe_view_all_job_sheet) ? 1 : 0,
        ];
        return $permissions;
    }

    // Super-admin permissions
    private function superadminPermission($request)
    {
        $permissions = [
            'superadmin_access_pack_subscrip' => isset($request->superadmin_access_pack_subscrip) ? 1 : 0,
        ];
        return $permissions;
    }

    // E-commerce permissions
    private function eCommercePermission($request)
    {
        $permissions = [
            'e_com_sync_pro_cate' => isset($request->e_com_sync_pro_cate) ? 1 : 0,
            'e_com_sync_pro' => isset($request->e_com_sync_pro) ? 1 : 0,
            'e_com_sync_order' => isset($request->e_com_sync_order) ? 1 : 0,
            'e_com_map_tax_rate' => isset($request->e_com_map_tax_rate) ? 1 : 0,
        ];
        return $permissions;
    }

    // E-commerce permissions
    private function othersPermission($request)
    {
        $permissions = [
            'today_summery' => isset($request->today_summery) ? 1 : 0,
            'communication' => isset($request->communication) ? 1 : 0,
        ];
        return $permissions;
    }
}
