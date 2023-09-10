<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\PurchaseSaleChainUtil;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockBranchToBranch;
use App\Models\TransferStockBranchToBranchProducts;

class ReceiveTransferBranchToBranchController extends Controller
{
    protected $converter;
    protected $productStockUtil;
    protected $purchaseSaleChainUtil;
    public function __construct(
        Converter $converter,
        ProductStockUtil $productStockUtil,
        PurchaseSaleChainUtil $purchaseSaleChainUtil
    ) {
        $this->converter = $converter;
        $this->productStockUtil = $productStockUtil;
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function receivableList(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('id', 'business')->first();

            $receivables = '';

            $query = DB::table('transfer_stock_branch_to_branches')
                ->leftJoin('branches as sender_branch', 'transfer_stock_branch_to_branches.sender_branch_id', 'sender_branch.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('transfer_stock_branch_to_branches.sender_branch_id', NULL);
                } else {

                    $query->where('transfer_stock_branch_to_branches.sender_branch_id', $request->branch_id);
                }
            }

            if ($request->receive_status) {

                $query->where('transfer_stock_branch_to_branches.receive_status', $request->receive_status);
            }

            $query->select(
                'transfer_stock_branch_to_branches.*',
                'sender_branch.name as sender_branch_name',
                'sender_branch.branch_code as sender_branch_code',
            );

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('transfer_stock_branch_to_branches.report_date', $date_range); // Final
            }

            $receivables = $query
                ->where('transfer_stock_branch_to_branches.receiver_branch_id', auth()->user()->branch_id)
                ->orderBy('transfer_stock_branch_to_branches.report_date', 'desc');

