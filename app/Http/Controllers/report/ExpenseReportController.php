<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Models\Expense;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ExpenseReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of expense report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $expenses = '';
            $query = DB::table('expenses')
                ->leftJoin('branches', 'expenses.branch_id', 'branches.id')
                ->leftJoin('admin_and_users', 'expenses.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('expenses.branch_id', NULL);
                } else {
                    $query->where('expenses.branch_id', $request->branch_id);
                }
            }

            if ($request->admin_id) {
                
                $query->where('expenses.admin_id', $request->admin_id);
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('expenses.report_date', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $expenses = $query->select(
                    'expenses.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->orderBy('expenses.report_date', 'desc');
            } else {
                $expenses = $query->select(
                    'expenses.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'admin_and_users.prefix as cr_prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name as cr_last_name',
                )->where('expenses.branch_id', auth()->user()->branch_id)
                    ->orderBy('expenses.report_date', 'desc');
            }

            return DataTables::of($expenses)
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('user_name',  function ($row) {
                    return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
                })
                ->editColumn('payment_status',  function ($row) {
                    $html = "";
                    $payable = $row->net_total_amount;
                    if ($row->due <= 0) {
                        $html .= '<span class="badge bg-success">Paid</span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="badge bg-primary text-white">Partial</span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="badge bg-danger text-white">Due</span>';
                    }
                    return $html;
                })
                ->editColumn('tax_percent',  function ($row) use($converter) {
                    $tax_amount = $row->total_amount / 100 * $row->tax_percent;
                    return '<b><span class="tax_amount" data-value="' . $tax_amount . '">' . $converter->format_in_bdt($tax_amount) . '(' . $row->tax_percent . '%)</span></b>';
                })
                ->editColumn('net_total', fn ($row) => '<span class="net_total" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')
                ->editColumn('paid', fn ($row) => '<span class="paid" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')
                ->editColumn('due', fn ($row) => '<span class="due" data-value="' . $row->due . '" class="text-danger">' . $this->converter->format_in_bdt($row->due) .'</span>')
                ->rawColumns(['action', 'date', 'from', 'user_name', 'payment_status', 'tax_percent', 'paid', 'due', 'net_total'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.expense_report.index', compact('branches'));
    }

    public function print(Request $request)
    {
        $expenses = '';
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';
        $query = DB::table('expenses')
            ->leftJoin('branches', 'expenses.branch_id', 'branches.id')
            ->leftJoin('admin_and_users', 'expenses.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('expenses.branch_id', NULL);
            } else {
                $query->where('expenses.branch_id', $request->branch_id);
            }
        }

        if ($request->admin_id) {
            $query->where('expenses.admin_id', $request->admin_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('expenses.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $expenses = $query->select(
                'expenses.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->orderBy('expenses.report_date', 'desc')->get();
        } else {
            $expenses = $query->select(
                'expenses.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'admin_and_users.prefix as cr_prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name as cr_last_name',
            )->where('expenses.branch_id', auth()->user()->branch_id)
                ->orderBy('expenses.report_date', 'desc')->get();
        }

        return view('reports.expense_report.ajax_view.print', compact('expenses', 'fromDate', 'toDate', 'branch_id'));
    }

    public function getFilteredExpenseReport(Request $request)
    {
        if ($request->ex_category_id && $request->branch_id && $request->date_range) {
            $this->getExpenseReport();
        }

        $expenses = Expense::orderBy('id', 'DESC');
        if ($request->branch_id) {
            $expenses->where('branch_id', $request->branch_id);
        }
    }
}
