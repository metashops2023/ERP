<?php

namespace App\Utils;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warranty;
use App\Utils\PurchaseUtil;
use App\Models\ProductBranch;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Models\ProductBranchVariant;
use App\Utils\PurchaseSaleChainUtil;

class ProductUtil
{
    public $purchaseUtil;
    public $purchaseSaleChainUtil;
    public $productStockUtil;
    public function __construct(
        PurchaseUtil $purchaseUtil,
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
        ProductStockUtil $productStockUtil
    ) {
        $this->purchaseUtil = $purchaseUtil;
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->productStockUtil = $productStockUtil;
    }

    public function productListTable($request)
    {
        $productStock = $this->productStockUtil;
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $countPriceGroup = DB::table('price_groups')->where('status', 'Active')->count();
        $img_url = asset('uploads/product/thumbnail');
        $products = '';

        $query = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->where('product_branches.status', 1);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_branches.branch_id', NULL);
            } else {

                $query->where('product_branches.branch_id', $request->branch_id);
            }
        }

        if ($request->type == 1) {

            $query->where('products.type', 1)->where('products.is_variant', 0);
        }

        if ($request->type == 1) {

            $query->where('products.type', 1)->where('products.is_variant', 0);
        }

        if ($request->type == 2) {

            $query->where('products.is_variant', 1)->where('products.type', 1);
        }

        if ($request->type == 3) {

            $query->where('products.type', 2)->where('products.is_combo', 1);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_id) {

            $query->where('products.tax_id', $request->tax_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->status != '') {

            $query->where('products.status', $request->status);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query;
        } else {

            $query->where('product_branches.branch_id', auth()->user()->branch_id);
        }

        $products = $query->select(
            [
                'products.id',
                'products.name',
                'products.status',
                'products.is_variant',
                'products.type',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.is_manage_stock',
                'products.thumbnail_photo',
                'products.expire_date',
                'products.is_combo',
                'units.name as unit_name',
                'taxes.tax_name',
                'categories.name as cate_name',
                'sub_cate.name as sub_cate_name',
                'brands.name as brand_name',
            ]
        )->distinct('product_branches.branch_id')->orderBy('id', 'desc');

        return DataTables::of($products)
            ->addColumn('multiple_delete', function ($row) {

                return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="' . $row->id . '"/>';
            })->editColumn('photo', function ($row) use ($img_url) {

                return '<img loading="lazy" class="rounded" style="height:40px; width:40px; padding:2px 0px;" src="' . $img_url . '/' . $row->thumbnail_photo . '">';
            })->addColumn('action', function ($row) use ($countPriceGroup) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('products.view', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" id="check_pur_and_gan_bar_button" href="' . route('products.check.purchase.and.generate.barcode', [$row->id]) . '"><i class="fas fa-barcode text-primary"></i>'.__("Barcode").' </a>';

                    if (auth()->user()->permission->product['product_edit']  == '1') {

                        $html .= '<a class="dropdown-item" href="' . route('products.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                    }

                    // if (auth()->user()->permission->product['product_delete']  == '1') {

                    //     $html .= '<a class="dropdown-item" id="delete" href="' . route('products.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    // }

                    if ($row->status == 1) {

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="fas fa-window-close text-danger"></i> '.__("Change Status").'</a>';
                    } else {

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="fas fa-undo text-success"></i> '.__("Change Status").'</a>';
                    }

                    if (auth()->user()->permission->product['openingStock_add']  == '1') {

                        $html .= '<a class="dropdown-item" id="opening_stock" href="' . route('products.opening.stock', [$row->id]) . '"><i class="fas fa-database text-primary"></i> '.__("Add or edit opening stock").'</a>';
                    }

                    if ($countPriceGroup > 0) {

                        $html .= '<a class="dropdown-item" href="' . route('products.add.price.groups', [$row->id, $row->is_variant]) . '"><i class="far fa-money-bill-alt text-primary"></i> '.__("Add or edit price group").'</a>';
                    }



                $html .= ' </div>';
                $html .= '</div>';
                return $html;
            })->editColumn('name', function ($row) {
                $html = '';
                $html .= $row->name;
                $html .= $row->is_manage_stock == 0 ? ' <span class="badge bg-primary pt-1"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '';
                return $html;
            })->editColumn('type', function ($row) {

                if ($row->type == 1 && $row->is_variant == 1) {

                    return '<span class="text-primary">Variant</span>';
                } elseif ($row->type == 1 && $row->is_variant == 0) {

                    return '<span class="text-success">Single</span>';
                } elseif ($row->type == 2) {

                    return '<span class="text-info">Combo</span>';
                } elseif ($row->type == 3) {

                    return '<span class="text-info">Digital</span>';
                }
            })
            ->editColumn('cate_name', fn ($row) => '<p class="p-0">' . ($row->cate_name ? $row->cate_name : '...') . '</p><p class="p-0">' . ($row->sub_cate_name ? ' --- ' . $row->sub_cate_name : '') . '</p>')

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    return '<span class="text-success">Active</span>';
                } else {

                    return '<span class="text-danger">Inactive</span>';
                }
            })

            ->editColumn('access_locations', function ($row) use ($generalSettings, $request) {

                $productBranches = '';
                $query = DB::table('product_branches')->leftJoin('branches', 'product_branches.branch_id', 'branches.id')->where('product_branches.product_id', $row->id);
                if ($request->branch_id) {

                    if ($request->branch_id == 'NULL') {

                        $query->where('product_branches.branch_id', NULL);
                    } else {

                        $query->where('product_branches.branch_id', $request->branch_id);
                    }
                }

                if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                    $productBranches = $query->select('branches.name as b_name')->orderBy('product_branches.branch_id', 'asc')->get();
                }else {

                    $productBranches = $query->where('product_branches.branch_id', auth()->user()->branch_id)->select('branches.name as b_name')->orderBy('product_branches.branch_id', 'asc')->get();
                }

                $text = '';
                foreach ($productBranches as $productBranch) {

                    $text .= '<p class="m-0 p-0">'.($productBranch->b_name != null ? $productBranch->b_name : json_decode($generalSettings->business, true)['shop_name']).',</p>';
                }

                return $text;
            })
            ->editColumn('quantity', function ($row) use ($productStock, $request) {

                $quantity = $productStock->branchWiseSingleProductStock($row->id, $request->branch_id);
                return \App\Utils\Converter::format_in_bdt($quantity) . '/' . $row->unit_name;
            })
            ->editColumn('brand_name', fn ($row) => $row->brand_name ? $row->brand_name : '...')
            ->editColumn('tax_name', fn ($row) =>  $row->tax_name ? $row->tax_name : '...')
            ->rawColumns(['multiple_delete', 'photo', 'quantity', 'action', 'name', 'type', 'cate_name', 'status', 'expire_date', 'tax_name', 'brand_name', 'access_locations'])
            ->smart(true)->make(true);
    }

    public function addQuickCategory($request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addQuickCategory = new Category();
        $addQuickCategory->name = $request->name;
        $addQuickCategory->save();
        return response()->json($addQuickCategory);
    }

    public function addQuickBrand($request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addBrand = new Brand();
        $addBrand->name = $request->name;
        $addBrand->save();

        return response()->json($addBrand);
    }

    public function addQuickUnit($request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->save();
        return response()->json($addUnit);
    }

    public function addQuickWarranty($request)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required',
        ]);

        $add = new Warranty();
        $add->name = $request->name;
        $add->type = $request->type;
        $add->duration = $request->duration;
        $add->duration_type = $request->duration_type;
        $add->description = $request->description;
        $add->save();
        return response()->json($add);
    }

    // Add opening stock method
    public function addOpeningStock($branch_id, $product_id, $variant_id, $unit_cost_inc_tax, $quantity, $subtotal)
    {
        $addOpeningStock = new ProductOpeningStock();
        $addOpeningStock->branch_id = $branch_id;
        $addOpeningStock->product_id = $product_id;
        $addOpeningStock->product_variant_id = $variant_id;
        $addOpeningStock->unit_cost_inc_tax = $unit_cost_inc_tax;
        $addOpeningStock->quantity = $quantity;
        $addOpeningStock->subtotal = $subtotal;
        $addOpeningStock->save();

        $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
            tranColName: 'opening_stock_id',
            transId: $addOpeningStock->id,
            branchId: auth()->user()->branch_id,
            productId: $product_id,
            quantity: $quantity,
            variantId: $variant_id,
            unitCostIncTax: $unit_cost_inc_tax,
            sellingPrice: 0,
            subTotal: $subtotal,
            createdAt: date('Y-m-d H:i:s'),
        );
    }

    // Update opening stock method
    public function updateOpeningStock($openingStock, $unit_cost_inc_tax, $quantity, $subtotal)
    {
        $openingStock->unit_cost_inc_tax = $unit_cost_inc_tax;
        $openingStock->quantity = $quantity;
        $openingStock->subtotal = $subtotal;
        $openingStock->save();

        $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
            tranColName: 'opening_stock_id',
            transId: $openingStock->id,
            branchId: auth()->user()->branch_id,
            productId: $openingStock->product_id,
            quantity: $quantity,
            variantId: $openingStock->product_variant_id,
            unitCostIncTax: $unit_cost_inc_tax,
            sellingPrice: 0,
            subTotal: $subtotal,
            createdAt: date('Y-m-d H:i:s'),
        );
    }

    public function addOrUpdateProductInBranchAndUpdateStatus($request, $productId)
    {
        $product = Product::with('product_variants')->where('id', $productId)->first();

        if (isset($request->branch_count)) {

            $productBranches = ProductBranch::where('product_id', $product->id)->get();

            foreach ($productBranches as $productBranch) {

                $productBranch->status = 0;
                $productBranch->save();
            }

            foreach ($request->branch_ids as $branchId) {

                $this->addOrUpdateProductInBranchAndUpdateStatusPrivateMethod($product, $branchId);
            }
        } else {

            $this->addOrUpdateProductInBranchAndUpdateStatusPrivateMethod($product, auth()->user()->branch_id);
        }
    }

    private function addOrUpdateProductInBranchAndUpdateStatusPrivateMethod($product, $branchId)
    {
        $productBranch = ProductBranch::where('branch_id', $branchId)->where('product_id', $product->id)->first();

        if ($productBranch) {

            $productBranch->status = 1;
            $productBranch->save();

            if (count($product->product_variants) > 0) {

                foreach ($product->product_variants as $variant) {

                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant->product_id)->where('product_variant_id', $variant->id)->first();

                    if (!$productBranchVariant) {

                        $addProductBranchVariant = new ProductBranchVariant();
                        $addProductBranchVariant->product_branch_id = $productBranch->id;
                        $addProductBranchVariant->product_id = $variant->product_id;
                        $addProductBranchVariant->product_variant_id = $variant->id;
                        $addProductBranchVariant->save();
                    }
                }
            }
        } else {

            $addProductBranch = new ProductBranch();
            $addProductBranch->branch_id = $branchId;
            $addProductBranch->product_id = $product->id;
            $addProductBranch->save();

            if (count($product->product_variants) > 0) {

                foreach ($product->product_variants as $variant) {

                    $addProductBranchVariant = new ProductBranchVariant();
                    $addProductBranchVariant->product_branch_id = $addProductBranch->id;
                    $addProductBranchVariant->product_id = $variant->product_id;
                    $addProductBranchVariant->product_variant_id = $variant->id;
                    $addProductBranchVariant->save();
                }
            }
        }
    }
}
