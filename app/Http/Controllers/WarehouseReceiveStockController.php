<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Models\TransferStockToWarehouse;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockToWarehouseProduct;

class WarehouseReceiveStockController extends Controller
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
            $transfers = DB::table('transfer_stock_to_warehouses')
                ->leftJoin('warehouses', 'transfer_stock_to_warehouses.warehouse_id', 'warehouses.id')
                ->leftJoin('branches', 'transfer_stock_to_warehouses.branch_id', 'branches.id')->select(
                    'transfer_stock_to_warehouses.*',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->orderBy('transfer_stock_to_warehouses.report_date', 'desc')
                ->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id);

            return DataTables::of($transfers)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('transfer.stocks.to.warehouse.receive.stock.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';
                        $html .= '<a class="dropdown-item" href="' . route('transfer.stocks.to.branch.receive.stock.process.view', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Process To Receive").'</a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code;
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('to',  function ($row) {

                    return $row->warehouse_name . '/' . $row->warehouse_code;
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
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('transfer.stocks.to.branch.receive.stock.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row text-start')
                ->rawColumns(['date', 'from', 'to', 'status', 'action'])
                ->make(true);
        }
        return view('transfer_stock.warehouse_to_branch.receive_stock.index');
    }

    public function show($sendStockId)
    {
        $sendStock = TransferStockToWarehouse::with(['warehouse', 'branch', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])->where('id', $sendStockId)->first();
        return view('transfer_stock.warehouse_to_branch.receive_stock.ajax_view.show', compact('sendStock'));
    }


    public function receiveProducessView($sendStockId)
    {
        $sendStockId = $sendStockId;
        return view('transfer_stock.warehouse_to_branch.receive_stock.product_receive_stock_view', compact('sendStockId'));
    }

    public function receivableStock($sendStockId)
    {
        $sandStocks = TransferStockToWarehouse::with(['branch', 'warehouse', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])
            ->where('id', $sendStockId)->first();

        return response()->json($sandStocks);
    }

    public function receiveProcessSave(Request $request, $sendStockId)
    {
        $updateSandStocks = TransferStockToWarehouse::where('id', $sendStockId)->first();
        $updateSandStocks->total_received_qty = $request->total_received_quantity;

        $status = 0;
        if ($request->total_received_quantity == 0) {

            $status = 1;
        } elseif ($request->total_received_quantity > 0 && $updateSandStocks->total_send_qty == $request->total_received_quantity) {

            $status = 3;
        } elseif ($request->total_received_quantity > 0 && $request->total_received_quantity < $updateSandStocks->total_send_qty) {

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
            $updateTransferProduct = TransferStockToWarehouseProduct::where('transfer_stock_id', $updateSandStocks->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            $updateTransferProduct->received_qty = $receive_quantities[$index];
            $updateTransferProduct->save();

            $this->productStockUtil->addWarehouseProduct($product_id, $variant_id, $updateSandStocks->warehouse_id);
            $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $updateSandStocks->warehouse_id);
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $updateSandStocks->branch_id);

            $index++;
        }

        session()->flash('successMsg', 'Successfully receiving has been has been processed');
        return response()->json(['successMsg' => 'Successfully receiving has been has been processed']);
    }
}
