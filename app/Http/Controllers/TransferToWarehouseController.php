<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Utils\Converter;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\TransferStockToWarehouse;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockToWarehouseProduct;
use App\Utils\UserActivityLogUtil;

class TransferToWarehouseController extends Controller
{
    protected $nameSearchUtil;
    protected $productStockUtil;
    protected $converter;
    protected $userActivityLogUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        ProductStockUtil $productStockUtil,
        Converter $converter,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->productStockUtil = $productStockUtil;
        $this->converter = $converter;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Transfer stock to branch
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $transfers = DB::table('transfer_stock_to_warehouses')
                ->leftJoin('warehouses', 'transfer_stock_to_warehouses.warehouse_id', 'warehouses.id')
                ->leftJoin('branches', 'transfer_stock_to_warehouses.branch_id', 'branches.id')->select(
                    'transfer_stock_to_warehouses.*',
                    'warehouses.warehouse_name as to_name',
                    'warehouses.warehouse_code as to_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id)
                ->orderBy('transfer_stock_to_warehouses.report_date', 'desc');

            return DataTables::of($transfers)

                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__("Action").' </button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('transfer.stock.to.warehouse.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i>'.__("View").' </a>';
                        $html .= '<a class="dropdown-item" href="' . route('transfer.stock.to.warehouse.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('transfer.stock.to.warehouse.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> '.__("Delete").'</a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })

                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return  $row->branch_name . '/' . $row->branch_code;
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('to_name',  function ($row) {

                    return  $row->to_name . '/' . $row->to_code;
                })

                ->editColumn('shipping_charge', fn ($row) => $this->converter->format_in_bdt($row->shipping_charge))

                ->editColumn('net_total_amount', fn ($row) => $this->converter->format_in_bdt($row->net_total_amount))

