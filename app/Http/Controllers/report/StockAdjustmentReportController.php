<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('stock_adjustments');
            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('branch_id', NULL);
                } else {
                    $query->where('branch_id', $request->branch_id);
                }
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('report_date_ts', $date_range);
            }

            return $query->select(
                DB::raw('sum(net_total_amount) as t_amount'),
                DB::raw('sum(recovered_amount) as t_recovered_amount'),
                DB::raw("SUM(IF(type = '1', net_total_amount, 0)) as total_normal"),
                DB::raw("SUM(IF(type = '2', net_total_amount, 0)) as total_abnormal"),
            )->get();
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.adjustment_report.index', compact('branches'));
    }

    // All Stock Adjustment **requested by ajax**
    public function allAdjustments(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $adjustments = '';
            $query = DB::table('stock_adjustments')->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
                ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('stock_adjustments.branch_id', NULL);
                } else {
                    $query->where('stock_adjustments.branch_id', $request->branch_id);
                }
            }

            if ($request->type) {
                $query->where('stock_adjustments.type', $request->type);
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('stock_adjustments.report_date_ts', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                )->orderBy('stock_adjustments.report_date_ts', 'desc');
            } else {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name as cr_name',
                    'admin_and_users.last_name',
                )->orderBy('stock_adjustments.report_date_ts', 'desc')
                ->where('stock_adjustments.branch_id', auth()->user()->branch_id);
            }

            return DataTables::of($adjustments)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })->editColumn('from',  function ($row) use ($generalSettings) {
                    if (!$row->branch_name && !$row->warehouse_name) {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    } else {
                        if ($row->branch_name) {
                            return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                        } else {
                            return $row->warehouse_name . '/' . $row->warehouse_code . '(<b>WH</b>)';
                        }
                    }
                })->editColumn('type',  function ($row) {
                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })->editColumn('net_total', fn ($row) => $this->converter->format_in_bdt($row->net_total_amount))
                ->editColumn('recovered_amount', fn ($row) => $this->converter->format_in_bdt($row->recovered_amount))
                ->editColumn('created_by', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })->rawColumns(['date', 'invoice_id', 'from', 'type', 'net_total', 'recovered_amount', 'created_by'])
                ->make(true);
        }
    }

    public function print(Request $request)
    {
        $branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
        $fromDate = '';
        $toDate = '';
        $adjustments = '';
        $query = DB::table('stock_adjustments')
            ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
            ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('stock_adjustments.branch_id', NULL);
            } else {
                $query->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->type) {
            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('stock_adjustments.report_date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $adjustments = $query->select(
                'stock_adjustments.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            )->orderBy('id', 'desc')->get();
        } else {
            $adjustments = $query->select(
                'stock_adjustments.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name as cr_name',
                'admin_and_users.last_name',
            )->where('stock_adjustments.branch_id', auth()->user()->branch_id)->get();
        }

        return view('reports.adjustment_report.ajax_view.print', compact('adjustments', 'branch_id', 'fromDate', 'toDate'));
    }
}
