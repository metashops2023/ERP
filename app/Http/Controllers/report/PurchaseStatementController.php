<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PurchaseStatementController extends Controller
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
            $purchases = '';

            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
                ->leftJoin('admin_and_users', 'purchases.admin_id', 'admin_and_users.id');

            if (!empty($request->branch_id)) {

                if ($request->branch_id == 'NULL') {

                    $query->where('purchases.branch_id', NULL);
                } else {

                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if (!empty($request->warehouse_id)) {

                $query->where('purchases.warehouse_id', $request->warehouse_id);
            }

            if ($request->supplier_id) {

                $query->where('purchases.supplier_id', $request->supplier_id);
            }

            if ($request->status) {

                $query->where('purchases.purchase_status', $request->status);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchases.report_date', $date_range); // Final
            }

            $query->select(
                'purchases.id',
                'purchases.branch_id',
                'purchases.warehouse_id',
                'purchases.date',
                'purchases.invoice_id',
                'purchases.total_item',
                'purchases.net_total_amount',
                'purchases.order_discount_amount',
                'purchases.purchase_tax_percent',
                'purchases.purchase_tax_amount',
                'purchases.total_purchase_amount',
                'purchases.purchase_return_amount',
                'purchases.due',
                'purchases.paid',
                'purchases.purchase_status',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
                'admin_and_users.prefix as created_prefix',
                'admin_and_users.name as created_name',
                'admin_and_users.last_name as created_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $purchases = $query->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc');
            } else {

                $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                    ->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc');
            }

            return DataTables::of($purchases)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })

                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return $row->warehouse_name . '<b>(WH)</b>';
                    } elseif ($row->branch_name) {

                        return $row->branch_name . '<b>(BL)</b>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                    }
                })

                ->editColumn('created_by', function ($row) {

                    return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
                })

                ->editColumn('status', function ($row) {

                    if ($row->purchase_status == 1) {

                        return '<span class="text-success"><b>Purchased</b></span>';
                    } elseif ($row->purchase_status == 2) {

                        return '<span class="text-secondary"><b>Pending</b></span>';
                    } elseif ($row->purchase_status == 3) {

                        return '<span class="text-primary"><b>Purchased By Order</b></span>';
                    }
                })

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . $this->converter->format_in_bdt($row->total_item) . '</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="' . $row->order_discount_amount . '">' . $this->converter->format_in_bdt($row->order_discount_amount) . '</span>')

                ->editColumn('purchase_tax_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->purchase_tax_amount . '">' . $this->converter->format_in_bdt($row->purchase_tax_amount) . '(' . $row->purchase_tax_percent . ')' . '</span>')

                ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . $this->converter->format_in_bdt($row->total_purchase_amount) . '</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

                ->editColumn('purchase_return_amount', fn ($row) => '<span class="purchase_return_amount" data-value="' . $row->purchase_return_amount . '">' . $this->converter->format_in_bdt($row->purchase_return_amount) . '</span>')

                ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span></span>')

                ->rawColumns(['date', 'from', 'created_by', 'status', 'total_item', 'net_total_amount', 'order_discount_amount', 'purchase_tax_amount', 'total_purchase_amount', 'paid', 'purchase_return_amount', 'due'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();

        return view('reports.purchase_statements.index', compact('branches', 'suppliers'));
    }

    public function print(Request $request)
    {
        $branch_id = $request->branch_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $purchases = '';

        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('admin_and_users', 'purchases.admin_id', 'admin_and_users.id');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', NULL);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if (!empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_id) {

            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->status) {

            $query->where('purchases.purchase_status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.total_item',
            'purchases.net_total_amount',
            'purchases.order_discount_amount',
            'purchases.purchase_tax_percent',
            'purchases.purchase_tax_amount',
            'purchases.total_purchase_amount',
            'purchases.purchase_return_amount',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'admin_and_users.prefix as created_prefix',
            'admin_and_users.name as created_name',
            'admin_and_users.last_name as created_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchases = $query->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc')->get();
        } else {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc')->get();
        }

        return view('reports.purchase_statements.ajax_view.print', compact('purchases', 'branch_id', 'fromDate', 'toDate'));
    }
}
