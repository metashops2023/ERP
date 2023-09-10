<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Models\TransferStockToBranch;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockToBranchProduct;
use App\Jobs\BranchReceiveStockDetailsMailJob;

class BranchReceiveStockController extends Controller
{
    protected $productStockUtil;
    public function __construct(ProductStockUtil $productStockUtil,)
    {
        $this->productStockUtil = $productStockUtil;
        $this->middleware('auth:admin_and_user');
    }

    //Branch receiving stock index view
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $transfers = DB::table('transfer_stock_to_branches')
                ->leftJoin('warehouses', 'transfer_stock_to_branches.warehouse_id', 'warehouses.id')
                ->leftJoin('branches', 'transfer_stock_to_branches.branch_id', 'branches.id')->select(
                    'transfer_stock_to_branches.*',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->where('transfer_stock_to_branches.branch_id', auth()->user()->branch_id)
                ->orderBy('transfer_stock_to_branches.report_date', 'desc')
                ->get();

            return DataTables::of($transfers)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('transfer.stocks.to.warehouse.receive.stock.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> '.__("View").'</a>';
                        $html .= '<a class="dropdown-item" href="' . route('transfer.stocks.to.warehouse.receive.stock.process.view', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i>'.__("Process To Receive").'</a>';
                        $html .= '<a class="dropdown-item" id="send_mail" href="' . route('transfer.stocks.to.warehouse.receive.stock.mail', $row->id) . '"><i class="fas fa-envelope mr-1 text-primary"></i>'.__("Send Receive Details Via Email").' </a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) {

                    return  $row->warehouse_name . '/' . $row->warehouse_code;
                })
                ->editColumn('to',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return  $row->branch_name . '/' . $row->branch_code;
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('status', function ($row) {

                    $html = '';
                    if ($row->status == 1) {

                        $html .= '<span class="badge bg-danger">Pending</span>';
                    } else if ($row->status == 2) {

                        $html .= '<span class="badge bg-warning text-white">Partial</span>';
                    } else if ($row->status == 3) {

                        $html .= '<span class="badge bg-success">Completed</span>';
                    }
                    return $html;
                })
                ->rawColumns(['date', 'from', 'to', 'status', 'action'])
                ->make(true);
        }

        $users = DB::table('admin_and_users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name', 'email']);

        return view('transfer_stock.branch_to_warehouse.receive_stock.index', compact('users'));
    }

    public function show($sendStockId)
    {
        $sendStock = TransferStockToBranch::with(['warehouse', 'branch', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])->where('id', $sendStockId)->first();
        return view('transfer_stock.branch_to_warehouse.receive_stock.ajax_view.show', compact('sendStock'));
    }

    public function receiveProducessView($sendStockId)
    {
        $sendStockId = $sendStockId;
        return view('transfer_stock.branch_to_warehouse.receive_stock.product_receive_stock_view', compact('sendStockId'));
    }

    public function receivableStock($sendStockId)
    {
        $sandStocks = TransferStockToBranch::with(['warehouse', 'branch', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])
            ->where('id', $sendStockId)->first();

        return response()->json($sandStocks);
    }

    public function receiveProcessSave(Request $request, $sendStockId)
    {
        $updateSandStocks = TransferStockToBranch::where('id', $sendStockId)->first();
        $updateSandStocks->total_received_qty = $request->total_received_quantity;

        $status = 0;
        if ($request->total_received_quantity == 0) {

            $status = 1;
        } elseif (

            $request->total_received_quantity > 0 &&
            $updateSandStocks->total_send_qty == $request->total_received_quantity
        ) {

            $status = 3;
        } elseif (
            $request->total_received_quantity > 0 &&
            $request->total_received_quantity < $updateSandStocks->total_send_qty
        ) {

            $status = 2;
        }

        $updateSandStocks->status = $status;
        $updateSandStocks->receiver_note = $request->receiver_note;
        $updateSandStocks->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $receive_quantities = $request->receive_quantities;

        $index = 0;
        foreach ($product_ids as $product_id) {

            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updateTransferProduct = TransferStockToBranchProduct::where('transfer_stock_id', $updateSandStocks->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();

            $updateTransferProduct->received_qty = $receive_quantities[$index];
            $updateTransferProduct->save();

            $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $updateSandStocks->warehouse_id);

            $this->productStockUtil->addBranchProduct($product_id, $variant_id, $updateSandStocks->branch_id);
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $updateSandStocks->branch_id);

            $index++;
        }

        session()->flash('successMsg', 'Successfully receiving has been has been processed');
        return response()->json(['successMsg' => 'Successfully receiving has been has been processed']);
    }

    // Send Receive branch stock details via email
    public function receiveMail(Request $request, $sendStockId)
    {
        $this->validate($request, [
            'user_email' => 'required',
        ]);

        $transfer = TransferStockToBranch::with([
            'warehouse', 'branch', 'transfer_products',
            'transfer_products.product', 'transfer_products.variant'
        ])->where('id', $sendStockId)->first();

        BranchReceiveStockDetailsMailJob::dispatch($request->user_email, $request->mail_note, $transfer)
            ->delay(now()->addSeconds(5));

        return response()->json('Successfully mail is send.');
    }
}