            return DataTables::of($receivables)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('transfer.stock.branch.to.branch.receivable.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';
                        $html .= '<a class="dropdown-item" href="' . route('transfer.stock.branch.to.branch.ProcessToReceive', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("Process To Receive").'</a>';
                        $html .= '<a class="dropdown-item" id="send_notification" href="#"><i class="fas fa-envelope text-primary"></i> '.__("Send Notification").'</a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('sender_branch',  function ($row) use ($generalSettings) {

                    if ($row->sender_branch_name) {

                        return $row->sender_branch_name . '/' . $row->sender_branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . $this->converter->format_in_bdt($row->total_item) . '</span>')

                ->editColumn('total_send_qty', fn ($row) => '<span class="total_send_qty" data-value="' . $row->total_send_qty . '">' . $this->converter->format_in_bdt($row->total_send_qty) . '</span>')

                ->editColumn('total_received_qty', fn ($row) => '<span class="total_received_qty text-success" data-value="' . $row->total_received_qty . '">' . $this->converter->format_in_bdt($row->total_received_qty) . '</span>')

                ->editColumn('total_pending_qty', fn ($row) =>  '<span class="total_pending_qty text-danger" data-value="' . $row->total_pending_qty . '">' . $this->converter->format_in_bdt($row->total_pending_qty) . '</span>')

                ->editColumn('total_stock_value', fn ($row) => '<span class="total_stock_value" data-value="' . $row->total_stock_value . '">' . $this->converter->format_in_bdt($row->total_stock_value) . '</span>')

                ->editColumn('receive_status', function ($row) {

                    if ($row->receive_status == 1) {

                        return '<span class="badge bg-danger">Pending</span>';
                    } else if ($row->receive_status == 2) {

                        return '<span class="badge bg-warning text-white">Partial</span>';
                    } else if ($row->receive_status == 3) {

                        return '<span class="badge bg-success">Completed</span>';
                    }
                })

                ->rawColumns(['action', 'date', 'sender_branch', 'total_item', 'total_send_qty', 'total_received_qty', 'total_pending_qty', 'total_stock_value', 'receive_status'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.receive.receivable_list',

            compact('branches')
        );
    }

    public function show($transferId)
    {
        $transfer = TransferStockBranchToBranch::with(
            [
                'sender_branch',
                'sender_warehouse',
                'receiver_branch',
                'receiver_branch',
                'Transfer_products',
                'Transfer_products.product',
                'Transfer_products.variant',
                'Transfer_products.product.unit'
            ]
        )->where('id', $transferId)->first();

        return view('transfer_stock.branch_to_branch.receive.ajax_view.show', compact('transfer'));
    }

    public function ProcessToReceive($transferId)
    {
        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $transfer = TransferStockBranchToBranch::with(
            [
                'sender_branch',
                'receiver_branch',
                'transfer_products',
                'transfer_products.product',
                'transfer_products.product.unit',
                'transfer_products.variant',
            ]
        )
            ->where('id', $transferId)->first();

        return view(

            'transfer_stock.branch_to_branch.receive.process_to_receive',

            compact('warehouses', 'transfer')
        );
    }

    public function processToReceiveSave(Request $request, $transferId)
    {
        //return $request->all();
        $transfer = TransferStockBranchToBranch::where('id', $transferId)->first();

        $previousReceiverWarehouseId = $transfer->receiver_warehouse_id;
        $previousSenderWarehouseId = $transfer->sender_warehouse_id;

        $transfer->total_received_qty = $request->total_received_quantity;
        $transfer->total_pending_qty = $request->total_pending_quantity;
        $transfer->receiver_warehouse_id = $request->receiver_warehouse_id;

        $status = 0;
        if ($request->total_received_quantity == 0) {

            $status = 1;
        } elseif (

            $request->total_received_quantity > 0 &&
            $transfer->total_send_qty == $request->total_received_quantity
        ) {

            $status = 3;
        } elseif (
            $request->total_received_quantity > 0 &&
            $request->total_received_quantity < $transfer->total_send_qty
        ) {

            $status = 2;
        }

        $transfer->receive_status = $status;
        $transfer->receiver_note = $request->receiver_note;
        $transfer->save();

        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$index] != 'no_id' ?  $request->variant_ids[$index] : NULL;

            $this->productStockUtil->addBranchProduct(
                product_id: $product_id,
                variant_id: $variant_id,
                branch_id: $transfer->receiver_branch_id,
                force_add: 1
            );

            $updateTransferProduct = TransferStockBranchToBranchProducts::where('transfer_id', $transfer->id)
                ->where('product_id', $product_id)
                ->where('variant_id', $variant_id)
                ->first();

            $updateTransferProduct->pending_qty = $request->pending_quantities[$index];
            $updateTransferProduct->received_qty = $request->received_quantities[$index];
            $updateTransferProduct->save();

            if ($transfer->receiver_warehouse_id) {

                $this->productStockUtil->addWarehouseProduct($product_id, $variant_id, $transfer->receiver_warehouse_id);

                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $transfer->receiver_warehouse_id);
            }

            if ($transfer->sender_warehouse_id) {

                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $transfer->sender_warehouse_id);
            }

            if ($previousReceiverWarehouseId != $transfer->receiver_warehouse_id && $previousReceiverWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $previousReceiverWarehouseId);
            }

            // Adjust Sender Business Location Stock
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $transfer->sender_branch_id);

            // Adjust Receiver Business Location Stock
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $transfer->receiver_branch_id);

            $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                tranColName : 'transfer_branch_to_branch_product_id',
                transId : $updateTransferProduct->id,
                branchId : $transfer->receiver_branch_id,
                productId : $updateTransferProduct->product_id,
                quantity : $updateTransferProduct->received_qty,
                variantId : $updateTransferProduct->variant_id,
                unitCostIncTax : $updateTransferProduct->unit_cost_inc_tax,
                sellingPrice : $updateTransferProduct->unit_price_inc_tax,
                subTotal : $updateTransferProduct->subtotal,
                createdAt : date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
            );

            $index++;
        }

        session()->flash('successMsg', 'Successfully receiving has been processed');
        return response()->json(['successMsg' => 'Successfully receiving has been processed']);
    }
}
