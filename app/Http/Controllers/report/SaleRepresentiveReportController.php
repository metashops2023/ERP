<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SaleRepresentiveReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of cash register report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $sale_query = DB::table('sales')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->where('sales.status', 1);

            if ($request->user_id) {
                $sale_query->where('sales.admin_id', $request->user_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $sale_query->where('sales.branch_id', NULL);
                } else {
                    $sale_query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $sale_query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $sales = $sale_query->select(
                    'sales.date',
                    'sales.customer_id',
                    'sales.invoice_id',
                    'sales.total_payable_amount',
                    'sales.paid',
                    'sales.due',
                    'sales.sale_return_amount',
                    'sales.sale_return_due',
                    'sales.status',
                    'customers.name as customer_name',
                    'branches.name as branch_name',
                    'branches.branch_code',
                );
            } else {
                $sales = $sale_query->select(
                    'sales.date',
                    'sales.customer_id',
                    'sales.invoice_id',
                    'sales.total_payable_amount',
                    'sales.paid',
                    'sales.due',
                    'sales.sale_return_amount',
                    'sales.sale_return_due',
                    'sales.status',
                    'customers.name as customer_name',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->where('sales.branch_id', auth()->user()->branch_id);
            }

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('branch',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('payment_status', function ($row) {
                    $payable = $row->total_payable_amount - $row->sale_return_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->editColumn('total_amount',  function ($row) use ($generalSettings) {
                    return '<b><span class="total_amount" data-value="' . $row->total_payable_amount . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</span></b>';
                })
                ->editColumn('paid',  function ($row) use ($generalSettings) {
                    return '<b><span class="paid" data-value="' . $row->paid . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</span></b>';
                })
                ->editColumn('total_return', function ($row) use ($generalSettings) {
                    return '<b><span class="total_return" data-value="' . $row->sale_return_amount . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->sale_return_amount . '</span></b>';
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {
                    return '<b><span class="due" data-value="' . $row->due . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due . '</span></b>';
                })
                ->rawColumns(['date', 'branch', 'customer', 'payment_status', 'total_amount', 'paid', 'total_return', 'due'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.sale_representive_report.index', compact('branches'));
    }

    public function SaleRepresentiveExpenseReport(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';
            $expense_query = DB::table('expenses')
                ->leftJoin('branches', 'expenses.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'expenses.admin_id', 'admin_and_users.id');

            if ($request->user_id) {
                $expense_query->where('expenses.admin_id', $request->user_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $expense_query->where('expenses.branch_id', NULL);
                } else {
                    $expense_query->where('expenses.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));
                $expense_query->whereBetween('expenses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $expenses = $expense_query->select(
                    'expenses.date',
                    'expenses.invoice_id',
                    'expenses.branch_id',
                    'expenses.admin_id',
                    'expenses.net_total_amount',
                    'expenses.paid',
                    'expenses.due',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as user_name',
                    'admin_and_users.last_name as user_last_name',
                )->get();
            } else {
                $expenses = $expense_query->select(
                    'expenses.date',
                    'expenses.invoice_id',
                    'expenses.branch_id',
                    'expenses.admin_id',
                    'expenses.net_total_amount',
                    'expenses.paid',
                    'expenses.due',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as user_name',
                    'admin_and_users.last_name as user_last_name',
                )->where('expenses.branch_id', auth()->user()->branch_id)->get();
            }

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('user',  function ($row) {
                    return $row->prefix.' '.$row->user_name.' '.$row->user_last_name;
                })
                ->editColumn('branch',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('payment_status', function ($row) {
                    $payable = $row->net_total_amount;
                    $html = '';
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->editColumn('total_amount',  function ($row) use ($generalSettings) {
                    return '<b><span class="ex_total" data-value="' . $row->net_total_amount . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_total_amount . '</span></b>';
                })
                ->editColumn('paid',  function ($row) use ($generalSettings) {
                    return '<b><span class="ex_paid" data-value="' . $row->paid . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</span></b>';
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {
                    return '<b><span class="ex_due" data-value="' . $row->due . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due . '</span></b>';
                })
                ->rawColumns(['date', 'branch', 'user' ,'payment_status', 'total_amount', 'paid', 'due'])
                ->make(true);
        }
        return view('reports.sale_representive_report.ajax_view.representive_reports', compact('sales', 'expenses'));
    }
}