                ->editColumn('status', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="badge bg-danger">Pending</span>';
                    } else if ($row->status == 2) {

                        return '<span class="badge bg-warning text-white">Partial</span>';
                    } else if ($row->status == 3) {

                        return '<span class="badge bg-success">Completed</span>';
                    }
                })
                ->rawColumns(['action', 'date', 'from', 'to_name', 'shipping_charge', 'net_total_amount', 'status'])
                ->make(true);
        }
        return view('transfer_stock.branch_to_warehouse.index');
    }

    public function show($id)
    {
        $transfer = TransferStockToWarehouse::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')
            ->where('id', $id)
            ->first();

        return view('transfer_stock.branch_to_warehouse.ajax_view.show', compact('transfer'));
    }

    // Add transfer stock to branch create view
    public function create()
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

        return view('transfer_stock.branch_to_warehouse.create', compact('warehouses'));
    }

    // Store transfer products form warehouse to branch
    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'date' => 'required',
        ]);

        $invoiceId = 1;
        $lastTransfer = DB::table('transfer_stock_to_warehouses')->orderBy('id', 'desc')->first();

        if ($lastTransfer) {

            $invoiceId = ++$lastTransfer->id;
        }

        $addTransferToWarehouse = new TransferStockToWarehouse();
        $addTransferToWarehouse->invoice_id = $request->invoice_id ? $request->invoice_id : 'TW' . $invoiceId;
        $addTransferToWarehouse->warehouse_id = $request->warehouse_id;
        $addTransferToWarehouse->branch_id = $request->branch_id;
        $addTransferToWarehouse->status = 1;
        $addTransferToWarehouse->total_item = $request->total_item;
        $addTransferToWarehouse->total_send_qty = $request->total_send_quantity;
        $addTransferToWarehouse->net_total_amount = $request->net_total_amount;
        $addTransferToWarehouse->shipping_charge = $request->shipping_charge;
        $addTransferToWarehouse->date = $request->date;
        $addTransferToWarehouse->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addTransferToWarehouse->month = date('F');
        $addTransferToWarehouse->year = date('Y');
        $addTransferToWarehouse->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach ($product_ids as $product_id) {

            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;
            $addTransferStockToWarehouseProduct = new TransferStockToWarehouseProduct();
            $addTransferStockToWarehouseProduct->transfer_stock_id = $addTransferToWarehouse->id;
            $addTransferStockToWarehouseProduct->product_id = $product_id;
            $addTransferStockToWarehouseProduct->product_variant_id = $variant_id;
            $addTransferStockToWarehouseProduct->unit = $units[$index2];
            $addTransferStockToWarehouseProduct->unit_price = $unit_prices[$index2];
            $addTransferStockToWarehouseProduct->quantity = $quantities[$index2];
            $addTransferStockToWarehouseProduct->subtotal = $subtotals[$index2];
            $addTransferStockToWarehouseProduct->save();
            $index2++;
        }

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 10,
            data_obj: $addTransferToWarehouse
        );

        if ($request->action == 'save') {

            session()->flash('successMsg', ' Transfer stock created successfully');
            return response()->json(['successMsg' => 'Successfully transfer stock is added']);
        } else {

            $transfer = TransferStockToWarehouse::with('branch', 'warehouse', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')
                ->where('id', $addTransferToWarehouse->id)->first();
            return view('transfer_stock.branch_to_warehouse.save_and_print_template.print', compact('transfer'));
        }
    }

    // Transfer stock edit view
    public function edit($transferId)
    {
        $transferId = $transferId;

        $transfer = DB::table('transfer_stock_to_warehouses')->where('id', $transferId)->select('id', 'warehouse_id', 'date')->first();

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        return view('transfer_stock.branch_to_warehouse.edit', compact('transferId', 'transfer', 'warehouses'));
    }

    // Get editable transfer **requested by ajax
    public function editableTransfer($transferId)
    {
        $transfer = TransferStockToWarehouse::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $transferId)->first();
        $qty_limits = [];

        foreach ($transfer->Transfer_products as $transfer_product) {

            $productBranch = ProductBranch::where('branch_id', $transfer->branch_id)
                ->where('product_id', $transfer_product->product_id)->first();

            if ($transfer_product->product_variant_id) {

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                    ->where('product_id', $transfer_product->product_id)
                    ->where('product_variant_id', $transfer_product->product_variant_id)
                    ->first();
                $qty_limits[] = $productBranchVariant->variant_quantity;
            } else {

                $qty_limits[] = $productBranch->product_quantity;
            }
        }

        return response()->json(['transfer' => $transfer, 'qty_limits' => $qty_limits]);
    }

    // Update Transfer to branch
    public function update(Request $request, $transferId)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'date' => 'required',
        ]);

        $invoiceId = 1;
        $lastTransfer = DB::table('transfer_stock_to_warehouses')->orderBy('id', 'desc')->first();

        if ($lastTransfer) {

            $invoiceId = ++$lastTransfer->id;
        }

        $updateTransferToWarehouse = TransferStockToWarehouse::with('transfer_products')
            ->where('id', $transferId)
            ->first();

        // Update is delete in update status
        foreach ($updateTransferToWarehouse->transfer_products as $transfer_product) {

            $transfer_product->is_delete_in_update = 1;
            $transfer_product->save();
        }

        $updateTransferToWarehouse->invoice_id = $request->invoice_id ? $request->invoice_id : 'TW' . date('my') . $invoiceId;
        $updateTransferToWarehouse->warehouse_id = $request->warehouse_id;
        $updateTransferToWarehouse->branch_id = $request->branch_id;
        $updateTransferToWarehouse->total_item = $request->total_item;
        $updateTransferToWarehouse->total_send_qty = $request->total_send_quantity;

        if (
            $request->total_send_quantity == $updateTransferToWarehouse->total_received_qty
        ) {

            $updateTransferToWarehouse->status = 3;
        } elseif (
            $updateTransferToWarehouse->total_received_qty > 0 && $updateTransferToWarehouse->total_received_qty < $request->total_send_quantity
        ) {

            $updateTransferToWarehouse->status = 2;
        } elseif ($updateTransferToWarehouse->total_received_qty == 0) {

            $updateTransferToWarehouse->status = 1;
        }

        $updateTransferToWarehouse->net_total_amount = $request->net_total_amount;
        $updateTransferToWarehouse->shipping_charge = $request->shipping_charge;
        $updateTransferToWarehouse->additional_note = $request->additional_note;
        $updateTransferToWarehouse->date = $request->date;
        $updateTransferToWarehouse->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateTransferToWarehouse->month = date('F');
        $updateTransferToWarehouse->year = date('Y');
        $updateTransferToWarehouse->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach ($product_ids as $product_id) {

            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;

            $transferProduct = TransferStockToWarehouseProduct::where('transfer_stock_id', $updateTransferToWarehouse->id)->where('product_id')
                ->where('product_variant_id', $variant_id)
                ->first();

            if ($transferProduct) {

                $transferProduct->quantity = $quantities[$index2];
                $transferProduct->subtotal = $subtotals[$index2];
                $transferProduct->is_delete_in_update = 0;
                $transferProduct->save();
            } else {

                $addTransferStockToBranchProduct = new TransferStockToWarehouseProduct();
                $addTransferStockToBranchProduct->transfer_stock_id = $updateTransferToWarehouse->id;
                $addTransferStockToBranchProduct->product_id = $product_id;
                $addTransferStockToBranchProduct->product_variant_id = $variant_id;
                $addTransferStockToBranchProduct->unit = $units[$index2];
                $addTransferStockToBranchProduct->unit_price = $unit_prices[$index2];
                $addTransferStockToBranchProduct->quantity = $quantities[$index2];
                $addTransferStockToBranchProduct->subtotal = $subtotals[$index2];
                $addTransferStockToBranchProduct->save();
            }

            $index2++;
        }

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 10,
            data_obj: $updateTransferToWarehouse
        );

        // Delete not found which was previous
        $delectableTransferProducts = TransferStockToWarehouseProduct::where('transfer_stock_id', $transferId)->where('is_delete_in_update', 1)->get();

        foreach ($delectableTransferProducts as $delectableTransferProduct) {

            $delectableTransferProduct->delete();
        }

        return response()->json(['successMsg' => 'Transfer stock updated successfully']);
    }

    // delete transfer
    public function delete($transferId)
    {
        $deleteTransferToWarehouse = TransferStockToWarehouse::with('transfer_products')
            ->where('id', $transferId)
            ->first();

        if (!is_null($deleteTransferToWarehouse)) {

            $this->userActivityLogUtil->addLog(
                action: 3,
                subject_type: 10,
                data_obj: $deleteTransferToWarehouse
            );

            $storedTransferredProducts = $deleteTransferToWarehouse->transfer_products;
            $storedBranchId = $deleteTransferToWarehouse->branch_id;
            $storedWarehouseId = $deleteTransferToWarehouse->warehouse_id;

            $deleteTransferToWarehouse->delete();

            foreach ($storedTransferredProducts as $transfer_product) {

                $this->productStockUtil->adjustWarehouseStock($transfer_product->product_id, $transfer_product->product_variant_id, $storedWarehouseId);
                $this->productStockUtil->adjustBranchStock($transfer_product->product_id, $transfer_product->product_variant_id, $storedBranchId);
            }
        }

        return response()->json('Successfully transfer stock is deleted');
    }

    public function productSearch($product_code)
    {
        $product_code = (string)$product_code;
        $__product_code = str_replace('~', '/', $product_code);
        $branch_id = auth()->user()->branch_id;

        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_code', $__product_code)
            ->select([
                'id',
                'name',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
            ])->first();

        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', $branch_id)
                ->where('product_id', $product->id)
                ->where('status', 1)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productBranch->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product in this Business Location']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in the Business Location.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $__product_code)
                ->select([
                    'id', 'product_id', 'variant_name', 'variant_code', 'variant_quantity', 'variant_cost', 'variant_cost_with_tax', 'variant_profit', 'variant_price'
                ])->first();

            if ($variant_product) {

                if ($variant_product) {

                    $productBranch = DB::table('product_branches')
                        ->where('branch_id', $branch_id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('status', 1)
                        ->first();

                    if (is_null($productBranch)) {

                        return response()->json(['errorMsg' => 'This product is not available in the Business Location']);
                    }

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productBranchVariant)) {

                        return response()->json(['errorMsg' => 'This variant is not available in the Business Location']);
                    }

                    if ($productBranch && $productBranchVariant) {

                        if ($productBranchVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant->variant_quantity
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this branch']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'This product is not available in this branch.']);
                    }
                }
            }
        }

        return $this->nameSearchUtil->nameSearching($__product_code);
    }

    public function checkBranchSingleProduct($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        $productBranch = DB::table('product_branches')->where('product_id', $product_id)->where('branch_id', $branch_id)->first();
        if ($productBranch) {

            if ($productBranch->product_quantity > 0) {

                return response()->json($productBranch->product_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of the Business Location']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in the Business Location.']);
        }
    }

    // Check branch product variant qty
    public function checkBranchProductVariant($product_id, $variant_id)
    {
        $branch_id = auth()->user()->branch_id;
        $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product_id)->first();
        if ($productBranch) {
            $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();
            if ($productBranchVariant) {

                if ($productBranchVariant->variant_quantity > 0) {

                    return response()->json($productBranchVariant->variant_quantity);
                } else {

                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) of the Business Location']);
                }
            } else {

                return response()->json(['errorMsg' => 'This variant is not available in the Business Location.']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in the Business Location.']);
        }
    }

    // Get all warehouse requested by ajax
    public function getAllWarehouse()
    {
        $warehouses = Warehouse::select('id', 'warehouse_name', 'warehouse_code')->get();
        return response()->json($warehouses);
    }
}
