<?php

namespace App\Http\Controllers\report;

use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $customers = '';
            if (auth()->user()->role_type == 1) {
                $query = DB::table('customers')->where('status', 1);
            } else if (auth()->user()->role_type == 2) {
                $query = DB::table('customers')->where('status', 1)->where('admin_user_id', auth()->user()->id);
            } else {
                $query = DB::table('customers')->where('status', 1)->where('branch_id', auth()->user()->branch_id);
            }
            // $query = DB::table('customers')->where('status', 1);
            if ($request->customer_id) {
                $query->where('customers.id', $request->customer_id);
            }

            $customers = $query->select(
                'customers.name',
                // 'customers.contact_id',
                'customers.phone',
                'customers.address',
                'customers.opening_balance',
                'customers.total_paid',
                'customers.total_sale',
                'customers.total_sale_due',
                'customers.total_sale_return_due'
            );

            return DataTables::of($customers)
                ->editColumn('name', function ($row) {
                    return $row->name . '(<b>' . $row->phone . '</b>)';
                })
                ->editColumn('opening_balance', fn ($row) => '<span class="opening_balance" data-value="' . $row->opening_balance . '">' . $this->converter->format_in_bdt($row->opening_balance) . '</span>')
                ->editColumn('total_paid', fn ($row) => '<span class="total_paid" data-value="' . $row->total_paid . '">' . $this->converter->format_in_bdt($row->total_paid) . '</span></>')
                ->editColumn('total_sale_due', fn ($row) => '<span class="total_purchase_due" data-value="' . $row->total_sale_due . '">' . $this->converter->format_in_bdt($row->total_sale_due) . '</span>')
                ->editColumn('total_sale', fn ($row) => '<span class="total_sale" data-value="' . $row->total_sale . '">' . $this->converter->format_in_bdt($row->total_sale) . '</span>')
                ->editColumn('total_sale_return_due', fn ($row) => '<span class="total_sale_return_due" data-value="' . $row->total_sale_return_due . '">' . $this->converter->format_in_bdt($row->total_sale_return_due) . '</span>')
                ->rawColumns(['name', 'opening_balance', 'total_paid', 'total_sale', 'total_sale_due', 'total_due', 'total_sale_return_due'])
                ->make(true);
        }
        if (auth()->user()->role_type == 1) {
            $customers = DB::table('customers')->get();
        } else if (auth()->user()->role_type == 2) {
            $customers = DB::table('customers')->where('admin_user_id', auth()->user()->id)->get();
        } else {
            $customers = DB::table('customers')->where('branch_id', auth()->user()->branch_id)->get();
        }
        // $customers = DB::table('customers')->select('id', 'name', 'phone')->get();
        return view('reports.customer_report.index', compact('customers'));
    }

    public function print(Request $request)
    {
        $customerReports = '';
        if (auth()->user()->role_type == 1) {
            $query = DB::table('customers')->where('status', 1);
        } else if (auth()->user()->role_type == 2) {
            $query = DB::table('customers')->where('status', 1)->where('admin_user_id', auth()->user()->id);
        } else {
            $query = DB::table('customers')->where('status', 1)->where('branch_id', auth()->user()->branch_id);
        }
        // $query = DB::table('customers')->where('status', 1);
        if ($request->customer_id) {
            $query->where('customers.id', $request->customer_id);
        }

        $customerReports = $query->select(
            'customers.name',
            // 'customers.contact_id',
            'customers.phone',
            'customers.address',
            'customers.opening_balance',
            'customers.total_paid',
            'customers.total_sale',
            'customers.total_sale_due',
            'customers.total_sale_return_due'
        )->get();
        return view('reports.customer_report.ajax_view.print', compact('customerReports'));
    }
}
