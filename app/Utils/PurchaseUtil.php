<?php

namespace App\Utils;

use Carbon\Carbon;
use App\Models\Product;
use App\Utils\Converter;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use Yajra\DataTables\Facades\DataTables;

class PurchaseUtil
{
    public $converter;
    public $invoiceVoucherRefIdUtil;
    public function __construct(Converter $converter, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function purchaseListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');

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

        if ($request->purchase_status) {

            $query->where('purchases.purchase_status', $request->purchase_status);
        }

        if ($request->status) {

            $query->where('purchases.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.is_return_available',
            'purchases.total_purchase_amount',
            'purchases.purchase_return_amount',
            'purchases.purchase_return_due',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'purchases.status',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchases = $query->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc');
        } else {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->where('is_purchased', 1)->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';
                return $html;
            })->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {

                    return $row->branch_name . '<b>(BL)</b>';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                }
            })
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . $this->converter->format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' .  '<span class="due" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span></span>')

            ->editColumn('purchase_return_amount', fn ($row) => '<span class="purchase_return_amount" data-value="' . $row->purchase_return_amount . '">' . $this->converter->format_in_bdt($row->purchase_return_amount) . '</span>')

            ->editColumn('purchase_return_due', fn ($row) => '<span class="purchase_return_due text-danger" data-value="' . $row->purchase_return_due . '">' . $this->converter->format_in_bdt($row->purchase_return_due) . '</span>')

            ->editColumn('purchase_status', function ($row) {

                if ($row->purchase_status == 1) {

                    return '<span class="text-success"><b>' . __('Purchased') . '</b></span>';
                } elseif ($row->purchase_status == 2) {

                    return '<span class="text-secondary"><b>' . __('Pending') . '</b></span>';
                } elseif ($row->purchase_status == 3) {

                    return '<span class="text-primary"><b>' . __('Purchased By Order') . '</b></span>';
                }
            })->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>' . __('Paid') .  '</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>' . __('Partial') . '</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>' . __('Due') . '</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    return '<span class="text-success">Active</span>';
                } else {

                    return '<span class="text-danger">Inactive</span>';
                }
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status','purchase_status','status','created_by'])
            ->make(true);
    }

    public function poListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');

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
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.is_return_available',
            'purchases.total_purchase_amount',
            'purchases.purchase_return_amount',
            'purchases.purchase_return_due',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'purchases.po_receiving_status',
            'branches.name as branch_name',
            'branches.branch_code',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchases = $query->where('purchases.purchase_status', 3)
                ->orderBy('purchases.report_date', 'desc');
        } else {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->where('purchases.purchase_status', 3)
                ->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseOrderAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';
                return $html;
            })->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {

                    return $row->branch_name . '<b>(BL)</b>';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                }
            })
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . $this->converter->format_in_bdt($row->total_purchase_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('status', function ($row) {

                if ($row->po_receiving_status == 'Completed') {

                    return '<span class="text-success"><b>Completed</b></span>';
                } elseif ($row->po_receiving_status == 'Pending') {

                    return '<span class="text-danger"><b>Pending</b></span>';
                } elseif ($row->po_receiving_status == 'Partial') {

                    return '<span class="text-primary"><b>Partial</b></span>';
                }
            })->editColumn('payment_status', function ($row) {

                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->editColumn('statuss', function ($row) {

                if ($row->status == 1) {

                    return '<span class="text-success">Active</span>';
                } else {

                    return '<span class="text-danger">Inactive</span>';
                }
            })
            ->filter(function($query) use($request){
                // dd($request->active);
                if($request->active=="false"){
                    $query->where('status',1);
                }else{
                $query->where('status',0);
            }
               })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'status', 'created_by','statuss'])
            ->make(true);
    }

    public function purchaseProductListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $converter = $this->converter;
        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id');

        if ($request->product_id) {

            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', NULL);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_id) {

            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {

            $query->where('products.parent_category_id', $request->sub_category_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchase_products.purchase_id',
            'purchase_products.product_id',
            'purchase_products.product_variant_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity',
            'units.code_name as unit_code',
            'purchase_products.line_total',
            'purchase_products.selling_price',
            'purchases.id',
            'purchases.branch_id',
            'purchases.supplier_id',
            'purchases.date',
            'purchases.invoice_id',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name'
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchaseProducts = $query->where('purchases.is_purchased', 1)
                ->orderBy('purchases.report_date', 'desc');
        } else {

            $purchaseProducts = $query->where('purchases.is_purchased', 1)
                ->where('purchases.branch_id', auth()->user()->branch_id)
                ->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchaseProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return Str::limit($row->name, 25, '') . $variant;
            })->editColumn('product_code', function ($row) {

                return $row->variant_code ? $row->variant_code : $row->product_code;
            })->editColumn('date', function ($row) {

                return date('d/m/Y', strtotime($row->date));
            })->editColumn('quantity', function ($row) {

                return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
            })
            ->editColumn('invoice_id', fn ($row) => '<a href="' . route('purchases.show', [$row->purchase_id]) . '" class="details_button text-danger text-hover" title="view" >' . $row->invoice_id . '</a>')

            ->editColumn('net_unit_cost', fn ($row) => $this->converter->format_in_bdt($row->net_unit_cost))
            ->editColumn('price',  function ($row) use ($converter) {
                if ($row->selling_price > 0) {

                    return $converter->format_in_bdt($row->selling_price);
                } else {

                    if ($row->variant_name) {

                        return $converter->format_in_bdt($row->variant_price);
                    } else {

                        return $converter->format_in_bdt($row->product_price);
                    }
                }
                return $converter->format_in_bdt($row->net_unit_cost);
            })->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->line_total . '">' . $this->converter->format_in_bdt($row->line_total) . '</span>')

            ->rawColumns(['product', 'product_code', 'date', 'quantity', 'invoice_id', 'branch', 'net_unit_cost', 'price', 'subtotal'])
            ->make(true);
    }

    public function addPurchaseProduct($request, $isEditProductPrice, $purchaseId)
    {
        $warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : NULL;

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addPurchaseProduct = new PurchaseProduct();
            $addPurchaseProduct->purchase_id = $purchaseId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
            $addPurchaseProduct->description = $request->descriptions[$index];
            $addPurchaseProduct->quantity =  $request->quantities[$index];
            $addPurchaseProduct->left_qty =  $request->quantities[$index];
            $addPurchaseProduct->unit = $request->unit_names[$index];
            $addPurchaseProduct->unit_cost = $request->unit_costs[$index];
            $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $request->subtotals[$index];
            $addPurchaseProduct->unit_tax_percent = $request->tax_percents[$index];
            $addPurchaseProduct->unit_tax = $request->unit_taxes[$index];
            $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
            $addPurchaseProduct->line_total = $request->linetotals[$index];
            $addPurchaseProduct->branch_id = auth()->user()->branch_id;

            if ($isEditProductPrice == '1') {

                $addPurchaseProduct->profit_margin = $request->profits[$index];
                $addPurchaseProduct->selling_price = $request->selling_prices[$index];
            }

            if (isset($request->lot_number)) {

                $addPurchaseProduct->lot_no = $request->lot_number[$index];
            }

            $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));

            $addPurchaseProduct->save();

            $index++;
        }
    }

    public function addPurchaseOrderProduct($request, $isEditProductPrice, $purchaseId)
    {
        $warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : NULL;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $descriptions = $request->descriptions;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        $index = 0;
        foreach ($product_ids as $productId) {

            $addPurchaseProduct = new PurchaseOrderProduct();
            $addPurchaseProduct->purchase_id = $purchaseId;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addPurchaseProduct->description = $descriptions[$index];
            $addPurchaseProduct->order_quantity = $quantities[$index];
            $addPurchaseProduct->pending_quantity = $quantities[$index];
            $addPurchaseProduct->unit = $unit_names[$index];
            $addPurchaseProduct->unit_cost = $unit_costs[$index];
            $addPurchaseProduct->unit_discount = $discounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $subtotal[$index];
            $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
            $addPurchaseProduct->unit_tax = $unit_taxes[$index];
            $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
            $addPurchaseProduct->line_total = $linetotals[$index];

            if ($isEditProductPrice == '1') {

                $addPurchaseProduct->profit_margin = $profits[$index];
                $addPurchaseProduct->selling_price = $selling_prices[$index];
            }

            if (isset($request->lot_number)) {

                $addPurchaseProduct->lot_no = $request->lot_number[$index];
            }

            $addPurchaseProduct->save();
            $index++;
        }
    }

    public function updatePurchaseProduct($request, $isEditProductPrice, $purchaseId)
    {
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $descriptions = $request->descriptions;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        $index = 0;
        foreach ($product_ids as $productId) {

            $filterVariantId = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updatePurchaseProduct = PurchaseProduct::where('purchase_id', $purchaseId)
                ->where('product_id', $productId)
                ->where('product_variant_id', $filterVariantId)->first();

            if ($updatePurchaseProduct) {

                $updatePurchaseProduct->product_id = $productId;
                $updatePurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePurchaseProduct->quantity = $quantities[$index];
                $updatePurchaseProduct->description = $descriptions[$index];
                $updatePurchaseProduct->unit = $unit_names[$index];
                $updatePurchaseProduct->unit_cost = $unit_costs[$index];
                $updatePurchaseProduct->unit_discount = $discounts[$index];
                $updatePurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $updatePurchaseProduct->subtotal = $subtotal[$index];
                $updatePurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $updatePurchaseProduct->unit_tax = $unit_taxes[$index];
                $updatePurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $updatePurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {

                    $updatePurchaseProduct->profit_margin = $profits[$index];
                    $updatePurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {

                    $updatePurchaseProduct->lot_no = $request->lot_number[$index];
                }
                $updatePurchaseProduct->delete_in_update = 0;
                $updatePurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                $updatePurchaseProduct->save();

                $this->adjustPurchaseLeftQty($updatePurchaseProduct);
            } else {

                $addPurchaseProduct = new PurchaseProduct();
                $addPurchaseProduct->purchase_id = $purchaseId;
                $addPurchaseProduct->product_id = $productId;
                $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addPurchaseProduct->description = $descriptions[$index];
                $addPurchaseProduct->quantity = $quantities[$index];
                $addPurchaseProduct->left_qty = $quantities[$index];
                $addPurchaseProduct->unit = $unit_names[$index];
                $addPurchaseProduct->unit_cost = $unit_costs[$index];
                $addPurchaseProduct->unit_discount = $discounts[$index];
                $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $addPurchaseProduct->subtotal = $subtotal[$index];
                $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $addPurchaseProduct->unit_tax = $unit_taxes[$index];
                $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $addPurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {

                    $addPurchaseProduct->profit_margin = $profits[$index];
                    $addPurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {

                    $addPurchaseProduct->lot_no = $request->lot_number[$index];
                }

                $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                $addPurchaseProduct->save();
            }

            $index++;
        }
    }

    public function updatePurchaseOrderProduct($request, $isEditProductPrice, $purchaseId)
    {
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $descriptions = $request->descriptions;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        $index = 0;
        foreach ($product_ids as $productId) {

            $filterVariantId = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updatePurchaseProduct = PurchaseOrderProduct::where('purchase_id', $purchaseId)
                ->where('product_id', $productId)
                ->where('product_variant_id', $filterVariantId)
                ->first();

            if ($updatePurchaseProduct) {

                $updatePurchaseProduct->product_id = $productId;
                $updatePurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePurchaseProduct->description = $descriptions[$index];
                $updatePurchaseProduct->order_quantity = (float)$quantities[$index];
                $updatePurchaseProduct->pending_quantity = (float)$quantities[$index] - $updatePurchaseProduct->received_quantity;
                $updatePurchaseProduct->unit = $unit_names[$index];
                $updatePurchaseProduct->unit_cost = $unit_costs[$index];
                $updatePurchaseProduct->unit_discount = $discounts[$index];
                $updatePurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $updatePurchaseProduct->subtotal = $subtotal[$index];
                $updatePurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $updatePurchaseProduct->unit_tax = $unit_taxes[$index];
                $updatePurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $updatePurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {

                    $updatePurchaseProduct->profit_margin = $profits[$index];
                    $updatePurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {

                    $updatePurchaseProduct->lot_no = $request->lot_number[$index];
                }

                $updatePurchaseProduct->delete_in_update = 0;
                $updatePurchaseProduct->save();
            } else {

                $addPurchaseProduct = new PurchaseOrderProduct();
                $addPurchaseProduct->purchase_id = $purchaseId;
                $addPurchaseProduct->product_id = $productId;
                $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addPurchaseProduct->description = $descriptions[$index];
                $addPurchaseProduct->order_quantity = $quantities[$index];
                $addPurchaseProduct->pending_quantity = $quantities[$index];
                $addPurchaseProduct->unit = $unit_names[$index];
                $addPurchaseProduct->unit_cost = $unit_costs[$index];
                $addPurchaseProduct->unit_discount = $discounts[$index];
                $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $addPurchaseProduct->subtotal = $subtotal[$index];
                $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $addPurchaseProduct->unit_tax = $unit_taxes[$index];
                $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $addPurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {

                    $addPurchaseProduct->profit_margin = $profits[$index];
                    $addPurchaseProduct->selling_price = $selling_prices[$index];
                }

                if (isset($request->lot_number)) {

                    $addPurchaseProduct->lot_no = $request->lot_number[$index];
                }

                $addPurchaseProduct->save();
            }
            $index++;
        }
    }

    public function updatePoInvoiceQtyAndStatusPortion($purchase)
    {
        $purchaseOrderProducts = DB::table('purchase_order_products')->where('purchase_id', $purchase->id)
            ->select(
                DB::raw('sum(order_quantity) as o_qty'),
                DB::raw('sum(pending_quantity) as p_qty'),
                DB::raw('sum(received_quantity) as r_qty')
            )->groupBy('purchase_id')->get();

        $purchase->po_qty = $purchaseOrderProducts->sum('o_qty');
        $purchase->po_pending_qty = $purchaseOrderProducts->sum('p_qty');
        $purchase->po_received_qty = $purchaseOrderProducts->sum('r_qty');

        if ($purchaseOrderProducts->sum('p_qty') == 0) {

            $purchase->po_receiving_status = 'Completed';
        } elseif ($purchaseOrderProducts->sum('o_qty') == $purchaseOrderProducts->sum('p_qty')) {

            $purchase->po_receiving_status = 'Pending';
        } elseif ($purchaseOrderProducts->sum('r_qty') > 0) {

            $purchase->po_receiving_status = 'Partial';
        }

        $purchase->save();
    }

    private function createPurchaseAction($row)
    {

        $html = '<div class="btn-group" role="group">';
            $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
            $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

            if ($row->purchase_status == 1) {

                $html .= '<a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i>'.__("View").' </a>';
            } else {

                $html .= '<a class="dropdown-item details_button" href="' . route('purchases.show.order', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';
            }

            if ($row->status == 1) {
                $html .= '<a class="dropdown-item details_button" id="change_status" href="' . route('purchase.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i>'.__("Change Status").'</a>';
            } else {
                $html .= '<a class="dropdown-item details_button" id="change_status" href="' . route('purchase.change.status', [$row->id]) . '"><i class="fas fa-undo text-success">'.__("Change Status").'</i></a>';
            }

            // $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

            $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("View Payment").' </a>';

            // if (auth()->user()->branch_id == $row->branch_id) {

                if (auth()->user()->permission->purchase['purchase_payment'] == '1') {

                    if ($row->due > 0) {

                        $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("Add Payment").' </a>';
                    }

                    if ($row->purchase_return_due > 0) {

                        $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i>'.__("Receive Return Amount").' </a>';
                    }
                }

                if (auth()->user()->permission->purchase['purchase_edit'] == '1') {

                    $html .= '<a class="dropdown-item" href="' . route('purchases.edit', [$row->id, 'purchased']) . ' "><i class="far fa-edit text-primary"></i>'.__("Edit").' </a>';
                }

                // if (auth()->user()->permission->purchase['purchase_delete'] == '1') {

                //     $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                // }


                if (auth()->user()->permission->purchase['purchase_return'] == '1') {

                    $html .= '<a class="dropdown-item" id="purchase_return" href="' . route('purchases.returns.create', $row->id) . '"><i class="fas fa-undo-alt text-primary"></i> '.__("Purchase Return").'</a>';
                }

                // $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.change.status.modal', $row->id) . '"><i class="far fa-edit text-primary"></i> Update Status</a>';
            // }

            // $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> Items Received Notification</a>';


        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    private function createPurchaseOrderAction($row)
    {
        $html = '<div class="btn-group" role="group">';
                   $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>';
            $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item details_button" href="' . route('purchases.show.order', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

    if (auth()->user()->branch_id == $row->branch_id) {

        $html .= '<a class="dropdown-item" href="' . route('purchases.po.receive.process', [$row->id]) . '"><i class="fas fa-check-double text-primary"></i> PO To Receive</a>';
    }
    // $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

    if (auth()->user()->branch_id == $row->branch_id) {

        if (auth()->user()->permission->purchase['purchase_payment'] == '1') {

            if ($row->due > 0) {

                $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
            }

            $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payments</a>';
        }

        if (auth()->user()->permission->purchase['purchase_edit'] == '1') {

            $html .= '<a class="dropdown-item" href="' . route('purchases.edit', [$row->id, 'ordered']) . ' "><i class="far fa-edit text-primary"></i> Edit</a>';
        }

        if (auth()->user()->permission->purchase['purchase_delete'] == '1') {
            $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
        }
    }



        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function updateProductAndVariantPrice(
        $productId,
        $variant_id,
        $unit_cost_with_discount,
        $net_unit_cost,
        $profit,
        $selling_price,
        $isEditProductPrice,
        $isLastEntry
    ) {
        $updateProduct = Product::where('id', $productId)->first();
        $updateProduct->is_purchased = 1;

        if ($updateProduct->is_variant == 0) {

            if ($isLastEntry == 1) {

                $updateProduct->product_cost = $unit_cost_with_discount;
                $updateProduct->product_cost_with_tax = $net_unit_cost;
            }

            if ($isEditProductPrice == '1') {

                $updateProduct->profit = $profit;
                $updateProduct->product_price = $selling_price;
            }
        }

        $updateProduct->save();

        if ($variant_id != NULL) {

            $updateVariant = ProductVariant::where('id', $variant_id)
                ->where('product_id', $productId)
                ->first();

            if ($isLastEntry == 1) {

                $updateVariant->variant_cost = $unit_cost_with_discount;
                $updateVariant->variant_cost_with_tax = $net_unit_cost;
            }

            if ($isEditProductPrice == '1') {

                $updateVariant->variant_profit = $profit;
                $updateVariant->variant_price = $selling_price;
            }

            $updateVariant->is_purchased = 1;
            $updateVariant->save();
        }
    }

    public function adjustPurchaseInvoiceAmounts($purchase)
    {
        $totalPurchasePaid = DB::table('purchase_payments')
            ->where('purchase_payments.purchase_id', $purchase->id)->where('payment_type', 1)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('purchase_payments.purchase_id')
            ->get();

        $totalReturnPaid = DB::table('purchase_payments')
            ->where('purchase_payments.purchase_id', $purchase->id)->where('payment_type', 2)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('purchase_payments.purchase_id')
            ->get();

        $return = DB::table('purchase_returns')->where('purchase_id', $purchase->id)->first();

        $returnAmount = $return ? $return->total_return_amount : 0;

        $due = $purchase->total_purchase_amount
            - $totalPurchasePaid->sum('total_paid')
            - $returnAmount
            + $totalReturnPaid->sum('total_paid');

        $returnDue = $returnAmount
            - ($purchase->total_purchase_amount - $totalPurchasePaid->sum('total_paid'))
            - $totalReturnPaid->sum('total_paid');

        $purchase->paid = $totalPurchasePaid->sum('total_paid');
        $purchase->due = $due;
        $purchase->purchase_return_amount = $returnAmount;
        $purchase->purchase_return_due = $returnDue > 0 ? $returnDue : 0;
        $purchase->save();

        return $purchase;
    }

    public function adjustPurchaseLeftQty($purchaseProduct)
    {
        $totalSold = DB::table('purchase_sale_product_chains')
            ->where('purchase_product_id', $purchaseProduct->id)
            ->select(DB::raw('SUM(sold_qty) as total_sold'))
            ->groupBy('purchase_product_id')->get();

        $leftQty = $purchaseProduct->quantity - $totalSold->sum('total_sold');
        $purchaseProduct->left_qty = $leftQty;
        $purchaseProduct->save();
    }

    public function addPurchasePaymentGetId($invoicePrefix, $request, $payingAmount, $invoiceId, $purchase, $supplier_payment_id, $fixed_payment_date = NULL)
    {
        $__date = $fixed_payment_date ? $fixed_payment_date : $request->date;
        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($invoicePrefix != null ? $invoicePrefix : 'PPV') . $invoiceId;
        $addPurchasePayment->purchase_id = $purchase->id;
        $addPurchasePayment->is_advanced = $purchase->is_purchased == 0 ? 1 : 0;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->payment_method_id = $request->payment_method_id;
        $addPurchasePayment->supplier_id = $purchase->supplier_id;
        $addPurchasePayment->supplier_payment_id = $supplier_payment_id;
        $addPurchasePayment->paid_amount = $payingAmount;
        $addPurchasePayment->date = $__date;
        $addPurchasePayment->time = date('h:i:s a');
        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($__date . date(' H:i:s')));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->note = $request->payment_note;
        $addPurchasePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {

            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
        }

        $addPurchasePayment->save();
        return $addPurchasePayment->id;
    }

    public function updatePurchasePayment($request, $payment)
    {
        // update sale payment
        $payment->account_id = $payment->supplier_payment_id == NULL ? $request->account_id : $payment->account_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->paid_amount = $request->paying_amount;
        $payment->date = $request->date;
        $payment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $payment->month = date('F');
        $payment->year = date('Y');
        $payment->note = $request->note;

        if ($request->hasFile('attachment')) {
            if ($payment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $payment->attachment = $salePaymentAttachmentName;
        }

        $payment->save();
    }

    public function purchaseReturnPaymentGetId($request, $purchase, $supplier_payment_id)
    {
        // Add sale return payment
        $addPurchaseReturnPayment = new PurchasePayment();
        $addPurchaseReturnPayment->invoice_id = 'PRPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');
        $addPurchaseReturnPayment->purchase_id = $purchase->id;
        $addPurchaseReturnPayment->supplier_id = $purchase->supplier_id;
        $addPurchaseReturnPayment->account_id = $request->account_id;
        $addPurchaseReturnPayment->payment_method_id = $request->payment_method_id;
        $addPurchaseReturnPayment->supplier_payment_id = $supplier_payment_id;
        $addPurchaseReturnPayment->payment_type = 2;
        $addPurchaseReturnPayment->paid_amount = $request->paying_amount;
        $addPurchaseReturnPayment->date = $request->date;
        $addPurchaseReturnPayment->time = date('h:i:s a');
        $addPurchaseReturnPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchaseReturnPayment->month = date('F');
        $addPurchaseReturnPayment->year = date('Y');
        $addPurchaseReturnPayment->note = $request->note;
        $addPurchaseReturnPayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = uniqid() . '-' . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('uploads/payment_attachment/'), $attachmentName);
            $addPurchaseReturnPayment->attachment = $attachmentName;
        }
        $addPurchaseReturnPayment->save();

        return $addPurchaseReturnPayment->id;
    }

    public function updatePurchaseReturnPayment($request, $payment)
    {
        // update sale payment
        $payment->account_id = $payment->supplier_payment_id == NULL ? $request->account_id : $payment->account_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->paid_amount = $request->paying_amount;
        $payment->date = $request->date;
        $payment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $payment->month = date('F');
        $payment->year = date('Y');
        $payment->note = $request->note;

        if ($request->hasFile('attachment')) {
            if ($payment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                }
            }
            $attachment = $request->file('attachment');
            $attachmentName = uniqid() . '-' . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('uploads/payment_attachment/'), $attachmentName);
            $payment->attachment = $attachmentName;
        }

        $payment->save();
    }
}
