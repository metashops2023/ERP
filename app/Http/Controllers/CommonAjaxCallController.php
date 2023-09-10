<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonAjaxCallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function branchAuthenticatedUsers($branchId)
    {
        $branch_id = $branchId != 'NULL' ? $branchId : NULL;
        return DB::table('admin_and_users')
            ->where('branch_id', $branch_id)
            ->where('allow_login', 1)->get();
    }

    public function categorySubcategories($categoryId)
    {
        return DB::table('categories')->where('parent_category_id', $categoryId)->select('id', 'name')->get();
    }

    public function onlySearchProductForReports($product_name)
    {
        $products = '';
        $query = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query->distinct('product_branches.branch_id')->get(); 
        }else{

            $query->where('product_branches.branch_id', auth()->user()->branch_id)->distinct('product_branches.branch_id')->get(); 
        }

        $products = $query->select(
            'products.id as product_id',
            'products.name',
            'products.product_code',
            'product_variants.id as variant_id',
            'product_variants.variant_name',
            'product_variants.variant_code',
        )->get();

        if (count($products) > 0) {

            return view('common_ajax_view.product_search_result_for_report_filter', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchFinalSaleInvoices($invoiceId)
    {
        $invoices = DB::table('sales')
            ->where('branch_id', auth()->user()->branch_id)
            ->where('status', 1)->where('invoice_id', 'like', "%{$invoiceId}%")
            ->select('id', 'invoice_id', 'customer_id', 'due')->get();

        if (count($invoices) > 0) {

            return view('common_ajax_view.invoice_search_list', compact('invoices'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    public function getSaleProducts($saleId)
    {
        $saleReturn = DB::table('sale_returns')->where('sale_id', $saleId)->first();

        if ($saleReturn) {

            return response()->json(['errorMsg' => 'Sale Return has already been exists on this sale invoice']);
        }

        return DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->select(
                'sale_products.id',
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id as variant_id',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_type',
                'sale_products.unit_discount',
                'sale_products.unit_discount_amount',
                'sale_products.unit',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.quantity',
                'products.name as product_name',
                'products.is_manage_stock',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.tax_type',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
            )->get();
    }

    // Get customer info
    public function customerInfo($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)
            ->select('pay_term', 'pay_term_number', 'total_sale_due', 'point')->first();

        return response()->json($customer);
    }

    // Recent Add sale
    public function recentSale($create_by)
    {
        $sales = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->where('created_by', $create_by)
            ->where('is_return_available', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_sale_list', compact('sales'));
    }

    // Get all recent quotations ** requested by ajax **
    public function recentQuotations($create_by)
    {
        $quotations = Sale::where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 4)
            ->where('created_by', $create_by)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_quotation_list', compact('quotations'));
    }

    // Get all recent drafts ** requested by ajax **
    public function recentDrafts($create_by)
    {
        $drafts = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 2)
            ->where('created_by', $create_by)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('common_ajax_view.recent_draft_list', compact('drafts'));
    }

    // Search product 
    public function searchProductForReportFilter($product_name)
    {
        return $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->get();

        if (count($products) > 0) {

            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }

    // Get all parent Category
    public function branchWarehouses($branch_id)
    {
        $branch_id = $branch_id == 'NULL' ? NULL : $branch_id;

        return  DB::table('warehouse_branches')
        ->where('warehouse_branches.branch_id', $branch_id)
        ->orWhere('warehouse_branches.is_global', 1)
        ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
        ->select(
            'warehouses.id',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
        )->get();
    }

    public function branchAllowLoginUsers($branchId)
    {
        $branch_id = $branchId == 'NULL' ? NULL : $branchId;

        return DB::table('admin_and_users')
            ->where('branch_id', $branch_id)
            ->where('allow_login', 1)
            ->select('id', 'prefix', 'name', 'last_name')
            ->get();
    }

    public function branchUsers($branchId)
    {
        $branch_id = $branchId == 'NULL' ? NULL : $branchId;

        return DB::table('admin_and_users')
            ->where('branch_id', $branch_id)
            ->select('id', 'prefix', 'name', 'last_name')
            ->get();
    }

    public function getSupplier($supplierId)
    {
        $supplier = DB::table('suppliers')
            ->where('id', $supplierId)
            ->select(
                'name',
                'contact_id',
                'phone',
                'opening_balance',
                'total_purchase',
                'total_return',
                'total_paid',
                'total_less',
                'total_purchase_due',
                'total_purchase_return_due',
            )
            ->first();
        return response()->json($supplier);
    }
}
