<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnStatementController extends Controller
{
    public $converter;
    public function __construct(
        Converter $converter
    ) {
        $this->middleware('auth:admin_and_user');
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $returns = '';

            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
                ->leftJoin('customers', 'sale_returns.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sale_returns.admin_id', 'admin_and_users.id');

            $query->select(
                'sale_returns.id',
                'sale_returns.total_item',
                'sale_returns.total_qty',
                'sale_returns.invoice_id',
                'sale_returns.date',
                'sale_returns.net_total_amount',
                'sale_returns.return_discount_amount',
                'sale_returns.return_tax',
                'sale_returns.return_tax_amount',
                'sale_returns.total_return_amount',
                'sale_returns.total_return_due_pay',
                'branches.name as branch_name',
                'branches.branch_code',
                'sales.invoice_id as parent_sale',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $returns = $this->filteredQuery($request, $query)->orderBy('sale_returns.report_date', 'desc');
            } else {

                $returns = $this->filteredQuery($request, $query)->where('sale_returns.branch_id', auth()->user()->branch_id)->orderBy('sale_returns.report_date', 'desc');
            }

            return DataTables::of($returns)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
                    return date($__date_format, strtotime($row->date));
                })

                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })

                ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

                ->editColumn('created_by', fn ($row) => $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name)

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . $this->converter->format_in_bdt($row->total_item) . '</span>')

                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . $this->converter->format_in_bdt($row->total_qty) . '</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('return_discount_amount', fn ($row) => '<span class="return_discount_amount" data-value="' . $row->return_discount_amount . '">' . $this->converter->format_in_bdt($row->return_discount_amount) . '</span>')

                ->editColumn('return_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="' . $row->return_tax_amount . '">' . $this->converter->format_in_bdt($row->return_tax_amount) . '(' . $row->return_tax . '%)' . '</span>')

                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount text-danger" data-value="' . $row->total_return_amount . '">' . $this->converter->format_in_bdt($row->total_return_amount) . '</span>')

                ->editColumn('total_return_due_pay', fn ($row) => '<span class="total_return_due_pay text-success" data-value="' . $row->total_return_due_pay . '">' . $this->converter->format_in_bdt($row->total_return_due_pay) . '</span>')

                ->rawColumns(['date', 'invoice_id', 'from', 'customer', 'created_by', 'total_item', 'total_qty', 'net_total_amount', 'return_discount_amount', 'return_tax_amount', 'total_return_amount', 'total_return_amount', 'total_return_due_pay'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);

        return view('reports.sale_return_statement.index', compact('branches', 'customers'));
    }

    public function print(Request $request)
    {
        $branch_id = $request->branch_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $returns = '';

        $query = DB::table('sale_returns')
            ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('customers', 'sale_returns.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sale_returns.admin_id', 'admin_and_users.id');

        $query->select(
            'sale_returns.id',
            'sale_returns.total_item',
            'sale_returns.total_qty',
            'sale_returns.invoice_id',
            'sale_returns.date',
            'sale_returns.net_total_amount',
            'sale_returns.return_discount_amount',
            'sale_returns.return_tax',
            'sale_returns.return_tax_amount',
            'sale_returns.total_return_amount',
            'sale_returns.total_return_due_pay',
            'branches.name as branch_name',
            'branches.branch_code',
            'sales.invoice_id as parent_sale',
            'customers.name as customer_name',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $returns = $this->filteredQuery($request, $query)
                ->orderBy('sale_returns.report_date', 'desc')->get();
        } else {

            $returns = $this->filteredQuery($request, $query)
                ->where('sale_returns.branch_id', auth()->user()->branch_id)
                ->orderBy('sale_returns.report_date', 'desc')->get();
        }

        return view('reports.sale_return_statement.ajax_view.print', compact('returns', 'branch_id', 'fromDate', 'toDate'));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sale_returns.branch_id', NULL);
            } else {

                $query->where('sale_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sale_returns.admin_id', $request->user_id);
        }

        if ($request->customer_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sale_returns.customer_id', NULL);
            } else {

                $query->where('sale_returns.customer_id', $request->customer_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sale_returns.report_date', $date_range); // Final
        }
        return $query;
    }
}
