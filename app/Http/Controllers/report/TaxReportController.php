<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TaxReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of cash register report
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.tax_report.index', compact('branches'));
    }

    public function getTaxReport(Request $request)
    {
        $purchases = '';
        $sales = '';
        $expenses = '';

        $purchase_query = DB::table('purchases')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->where('purchases.purchase_tax_percent', '>', 0);

        $sale_query = DB::table('sales')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->where('sales.order_tax_percent', '>', 0)
            ->where('sales.status', 1);

        $expense_query = DB::table('expenses')
            ->where('expenses.tax_percent', '>', 0);

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $purchase_query->where('purchases.branch_id', NULL);
                $sale_query->where('sales.branch_id', NULL);
                $expense_query->where('expenses.branch_id', NULL);
            } else {
                $purchase_query->where('purchases.branch_id', $request->branch_id);
                $sale_query->where('sales.branch_id', $request->branch_id);
                $expense_query->where('expenses.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $purchase_query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $sale_query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expense_query->whereBetween('expenses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        $purchases = $purchase_query->select(
            'purchases.date',
            'purchases.branch_id',
            'purchases.supplier_id',
            'purchases.invoice_id',
            'purchases.net_total_amount',
            'purchases.order_discount_amount',
            'purchases.purchase_tax_percent',
            'purchases.purchase_tax_amount',
            'suppliers.name as supplier_name',
            'suppliers.tax_number',
        )->get();

         $sales = $sale_query->select(
            'sales.date',
            'sales.branch_id',
            'sales.customer_id',
            'sales.invoice_id',
            'sales.net_total_amount',
            'sales.order_discount_amount',
            'sales.order_tax_percent',
            'sales.order_tax_amount',
            'sales.status',
            'customers.name as customer_name',
            'customers.tax_number',
        )->get();

        $expenses = $expense_query->select(
            'expenses.date',
            'expenses.invoice_id',
            'expenses.branch_id',
            'expenses.admin_id',
            'expenses.total_amount',
            'expenses.tax_percent',
        )->get();

        return view('reports.tax_report.ajax_view.tax_report', compact('purchases', 'sales', 'expenses'));
    }
}
