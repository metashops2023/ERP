<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Utils\TransferStockUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockBranchToBranch;
use App\Models\TransferStockBranchToBranchProducts;
use App\Utils\Converter;
use App\Utils\UserActivityLogUtil;

class TransferStockBranchToBranchController extends Controller
{
    protected $nameSearchUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $transferStockUtil;
    protected $converter;
    protected $userActivityLogUtil;

    public function __construct(
        NameSearchUtil $nameSearchUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        TransferStockUtil $transferStockUtil,
        Converter $converter,
        UserActivityLogUtil $userActivityLogUtil
    ) {

        $this->nameSearchUtil = $nameSearchUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->transferStockUtil = $transferStockUtil;
        $this->converter = $converter;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function transferList(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->select('id', 'business')->first();

            $transfers = '';

            $query = DB::table('transfer_stock_branch_to_branches')
                ->leftJoin('branches as sender_branch', 'transfer_stock_branch_to_branches.sender_branch_id', 'sender_branch.id')
                ->leftJoin('branches as receiver_branch', 'transfer_stock_branch_to_branches.receiver_branch_id', 'receiver_branch.id');

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
                'receiver_branch.name as receiver_branch_name',
                'receiver_branch.branch_code as receiver_branch_code',
            );

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('transfer_stock_branch_to_branches.report_date', $date_range); // Final
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $transfers = $query->orderBy('transfer_stock_branch_to_branches.report_date', 'desc');
            } else {

                $transfers = $query->orderBy('transfer_stock_branch_to_branches.report_date', 'desc')
                    ->where('transfer_stock_branch_to_branches.sender_branch_id', auth()->user()->branch_id);
            }

