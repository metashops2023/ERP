<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\Converter;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use App\Models\ProductWarehouse;
use App\Utils\PurchaseReturnUtil;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\PurchaseReturnProduct;
use App\Models\ProductWarehouseVariant;
use App\Utils\AccountUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnController extends Controller
{
    protected $purchaseReturnUtil;
    protected $nameSearchUtil;
    protected $productStockUtil;
    protected $supplierUtil;
    protected $purchaseUtil;
    protected $converter;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        PurchaseReturnUtil $purchaseReturnUtil,
        NameSearchUtil $nameSearchUtil,
        ProductStockUtil $productStockUtil,
        SupplierUtil $supplierUtil,
        PurchaseUtil $purchaseUtil,
        Converter $converter,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {

        $this->purchaseReturnUtil = $purchaseReturnUtil;
        $this->nameSearchUtil = $nameSearchUtil;
        $this->productStockUtil = $productStockUtil;
        $this->supplierUtil = $supplierUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->converter = $converter;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $returns = '';
            $generalSettings = DB::table('general_settings')->first();
            $query = DB::table('purchase_returns')
                ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchase_returns.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchase_returns.supplier_id', 'suppliers.id')
                ->leftJoin('suppliers as p_supplier', 'purchases.supplier_id', 'p_supplier.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('purchase_returns.branch_id', NULL);
                } else {

                    $query->where('purchase_returns.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_id) {

                $query->where('purchase_returns.supplier_id', $request->supplier_id);
            }

            if ($request->status) {

                $query->where('purchase_returns.status', $request->status);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchase_returns.report_date', $date_range); // Final
            }

            $query->select(
                'purchase_returns.*',
                'purchases.invoice_id as parent_invoice_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as sup_name',
                'p_supplier.name as ps_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $returns = $query->orderBy('purchase_returns.report_date', 'desc');
            } else {

                $returns = $query->where('purchase_returns.branch_id', auth()->user()->branch_id)
                    ->orderBy('purchase_returns.report_date', 'desc');
            }

            return DataTables::of($returns)

                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__("Action").' </button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('purchases.returns.show', $row->id) . '"><i class="far fa-eye mr-1 text-primary"></i>'.__("View").' </a>';
                        if ($row->status == 1) {
                            $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.returns.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i>Change Status</a>';
                        } else {
                            $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.returns.change.status', [$row->id]) . '"><i class="fas fa-undo text-success">Change Status</i></a>';
                        }
                        if (auth()->user()->branch_id == $row->branch_id) {

                            if ($row->return_type == 1) {

                                $html .= '<a class="dropdown-item" href="' . route('purchases.returns.create', $row->purchase_id) . '"><i class="far fa-edit mr-1 text-primary"></i>'.__("Edit").' </a>';
                            } else {

                                $html .= '<a class="dropdown-item" href="' . route('purchases.returns.supplier.return.edit', $row->id) . '"><i class="far fa-edit mr-1 text-primary"></i> '.__("Edit").'</a>';
                            }



                            // $html .= '<a class="dropdown-item" id="delete" href="' . route('purchases.returns.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>'.__("Delete").' </a>';
                            $html .= '<a class="dropdown-item " id="view_payment" href="' . route('purchases.returns.show.payment', $row->id) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                            if ($row->total_return_due > 0) {

                                if ($row->purchase_id) {

                                    $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->purchase_id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i> '.__("Add Payment").'</a>';
                                } else {

                                    $html .= '<a class="dropdown-item" id="add_supplier_return_payment" href="#"><i class="far fa-money-bill-alt mr-1 text-primary"></i>'.__("Receive Return Amt.").' </a>';
                                }

                        }

                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('supplier',  function ($row) {

                    if ($row->sup_name == null) {

                        return $row->ps_name;
                    }

                    return $row->sup_name;
                })
                ->editColumn('location',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('return_from',  function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return ($row->warehouse_name . '/' . $row->warehouse_code) . '<b>(WH)</b>';
                    } elseif ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('total_return_amount', fn ($row) => $this->converter->format_in_bdt($row->total_return_amount))

                ->editColumn('total_return_due_received', fn ($row) => $this->converter->format_in_bdt($row->total_return_due_received))

                ->editColumn('total_return_due', function ($row) {

                    if ($row->parent_invoice_id) {

                        return '<span class="text-danger"> ' . ($row->total_return_due >= 0 ? $this->converter->format_in_bdt($row->total_return_due) : $this->converter->format_in_bdt(0)) . '</span></b>';
                    } else {

                        return '<span class="text-dark"><b>CHECK SUPPLIER DUE</b></span>';
                    }
                })

                ->editColumn('payment_status', function ($row) {

                    if ($row->parent_invoice_id) {
                        if ($row->total_return_due > 0) {

                            return '<span class="text-danger"><b>Due</b></span>';
                        } else {

                            return '<span class="text-success"><b>Paid</b></span>';
                        }
                    } else {

                        return '<span class="text-dark"><b>CHECK SUPPLIER DUE</b></span>';
                    }
                })
                ->editColumn('status', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="text-success">Active</span>';
                    } else {

                        return '<span class="text-danger">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'date', 'supplier', 'return_from', 'location', 'total_return_amount', 'total_return_due_received', 'total_return_due', 'payment_status','status'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->where('status', 1)->get(['id', 'name', 'phone']);
        return view('purchases.purchase_return.index', compact('branches', 'suppliers'));
    }

    // create purchase return view
    public function create($purchaseId)
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $purchaseId = $purchaseId;

        $purchase = Purchase::with(['warehouse', 'branch', 'supplier'])->where('id', $purchaseId)->first();

        $purchaseReturnAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 4)
            ->get(['accounts.id', 'accounts.name']);

        return view('purchases.purchase_return.create', compact('purchaseId', 'purchase', 'purchaseReturnAccounts'));
    }

    public function store(Request $request, $purchaseId)
    {
        $this->validate(
            $request,
            [
                'purchase_return_account_id' => 'required',
                'date' => 'required',
            ]
        );

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];

        // generate invoice ID
        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_returns'), 4, "0", STR_PAD_LEFT);

        $qty = 0;
        foreach ($request->return_quantities as $return_quantity) {

            if ($return_quantity > 0) {

                $qty += 1;
            }
        }

        if ($qty == 0) {

            return response()->json(['errorMsg' => "All product`s quantity is 0."]);
        }

        $purchaseReturn = PurchaseReturn::where('purchase_id', $purchaseId)->first();

        if ($purchaseReturn) {

            $this->purchaseReturnUtil->updatePurchaseInvoiceWiseReturn($purchaseId, $purchaseReturn, $request, $invoicePrefix, $invoiceId);
        } else {

            $this->purchaseReturnUtil->storePurchaseInvoiceWiseReturn($purchaseId, $request, $invoicePrefix, $invoiceId);
        }

        if ($request->action == 'save_and_print') {

            $return = PurchaseReturn::with([
                'purchase',
                'branch',
                'supplier',
                'warehouse',
                'purchase.supplier',
                'purchase_return_products',
                'purchase_return_products.product',
                'purchase_return_products.variant',
            ])->where('purchase_id', $purchaseId)->first();

            if ($purchaseReturn) {

                return view('purchases.purchase_return.save_and_print_template.purchase_return_print_view', compact('return'));
            }
        } else {

                return response()->json(['successMsg' => __('Purchase Return Added Successfully.')]);


        }
    }

    // Show purchase return details
    public function show($returnId)
    {
        $return = PurchaseReturn::with([
            'purchase',
            'warehouse',
            'branch',
            'supplier',
            'purchase_return_products',
            'purchase_return_products.product',
            'purchase_return_products.variant',
            'purchase_return_products.purchase_product'
        ])->where('id', $returnId)->first();

        return view('purchases.purchase_return.ajax_view.show', compact('return'));
    }


    public function showPayment($returnId)
    {
        $return = PurchaseReturn::with([
            'purchase',
            'warehouse',
            'branch',
            'supplier',
            'purchase_return_products',
            'purchase_return_products.product',
            'purchase_return_products.variant',
            'purchase_return_products.purchase_product'
        ])->where('id', $returnId)->first();

        return view('purchases.purchase_return.ajax_view.return_payment_list', compact('return'));
    }

    // Get purchase requested by ajax
    public function getPurchase($purchaseId)
    {
        $purchase = Purchase::with([
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant',
            'purchase_return',
            'purchase_return.purchase_return_products',
            'purchase_return.purchase_return_products.purchase_product',
            'purchase_return.purchase_return_products.purchase_product.product',
            'purchase_return.purchase_return_products.purchase_product.variant'
        ])->where('id', $purchaseId)->first();

        return response()->json($purchase);
    }

    //Deleted purchase return
    public function delete($purchaseReturnId)
    {
        $purchaseReturn = PurchaseReturn::with(['purchase', 'purchase.supplier', 'supplier', 'purchase_return_products'])->where('id', $purchaseReturnId)->first();
        $storeReturnProducts = $purchaseReturn->purchase_return_products;
        $storePurchase = $purchaseReturn->purchase;
        $storedReturnType = $purchaseReturn->return_type;
        $storedBranchId = $purchaseReturn->branch_id;
        $storedWarehouseId = $purchaseReturn->warehouse_id;
        $storePurchaseReturnAccountId = $purchaseReturn->purchase_return_account_id;
        $storeSupplierId = $purchaseReturn->purchase ? $purchaseReturn->purchase->supplier_id : $purchaseReturn->supplier_id;

        if ($purchaseReturn->return_type == 1) {

            $purchaseReturn->purchase->is_return_available = 0;

            if ($purchaseReturn->total_return_due_received > 0) {

                return response()->json(['errorMsg' => "You can not delete this, cause your have received some or full amount on this return."]);
            }
        } else {

            if ($purchaseReturn->total_return_due_received > 0) {

                return response()->json(['errorMsg' => "You can not delete this, cause your have received some or full amount on this return."]);
            }
        }
        $purchaseReturn->delete();

        foreach ($storeReturnProducts as $return_product) {

            $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);

            if ($storedReturnType == 1) {

                if ($storePurchase->warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $storePurchase->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $storePurchase->branch_id);
                }
            } else {

                if ($storedWarehouseId) {

                    $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $storedWarehouseId);
                } else {

                    $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $storedBranchId);
                }
            }
        }

        if ($storePurchase) {

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storePurchase);
        }

        if ($storePurchaseReturnAccountId) {

            $this->accountUtil->adjustAccountBalance('credit', $storePurchaseReturnAccountId);
        }

        $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storeSupplierId);

        return response()->json(['successMsg' => __('Purchase Return deleted Successfully.')]);
    }

    public function supplierReturn()
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')
            ->where('branch_id', auth()->user()->branch_id)->get();

        $purchaseReturnAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 4)
            ->get(['accounts.id', 'accounts.name']);

        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();

        return view('purchases.purchase_return.supplier_return', compact('warehouses', 'suppliers', 'purchaseReturnAccounts'));
    }

    // Search product by code
    public function searchProduct($product_code, $warehouse_id)
    {
        $product_code = (string)$product_code;
        $__product_code = str_replace('~', '/', $product_code);
        $branch_id = auth()->user()->branch_id;

        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_code', $__product_code)
            ->select([
                'id', 'name', 'type', 'product_code', 'product_price', 'profit', 'product_cost_with_tax', 'thumbnail_photo', 'unit_id', 'tax_id', 'tax_type', 'is_show_emi_on_pos'
            ])->first();

        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->searchStockToWarehouse($product, $__product_code, $warehouse_id);
        } else {

            return $this->nameSearchUtil->searchStockToBranch($product, $__product_code, $branch_id);
        }
    }

    public function checkSingleProductStock($product_id, $warehouse_id)
    {
        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseSingleProduct($product_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, auth()->user()->branch_id);
        }
    }

    public function checkVariantProductStock($product_id, $variant_id, $warehouse_id)
    {
        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, auth()->user()->branch_id);
        }
    }

    public function supplierReturnStore(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'date' => 'required',
            'purchase_return_account_id' => 'required',
        ], [
            'supplier_id.required' => 'Supplier field is required',
            'purchase_return_account_id.required' => 'Purchase Return A/C field is required',
        ]);

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'Product return table is empty']);
        }

        // generate invoice ID
        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_returns'), 4, "0", STR_PAD_LEFT);

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->supplier_id = $request->supplier_id;
        $addPurchaseReturn->purchase_return_account_id = $request->purchase_return_account_id;
        $addPurchaseReturn->warehouse_id = $request->warehouse_id;
        $addPurchaseReturn->branch_id = auth()->user()->branch_id;
        $addPurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PRI') . $invoiceId;
        $addPurchaseReturn->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $addPurchaseReturn->purchase_tax_amount = $request->purchase_tax_amount;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        $addPurchaseReturn->total_return_due = $request->total_return_amount;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->return_type = 2;
        $addPurchaseReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchaseReturn->month = date('F');
        $addPurchaseReturn->year = date('Y');
        $addPurchaseReturn->admin_id = auth()->user()->id;
        $addPurchaseReturn->save();

        // Add purchase return product
        $__index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : NULL;

            $addPurchaseReturnProduct = new PurchaseReturnProduct();
            $addPurchaseReturnProduct->purchase_return_id = $addPurchaseReturn->id;
            $addPurchaseReturnProduct->product_id = $product_id;
            $addPurchaseReturnProduct->product_variant_id = $variant_id;
            $addPurchaseReturnProduct->return_qty = $request->return_quantities[$__index];
            $addPurchaseReturnProduct->unit = $request->units[$__index];
            $addPurchaseReturnProduct->unit_cost = $request->unit_costs[$__index];
            $addPurchaseReturnProduct->return_subtotal = $request->return_subtotals[$__index];
            $addPurchaseReturnProduct->save();
            $__index++;
        }

        $__index2 = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$__index2] != 'noid' ? $request->variant_ids[$__index2] : NULL;

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

            if ($request->warehouse_id) {

                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->warehouse_id);
            } else {

                $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);
            }

            $__index2++;
        }

        // Add Purchase Return A/C ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 4,
            date: $request->date,
            account_id: $request->purchase_return_account_id,
            trans_id: $addPurchaseReturn->id,
            amount: $request->total_return_amount,
            balance_type: 'credit'
        );

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 2,
            supplier_id: $request->supplier_id,
            branch_id: auth()->user()->branch_id,
            date: $request->date,
            trans_id: $addPurchaseReturn->id,
            amount: $request->total_return_amount
        );

        if ($request->action == 1) {
            $return = PurchaseReturn::with([
                'supplier',
                'branch',
                'warehouse',
                'purchase',
                'purchase.supplier',
                'purchase_return_products',
                'purchase_return_products.product',
                'purchase_return_products.variant',
            ])
                ->where('id', $addPurchaseReturn->id)->first();

            return view('purchases.purchase_return.save_and_print_template.purchase_return_print_view', compact('return'));
        } else {

            return response()->json(['successMsg' => __('Purchase Return Added Successfully.')]);
        }
    }

    // Edit supplier return view
    public function supplierReturnEdit($purchaseReturnId)
    {
        $return = PurchaseReturn::with(
            [
                'supplier',
                'purchase_return_products',
                'purchase_return_products.product',
                'purchase_return_products.variant',
                'purchase_return_products.product.unit',
            ]
        )
            ->where('id', $purchaseReturnId)
            ->first();

        $taxes = DB::table('taxes')->select('tax_name', 'tax_percent')->get();

        $warehouses = DB::table('warehouses')
            ->select('id', 'warehouse_name', 'warehouse_code')->get();

        $purchaseReturnAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 4)
            ->get(['accounts.id', 'accounts.name']);

        $qty_limits = $this->purchaseReturnUtil->getStockLimitProducts($return);

        return view(
            'purchases.purchase_return.edit_supplier_return',
            compact(
                'return',
                'warehouses',
                'purchaseReturnAccounts',
                'taxes',
                'qty_limits'
            )
        );
    }

    public function changeStatus($id)
    {
        $statusChange = PurchaseReturn::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Purchase Return is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Purchase Return is activated Successfully'));

        }
    }

    public function supplierReturnUpdate(Request $request, $purchaseReturnId)
    {
        $this->validate($request, [
            'date' => 'required',
            'purchase_return_account_id' => 'required',
        ], [
            'supplier_id.required' => 'Supplier field is required',
            'purchase_return_account_id.required' => 'Purchase Return A/C field is required',
        ]);

        //return $request->purchase_return_account_id;
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];

        $updatePurchaseReturn = PurchaseReturn::with('purchase_return_products')
            ->where('id', $purchaseReturnId)->first();

        $storedWarehouseId = $updatePurchaseReturn->warehouse_id;

        $storedReturnProducts = $updatePurchaseReturn->purchase_return_products;

        foreach ($updatePurchaseReturn->purchase_return_products as $purchase_return_product) {

            $purchase_return_product->is_delete_in_update = 1;
            $purchase_return_product->save();
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_returns'), 4, "0", STR_PAD_LEFT);

        $updatePurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $invoiceId;

        $updatePurchaseReturn->warehouse_id = $request->warehouse_id ? $request->warehouse_id : NULL;
        $updatePurchaseReturn->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $updatePurchaseReturn->purchase_tax_amount = $request->purchase_tax_amount;
        $updatePurchaseReturn->total_return_amount = $request->total_return_amount;
        $updatePurchaseReturn->total_return_due = $request->total_return_amount - $updatePurchaseReturn->total_return_due_received;
        $updatePurchaseReturn->date = $request->date;
        $updatePurchaseReturn->return_type = 2;
        $updatePurchaseReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updatePurchaseReturn->month = date('F');
        $updatePurchaseReturn->year = date('Y');
        $updatePurchaseReturn->save();

        // Update Purchase Return Product
        $__index = 0;
        foreach ($product_ids as $product_id) {

            $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;

            $purchaseReturnProduct = PurchaseReturnProduct::where('purchase_return_id')
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();

            if ($purchaseReturnProduct) {

                $purchaseReturnProduct->return_qty = $return_quantities[$__index];
                $purchaseReturnProduct->unit = $units[$__index];
                $purchaseReturnProduct->return_subtotal = $return_subtotals[$__index];
                $purchaseReturnProduct->is_delete_in_update = 0;
                $purchaseReturnProduct->save();
            } else {

                $addPurchaseReturnProduct = new PurchaseReturnProduct();
                $addPurchaseReturnProduct->purchase_return_id = $updatePurchaseReturn->id;
                $addPurchaseReturnProduct->product_id = $product_id;
                $addPurchaseReturnProduct->product_variant_id = $variant_id;
                $addPurchaseReturnProduct->unit = $units[$__index];
                $addPurchaseReturnProduct->return_qty = $return_quantities[$__index];
                $addPurchaseReturnProduct->return_subtotal = $return_subtotals[$__index];
                $addPurchaseReturnProduct->save();
            }

            $__index++;
        }

        // delete not found previous products
        $purchaseReturnProducts = PurchaseReturnProduct::where('is_delete_in_update', 1)->get();

        if (count($purchaseReturnProducts) > 0) {

            foreach ($purchaseReturnProducts as $purchaseReturnProduct) {

                $storedProductId = $purchaseReturnProduct->product_id;
                $storedVariantId = $purchaseReturnProduct->product_variant_id;
                $purchaseReturnProduct->delete();

                $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                if ($updatePurchaseReturn->warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $updatePurchaseReturn->warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, $updatePurchaseReturn->branch_id);
                }
            }
        }

        $returnProducts = DB::table('purchase_return_products')
            ->where('purchase_return_id', $updatePurchaseReturn->id)->get();

        foreach ($returnProducts as $return_product) {

            $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);

            if ($updatePurchaseReturn->warehouse_id) {

                $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $updatePurchaseReturn->warehouse_id);
            } else {

                $this->productStockUtil->adjustBranchStock($return_product->product_id, $return_product->product_variant_id, $updatePurchaseReturn->branch_id);
            }
        }

        if ($storedWarehouseId != $request->warehouse_id) {

            foreach ($storedReturnProducts as $return_product) {

                $this->productStockUtil->adjustWarehouseStock($return_product->product_id, $return_product->product_variant_id, $storedWarehouseId);
            }
        }

        // Update Purchase Return A/C ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 4,
            date: $request->date,
            account_id: $request->purchase_return_account_id,
            trans_id: $updatePurchaseReturn->id,
            amount: $request->total_return_amount,
            balance_type: 'credit'
        );

        // Update Supplier Ledger
        $this->supplierUtil->updateSupplierLedger(
            voucher_type_id: 2,
            supplier_id: $updatePurchaseReturn->supplier_id,
            previous_branch_id: auth()->user()->branch_id,
            new_branch_id: auth()->user()->branch_id,
            date: $request->date,
            trans_id: $updatePurchaseReturn->id,
            amount: $request->total_return_amount
        );

        session()->flash('successMsg', __('Purchase Return Added Successfully.'));
        return response()->json(['successMsg' => __('Purchase Return Added Successfully.')]);
    }

    public function returnPaymentList()
    {
        # Supplier return payment list code will be go here
    }
}
