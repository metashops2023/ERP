<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PurchasePaymentReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of purchase payment report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $payments = '';
            $query = DB::table('purchase_payments')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->join('suppliers', 'purchases.supplier_id', 'suppliers.id');

            if ($request->supplier_id) {
                $query->where('purchases.supplier_id', $request->supplier_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_payments.report_date', $date_range);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $payments = $query->select(
                    'purchase_payments.id as payment_id',
                    'purchase_payments.invoice_id as payment_invoice',
                    'purchase_payments.paid_amount',
                    'purchase_payments.pay_mode',
                    'purchase_payments.date',
                    'purchases.invoice_id as purchase_invoice',
                    'suppliers.name as supplier_name',
                )->orderBy('purchase_payments.report_date', 'desc');
            } else {
                $payments = $query->select(
                    'purchase_payments.id as payment_id',
                    'purchase_payments.invoice_id as payment_invoice',
                    'purchase_payments.paid_amount',
                    'purchase_payments.pay_mode',
                    'purchase_payments.date',
                    'purchases.invoice_id as purchase_invoice',
                    'suppliers.name as supplier_name',
                )->where('purchases.branch_id', auth()->user()->branch_id)
                    ->orderBy('purchase_payments.report_date', 'desc');
            }


            return DataTables::of($payments)
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('paid_amount',  fn ($row) => '<span class="paid_amount" data-value="' . $row->paid_amount . '">' . $this->converter->format_in_bdt($row->paid_amount) . '</span>')
                ->rawColumns(['date', 'paid_amount'])
                ->make(true);
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        return view('reports.purchase_payment_report.index', compact('branches', 'suppliers'));
    }

    public function print(Request $request)
    {
        $payments = '';
        $branch_id = $request->branch_id;
        $fromDate = '';
        $toDate = '';
        $query = DB::table('purchase_payments')
            ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->join('suppliers', 'purchases.supplier_id', 'suppliers.id');

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('purchases.branch_id', NULL);
            } else {
                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchase_payments.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $payments = $query->select(
                'purchase_payments.id as payment_id',
                'purchase_payments.invoice_id as payment_invoice',
                'purchase_payments.paid_amount',
                'purchase_payments.pay_mode',
                'purchase_payments.date',
                'purchases.invoice_id as purchase_invoice',
                'suppliers.name as supplier_name',
            )->orderBy('purchase_payments.report_date', 'desc')->get();
        } else {
            $payments = $query->select(
                'purchase_payments.id as payment_id',
                'purchase_payments.invoice_id as payment_invoice',
                'purchase_payments.paid_amount',
                'purchase_payments.pay_mode',
                'purchase_payments.date',
                'purchases.invoice_id as purchase_invoice',
                'suppliers.name as supplier_name',
            )->where('purchases.branch_id', auth()->user()->branch_id)
                ->orderBy('purchase_payments.report_date', 'desc')
                ->get();
        }

        return view('reports.purchase_payment_report.ajax_view.print', compact('payments', 'fromDate', 'toDate', 'branch_id'));
    }
}