            return DataTables::of($transfers)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('transfer.stock.branch.to.branch.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';

                        if (auth()->user()->branch_id == $row->sender_branch_id) {

                            $html .= '<a class="dropdown-item" href="' . route('transfer.stock.branch.to.branch.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('transfer.stock.branch.to.branch.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i>'.__("Delete").' </a>';
                        }

                        $html .= '<a class="dropdown-item" id="send_notification" href="#"><i class="fas fa-envelope text-primary"></i>'.__("Send Notification").' </a>';



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
                ->editColumn('receiver_branch',  function ($row) use ($generalSettings) {

                    if ($row->receiver_branch_name) {

                        return $row->receiver_branch_name . '/' . $row->receiver_branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })

                ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . $this->converter->format_in_bdt($row->total_item) . '</span>')

                ->editColumn('total_send_qty', fn ($row) => '<span class="total_send_qty" data-value="' . $row->total_send_qty . '">' . $this->converter->format_in_bdt($row->total_send_qty) . '</span>')

                ->editColumn('total_received_qty', fn ($row) => '<span class="total_received_qty text-success" data-value="' . $row->total_received_qty . '">' . $this->converter->format_in_bdt($row->total_received_qty) . '</span>')

                ->editColumn('total_pending_qty', fn ($row) =>  '<span class="total_pending_qty text-danger" data-value="' . $row->total_pending_qty . '">' . $this->converter->format_in_bdt($row->total_pending_qty) . '</span>')

                ->editColumn('transfer_cost', fn ($row) => '<span class="transfer_cost" data-value="' . $row->transfer_cost . '">' . $this->converter->format_in_bdt($row->transfer_cost) . '</span>')

                ->editColumn('receive_status', function ($row) {

                    if ($row->receive_status == 1) {

                        return '<span class="badge bg-danger">Pending</span>';
                    } else if ($row->receive_status == 2) {

                        return '<span class="badge bg-warning text-white">Partial</span>';
                    } else if ($row->receive_status == 3) {

                        return '<span class="badge bg-success">Completed</span>';
                    }
                })

                ->rawColumns(['action', 'date', 'sender_branch', 'receiver_branch', 'total_item', 'total_send_qty', 'total_received_qty', 'total_pending_qty', 'transfer_cost', 'receive_status'])
                ->make(true);
        }

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.transfer_list',

            compact('warehouses', 'branches')
        );
    }

    public function create()
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('accounts.account_type', [7, 8, 9, 10, 15])
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        $methods = DB::table('payment_methods')
            ->select('id', 'name')->get();

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $branches = DB::table('branches')
            ->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.create',

            compact('warehouses', 'accounts', 'expenseAccounts', 'methods', 'branches')
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'account_id' => 'required',
            'payment_method_id' => 'required',
            'receiver_branch_id' => 'required',
        ], [
            'ex_account_id.required' => 'Expense ledger A/C is required',
            'account_id.required' => 'Credit A/C is required',
            'receiver_branch_id.required' => 'Receive from field is required',
        ]);

        $receiver_branch_id = $request->receiver_branch_id != 'NULL' ? $request->receiver_branch_id : NULL;

        if ($receiver_branch_id == $request->sender_branch_id) {

            return response()->json(['errorMsg' => 'Sender Business Location and Receiver Business Location must not be same.']);
        }

        $refId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('transfer_stock_branch_to_branches'), 5, "0", STR_PAD_LEFT);

        $addTransfer = new TransferStockBranchToBranch();
        $addTransfer->ref_id = 'TBB' . $refId;
        $addTransfer->sender_branch_id = $request->sender_branch_id;
        $addTransfer->sender_warehouse_id = $request->sender_warehouse_id;
        $addTransfer->receiver_branch_id = $receiver_branch_id;
        $addTransfer->date = $request->date;
        $addTransfer->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addTransfer->total_item = $request->total_item;
        $addTransfer->total_send_qty = $request->total_send_qty;
        $addTransfer->total_pending_qty = $request->total_send_qty;
        $addTransfer->total_stock_value = $request->total_stock_value;
        $addTransfer->transfer_note = $request->transfer_note;
        $addTransfer->expense_account_id = $request->ex_account_id;
        $addTransfer->bank_account_id = $request->account_id;
        $addTransfer->payment_method_id = $request->payment_method_id;
        $addTransfer->payment_note = $request->payment_note;
        $addTransfer->transfer_cost = $request->transfer_cost;
        $addTransfer->save();

        if (count($request->product_ids) > 0) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;

                $addTransProduct = new TransferStockBranchToBranchProducts();
                $addTransProduct->transfer_id = $addTransfer->id;
                $addTransProduct->product_id = $product_id;
                $addTransProduct->variant_id = $variant_id;
                $addTransProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addTransProduct->send_qty = $request->quantities[$index];
                $addTransProduct->pending_qty = $request->quantities[$index];
                $addTransProduct->subtotal = $request->subtotals[$index];
                $addTransProduct->save();
                $index++;
            }
        }

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 12,
            data_obj: $addTransfer
        );

        if ($request->transfer_cost > 0) {

            $this->transferStockUtil->addExpenseFromTransferStock($request, $addTransfer->id);
        }

        if ($request->action == 'save') {

            return response()->json(['successMsg' => 'Successfully transfer stock is added']);
        } else {

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
            )->where('id', $addTransfer->id)->first();

            return view('transfer_stock.branch_to_branch.save_and_print_template.print', compact('transfer'));
        }
    }

    public function edit($transferId)
    {
        $transfer = TransferStockBranchToBranch::with(
            [
                'transfer_products',
                'transfer_products.product',
                'transfer_products.product.unit',
                'transfer_products.product.tax',
                'transfer_products.variant',
            ]
        )->where('id', $transferId)->first();

        $qty_limits = $this->transferStockUtil->getStockLimitProducts($transfer);

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('accounts.account_type', [7, 8, 9, 10, 15])
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_type']);

        $methods = DB::table('payment_methods')
            ->select('id', 'name')->get();

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $branches = DB::table('branches')
            ->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.edit',

            compact('transfer', 'warehouses', 'accounts', 'expenseAccounts', 'methods', 'branches', 'qty_limits')
        );
    }

    public function update(Request $request, $transferId)
    {
        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'account_id' => 'required',
            'payment_method_id' => 'required',
            'receiver_branch_id' => 'required',
        ], [
            'ex_account_id.required' => 'Expense ledger A/C is required',
            'account_id.required' => 'Credit A/C is required',
            'receiver_branch_id.required' => 'Receive from field is required',
        ]);

        $receiver_branch_id = $request->receiver_branch_id != 'NULL' ? $request->receiver_branch_id : NULL;

        $refId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('transfer_stock_branch_to_branches'), 5, "0", STR_PAD_LEFT);

        $updateTransfer = TransferStockBranchToBranch::with('transfer_products', 'expense')
            ->where('id', $transferId)->first();

        $updateTransfer->ref_id = $request->ref_id ? $request->ref_id : 'TBB' . $refId;
        $updateTransfer->sender_branch_id = $request->sender_branch_id;
        $updateTransfer->sender_warehouse_id = $request->sender_warehouse_id;
        $updateTransfer->receiver_branch_id = $receiver_branch_id;
        $updateTransfer->date = $request->date;
        $updateTransfer->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateTransfer->total_item = $request->total_item;
        $updateTransfer->total_send_qty = $request->total_send_qty;
        $updateTransfer->total_pending_qty = $request->total_send_qty;
        $updateTransfer->total_stock_value = $request->total_stock_value;
        $updateTransfer->transfer_note = $request->transfer_note;
        $updateTransfer->expense_account_id = $request->ex_account_id;
        $updateTransfer->bank_account_id = $request->account_id;
        $updateTransfer->payment_method_id = $request->payment_method_id;
        $updateTransfer->payment_note = $request->payment_note;
        $updateTransfer->transfer_cost = $request->transfer_cost ? $request->transfer_cost : 0;

        if ($request->total_send_qty == $updateTransfer->total_received_qty) {

            $updateTransfer->receive_status = 3;
        } elseif (
            $updateTransfer->total_received_qty > 0 &&
            $updateTransfer->total_received_qty < $request->total_send_quantity
        ) {

            $updateTransfer->receive_status = 2;
        } elseif ($updateTransfer->total_received_qty == 0) {

            $updateTransfer->receive_status = 1;
        }

        $updateTransfer->save();

        foreach ($updateTransfer->transfer_products as $transfer_product) {

            $transfer_product->is_delete_in_update = 1;
            $transfer_product->save();
        }

        if (count($request->product_ids) > 0) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;

                $updateTransferProduct = TransferStockBranchToBranchProducts::where('transfer_id', $updateTransfer->id)
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variant_id)->first();

                if ($updateTransferProduct) {

                    $updateTransferProduct->send_qty = $request->quantities[$index];
                    $pending_qty = $updateTransferProduct->send_qty - $updateTransferProduct->received_qty;

                    $updateTransferProduct->pending_qty = $pending_qty;
                    $updateTransferProduct->subtotal = $request->subtotals[$index];

                    $updateTransferProduct->is_delete_in_update = 0;
                    $updateTransferProduct->save();
                } else {

                    $addTransProduct = new TransferStockBranchToBranchProducts();
                    $addTransProduct->transfer_id = $addTransfer->id;
                    $addTransProduct->product_id = $product_id;
                    $addTransProduct->variant_id = $variant_id;
                    $addTransProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                    $addTransProduct->send_qty = $request->quantities[$index];
                    $addTransProduct->pending_qty = $request->quantities[$index];
                    $addTransProduct->subtotal = $request->subtotals[$index];
                    $addTransProduct->save();
                }

                $index++;
            }
        }

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 12,
            data_obj: $updateTransfer
        );

        // Delete not found which was previous
        $delectableTransferProducts = TransferStockBranchToBranchProducts::where('transfer_id', $transferId)
            ->where('is_delete_in_update', 1)->get();

        foreach ($delectableTransferProducts as $delectableTransferProduct) {

            $delectableTransferProduct->delete();
        }

        $this->transferStockUtil->updateExpenseFromTransferStock($request, $updateTransfer);

        session()->flash('successMsg', 'Transfer Stock updated successfully.');

        return response()->json(['successMsg' => 'Transfer Stock updated successfully.']);
    }

    public function delete(Request $request, $transferId)
    {
        $deleteTransfer = TransferStockBranchToBranch::with('expense', 'expense.expense_payments')
            ->where('id', $transferId)->first();

        if ($deleteTransfer->received_qty > 0) {

            return response()->json(['errorMsg' => 'Transfer can not be deleted. Cause one or more quantity has already been received from this transfer.']);
        }

        if (!is_null($deleteTransfer)) {

            $this->userActivityLogUtil->addLog(
                action: 3,
                subject_type: 12,
                data_obj: $deleteTransfer
            );

            $this->transferStockUtil->deleteTransferBranchToBranch($deleteTransfer);

            return response()->json('Transfer deleted successfully.');
        }
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

        return view('transfer_stock.branch_to_branch.ajax_view.show', compact('transfer'));
    }

    public function searchProduct($product_code, $warehouse_id, $receiver_branch_id)
    {
        $receiverBranchId = $receiver_branch_id ? $receiver_branch_id : '';

        if ($receiverBranchId == '') {

            return response()->json(['errorMsg' => 'Please select receiver Business Location']);
        }

        $__receiverBranchId = $receiverBranchId == 'NULL' ? NULL : $receiverBranchId;

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
                ->where('branch_id', $__receiverBranchId)->where('product_id', $product->id)
                ->where('status', 1)->first();

            if (is_null($productBranch)) {

                return response()->json(['errorMsg' => 'Product dose not available in the receiver Business Location.']);
            }
        } else {

            $variantProduct = ProductVariant::where('variant_code', $product_code)->first();

            if ($variantProduct) {

                $productBranch = DB::table('product_branches')
                    ->where('branch_id', $__receiverBranchId)->where('product_id', $variantProduct->product_id)
                    ->where('status', 1)->first();

                if (is_null($productBranch)) {

                    return response()->json(['errorMsg' => 'Product(Variant) dose not available in the receiver Business Location.']);
                }
            }
        }

        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->searchStockToWarehouse($product, $__product_code, $warehouse_id);
        } else {

            return $this->nameSearchUtil->searchStockToBranch($product, $__product_code, $branch_id);
        }
    }

    public function checkSingleProductStock($product_id, $warehouse_id, $receiver_branch_id)
    {
        $receiverBranchId = $receiver_branch_id ? $receiver_branch_id : '';

        if ($receiverBranchId == '') {

            return response()->json(['errorMsg' => 'Please select receiver Business Location']);
        }

        $__receiverBranchId = $receiverBranchId == 'NULL' ? NULL : $receiverBranchId;

        $productBranch = DB::table('product_branches')
            ->where('branch_id', $__receiverBranchId)->where('product_id', $product_id)
            ->where('status', 1)->first();

        if (is_null($productBranch)) {

            return response()->json(['errorMsg' => 'Product dose not available in the receiver Business Location.']);
        }

        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseSingleProduct($product_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, auth()->user()->branch_id);
        }
    }

    public function checkVariantProductStock($product_id, $variant_id, $warehouse_id, $receiver_branch_id)
    {
        $receiverBranchId = $receiver_branch_id ? $receiver_branch_id : '';

        if ($receiverBranchId == '') {

            return response()->json(['errorMsg' => 'Please select receiver Business Location']);
        }

        $__receiverBranchId = $receiverBranchId == 'NULL' ? NULL : $receiverBranchId;

        $productBranch = DB::table('product_branches')
            ->where('branch_id', $__receiverBranchId)->where('product_id', $product_id)
            ->where('status', 1)->first();

        if (is_null($productBranch)) {

            return response()->json(['errorMsg' => 'Product dose not available in the receiver Business Location.']);
        }

        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, auth()->user()->branch_id);
        }
    }
}
