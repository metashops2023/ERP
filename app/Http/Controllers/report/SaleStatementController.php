<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SaleStatementController extends Controller
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

            $sales = '';

            $userPermission = auth()->user()->permission;

            $query = DB::table('sales')
                ->whereIn('sales.status', [1, 3])
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

            $query->select(
                'sales.id',
                'sales.total_item',
                'sales.branch_id',
                'sales.invoice_id',
                'sales.date',
                'sales.net_total_amount',
                'sales.total_payable_amount',
                'sales.sale_return_amount',
                'sales.order_discount_amount',
                'sales.order_tax_percent',
                'sales.order_tax_amount',
                'sales.shipment_charge',
                'sales.paid',
                'sales.due',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as u_prefix',
                'admin_and_users.name as u_name',
                'admin_and_users.last_name as u_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $sales = $this->filteredQuery($request, $query)->where('sales.status', 1)
                    ->orderBy('sales.report_date', 'desc');
            } else {

                if ($userPermission->sale['view_own_sale'] == '1') {

                    $query->where('sales.admin_id', auth()->user()->id);
                }

                $sales = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)
                    ->where('sales.status', 1)
                    ->orderBy('sales.report_date', 'desc');
            }

            return DataTables::of($sales)

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

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="' . $row->order_discount_amount . '">' . $this->converter->format_in_bdt($row->order_discount_amount) . '</span>')

                ->editColumn('order_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="' . $row->order_tax_amount . '">' . $this->converter->format_in_bdt($row->order_tax_amount) . '(' . $row->order_tax_percent . '%)' . '</span>')

                ->editColumn('shipment_charge', fn ($row) => '<span class="shipment_charge" data-value="' . $row->shipment_charge . '">' . $this->converter->format_in_bdt($row->shipment_charge) . '</span>')

                ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

                ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')

                ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

                ->rawColumns(['date', 'invoice_id', 'from', 'customer', 'created_by', 'total_item', 'net_total_amount', 'total_payable_amount', 'order_discount_amount', 'order_tax_amount', 'shipment_charge', 'paid', 'due', 'sale_return_amount'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);

        return view('reports.sale_statement.index', compact('branches', 'customers'));
    }

    public function print(Request $request)
    {
        $branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $sales = '';

        $userPermission = auth()->user()->permission;

        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.id',
            'sales.total_item',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.net_total_amount',
            'sales.total_payable_amount',
            'sales.sale_return_amount',
            'sales.order_discount_amount',
            'sales.order_tax_percent',
            'sales.order_tax_amount',
            'sales.shipment_charge',
            'sales.paid',
            'sales.due',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $this->filteredQuery($request, $query)
                ->where('sales.status', 1)
                ->orderBy('sales.report_date', 'desc')->get();
        } else {

            if ($userPermission->sale['view_own_sale'] == '1') {

                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->orderBy('sales.report_date', 'desc')->get();
        }

        return view('reports.sale_statement.ajax_view.print', compact('sales', 'branch_id', 'fromDate', 'toDate'));
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', NULL);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.admin_id', $request->user_id);
        }

        if ($request->customer_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_id', NULL);
            } else {

                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == 1) {

                $query->where('sales.due', '=', 0);
            } else {

                $query->where('sales.due', '>', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }
        return $query;
    }
}
