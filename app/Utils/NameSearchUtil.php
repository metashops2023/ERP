<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class NameSearchUtil
{
    public function nameSearching($keyword)
    {
        $namedProducts = '';

        $namedProducts = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'products.id',
                'products.name',
                'products.product_code',
                'products.is_combo',
                'products.is_manage_stock',
                // 'products.is_purchased',
                'products.is_show_emi_on_pos',
                'products.is_variant',
                'products.product_cost',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.profit',
                // 'products.quantity',
                'products.tax_id',
                'products.tax_type',
                'products.thumbnail_photo',
                'products.type',
                'products.unit_id',
                'taxes.tax_name',
                'taxes.tax_percent',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_profit',
                'product_variants.variant_price',
                'units.name as unit_name',
            )
            ->where('products.is_for_sale', 1)
            ->where('products.status', 1)
            ->where('product_branches.status', 1)
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->where('products.name', 'LIKE',  $keyword . '%')->orderBy('id', 'desc')->limit(25)
            ->get();

        if ($namedProducts && count($namedProducts) > 0) {

            return response()->json(['namedProducts' => $namedProducts]);
        } else {

            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }

    public function searchStockToBranch($product, $product_code, $branch_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL, $isCheckStock = true)
    {
        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', $branch_id)
                ->where('product_id', $product->id)
                ->where('status', 1)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {

                if ($product->is_manage_stock == 0) {

                    return response()->json(
                        [
                            'product' => $product,
                            'qty_limit' => PHP_INT_MAX,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        ]
                    );
                }

                if ($status == 2 || $status == 3 || $status == 4) {

                    return response()->json(
                        [
                            'product' => $product,
                            'qty_limit' => $productBranch ? $productBranch->product_quantity : 0,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        ]
                    );
                }

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not sellable in this demo']);
                } else {

                    if ($productBranch->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product of this Business Location']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in this Business Location.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'updateVariantCost', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_quantity',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')
                    ->where('branch_id', $branch_id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('status', 1)
                    ->first();

                if (is_null($productBranch)) {

                    return response()->json(['errorMsg' => 'Product(Variant) is not available in Business Location']);
                }

                if ($variant_product->product->is_manage_stock == 0) {

                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                    ]);
                }

                $productBranchVariant = DB::table('product_branch_variants')
                    ->where('product_branch_id', $productBranch->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->select('variant_quantity')
                    ->first();

                if (is_null($productBranchVariant)) {

                    return response()->json(['errorMsg' => 'Product(Variant) is not available in Business Location']);
                }

                if ($productBranch && $productBranchVariant) {

                    if ($status == 2 || $status == 3 || $status == 4) {

                        return response()->json(
                            [
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant ? $productBranchVariant->variant_quantity : 0,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                            ]
                        );
                    }

                    if ($productBranchVariant->variant_quantity > 0) {

                        return response()->json([
                            'variant_product' => $variant_product,
                            'qty_limit' => $productBranchVariant->variant_quantity,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                        ]);
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) from the Business Location']);
                    }
                } else {

                    return response()->json(['errorMsg' => 'This product is not available in Business Location.']);
                }
            }
        }

        return $this->nameSearching($product_code);
    }

    public function addSaleSearchStockToWarehouse($product, $product_code, $warehouse_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL, $isCheckStock = true)
    {
        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', auth()->user()->branch_id)
                ->where('product_id', $product->id)->where('status', 1)->first();

            if (!$productBranch) {

                return response()->json(['errorMsg' => 'Product is not available in business Location']);
            }

            if ($product->is_manage_stock == 0) {

                return response()->json(
                    [
                        'product' => $product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    ]
                );
            }

            $productWarehouse = DB::table('product_warehouses')
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productWarehouse) {

                if ($status == 2 || $status == 3 || $status == 4) {

                    return response()->json(
                        [
                            'product' => $product,
                            'qty_limit' => $productWarehouse ? $productWarehouse->product_quantity : 0,
                            'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        ]
                    );
                }

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not sellable in this demo']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product in the selected warehouse']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in the selected warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'updateVariantCost', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id',
                    'product_id',
                    'variant_name',
                    'variant_code',
                    'variant_quantity',
                    'variant_cost',
                    'variant_cost_with_tax',
                    'variant_profit',
                    'variant_price',
                ])->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                    ->where('product_id', $variant_product->product->id)
                    ->where('status', 1)->first();

                if (!$productBranch) {

                    return response()->json(['errorMsg' => 'Product(Variant) is not available in Business Location']);
                }

                if ($variant_product->product->is_manage_stock == 0) {

                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => PHP_INT_MAX,
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                    ]);
                }

                if ($variant_product) {

                    $productWarehouse = DB::table('product_warehouses')
                        ->where('warehouse_id', $warehouse_id)
                        ->where('product_id', $variant_product->product_id)
                        ->first();

                    if (is_null($productWarehouse)) {

                        return response()->json(['errorMsg' => 'This product is not available in the selected warehouse']);
                    }

                    $productWarehouseVariant = DB::table('product_warehouse_variants')
                        ->where('product_warehouse_id', $productWarehouse->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productWarehouseVariant)) {

                        return response()->json(['errorMsg' => 'Product variant is not available in the selected warehouse']);
                    }

                    if ($productWarehouse && $productWarehouseVariant) {

                        if ($status == 2 || $status == 3 || $status == 4) {

                            return response()->json(
                                [
                                    'variant_product' => $variant_product,
                                    'qty_limit' => $productWarehouseVariant ? $productWarehouseVariant->variant_quantity : 0,
                                    'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                                ]
                            );
                        }

                        if ($productWarehouseVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity,
                                'discount' => $is_allowed_discount == true ? $this->productDiscount($variant_product->product_id, $price_group_id, $variant_product->product->brand_id, $variant_product->product->category_id) : null,
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this product variant in the selected warehouse']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'Product is not available in the selected warehouse.']);
                    }
                }
            }
        }

        return $this->nameSearching($product_code);
    }

    public function checkBranchSingleProductStock($product_id, $branch_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id')
            ->first();

        $productBranch = DB::table('product_branches')
            ->where('product_id', $product_id)
            ->where('branch_id', $branch_id)->first();

        if ($productBranch) {

            if ($product->is_manage_stock == 0) {

                return response()->json(
                    [
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => PHP_INT_MAX,
                    ]
                );
            }

            if ($status == 2 || $status == 3 || $status == 4) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productBranch->product_quantity,
                ]);
            }

            if ($productBranch->product_quantity > 0) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productBranch->product_quantity,
                ]);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of the Business Location.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in the Business Location.']);
        }
    }

    public function checkAddSaleWarehouseSingleProductStock($product_id, $warehouse_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json(
                [
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => PHP_INT_MAX,
                ]
            );
        }

        $productWarehouse = DB::table('product_warehouses')
            ->where('product_id', $product_id)
            ->where('warehouse_id', $warehouse_id)->first();

        if ($productWarehouse) {

            if ($status == 2 || $status == 3 || $status == 4) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productWarehouse->product_quantity,
                ]);
            }

            if ($productWarehouse->product_quantity > 0) {

                return response()->json([
                    'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                    'stock' => $productWarehouse->product_quantity,
                ]);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) in the selected warehouse.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in the selected warehouse.']);
        }
    }

    public function checkBranchVariantProductStock($product_id, $variant_id, $branch_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json([
                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                'stock' => PHP_INT_MAX
            ]);
        }

        $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product_id)->first();

        if ($productBranch) {

            $productBranchVariant = DB::table('product_branch_variants')
                ->where('product_branch_id', $productBranch->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($productBranchVariant) {

                if ($status == 2 || $status == 3 || $status == 4) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productBranchVariant->variant_quantity
                    ]);
                }

                if ($productBranchVariant->variant_quantity > 0) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' =>  $productBranchVariant->variant_quantity
                    ]);
                } else {

                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) from the Business Location']);
                }
            } else {

                return response()->json(['errorMsg' => 'This variant is not available in the Business Location.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in the Business Location.']);
        }
    }

    public function checkAddSaleWarehouseVariantProductStock($product_id, $variant_id, $warehouse_id, $status = NULL, $is_allowed_discount = false, $price_group_id = NULL)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock', 'brand_id', 'category_id')
            ->first();

        if ($product->is_manage_stock == 0) {

            return response()->json([
                'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                'stock' => PHP_INT_MAX
            ]);
        }

        $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();

        if ($productWarehouse) {

            $productWarehouseVariant = DB::table('product_warehouse_variants')
                ->where('product_warehouse_id', $productWarehouse->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($productWarehouseVariant) {

                if ($status == 2 || $status == 3 || $status == 4) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' => $productWarehouseVariant->variant_quantity
                    ]);
                }

                if ($productWarehouseVariant->variant_quantity > 0) {

                    return response()->json([
                        'discount' => $is_allowed_discount == true ? $this->productDiscount($product->id, $price_group_id, $product->brand_id, $product->category_id) : null,
                        'stock' =>  $productWarehouseVariant->variant_quantity
                    ]);
                } else {

                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) in the selected warehouse.']);
                }
            } else {

                return response()->json(['errorMsg' => 'This variant is not available in the selected warehouse..']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in the selected warehouse.']);
        }
    }

    public function checkWarehouseSingleProduct($product_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();

        if ($productWarehouse) {

            if ($productWarehouse->product_quantity > 0) {

                return response()->json($productWarehouse->product_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product from this warehouse']);
            }
        } else {

            return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
        }
    }

    // Check warehouse product variant qty 
    public function checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        if (is_null($productWarehouse)) {

            return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
        }

        $productWarehouseVariant = DB::table('product_warehouse_variants')
            ->where('product_warehouse_id', $productWarehouse->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();

        if (is_null($productWarehouseVariant)) {

            return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
        }

        if ($productWarehouse && $productWarehouseVariant) {

            if ($productWarehouseVariant->variant_quantity > 0) {

                return response()->json($productWarehouseVariant->variant_quantity);
            } else {

                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse.']);
            }
        } else {

            return response()->json(['errorMsg' => 'This variant is not available in this Business Location.']);
        }
    }

    public function searchStockToWarehouse($product, $product_code, $warehouse_id)
    {
        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', auth()->user()->branch_id)
                ->where('product_id', $product->id)->where('status', 1)->first();

            if (!$productBranch) {

                return response()->json(['errorMsg' => 'Product is not available in business Location']);
            }

            $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->first();

            if ($productWarehouse) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')
                    ->where('branch_id', auth()->user()->branch_id)
                    ->where('product_id', $variant_product->product_id)->where('status', 1)->first();

                if (!$productBranch) {

                    return response()->json(['errorMsg' => 'Product is not available in business Location']);
                }

                $productWarehouse = DB::table('product_warehouses')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $variant_product->product_id)
                    ->first();

                if (is_null($productWarehouse)) {

                    return response()->json(['errorMsg' => 'Product is not available in the warehouse']);
                }

                $productWarehouseVariant = DB::table('product_warehouse_variants')
                    ->where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->first();

                if (is_null($productWarehouseVariant)) {

                    return response()->json(['errorMsg' => 'Product(variant) is not available in the warehouse']);
                }

                if ($productWarehouse && $productWarehouseVariant) {

                    if ($productWarehouseVariant->variant_quantity > 0) {

                        return response()->json(
                            [
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                    }
                } else {

                    return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
                }
            }
        }

        return $this->nameSearching($product_code);
    }

    public function productDiscount($product_id, $price_group_id, $brand_id, $category_id)
    {
        $presentDate = date('Y-m-d');

        $__price_group_id = $price_group_id != 'no_id' ? $price_group_id : NULL;
        $__category_id = $category_id ? $category_id : NULL;
        $__brand_id = $brand_id ? $brand_id : NULL;

        $discountProductWise = DB::table('discount_products')
            ->where('discount_products.product_id', $product_id)
            ->leftJoin('discounts', 'discount_products.discount_id', 'discounts.id')
            ->where('discounts.branch_id', auth()->user()->branch_id)
            ->where('discounts.is_active', 1)
            ->where('discounts.price_group_id', $__price_group_id)
            ->whereRaw('"' . $presentDate . '" between `start_at` and `end_at`')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        if ($discountProductWise) {

            return $this->setDiscount($discountProductWise);
        }

        $discountBrandCategoryWise = '';
        $discountBrandCategoryWiseQ = DB::table('discounts')
            ->where('discounts.branch_id', auth()->user()->branch_id)
            ->where('discounts.is_active', 1)
            //->where('discounts.price_group_id', $__price_group_id)
            ->whereRaw('"' . $presentDate . '" between `start_at` and `end_at`');

        if ($__brand_id && $__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', NULL);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', NULL);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__category_id);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brand_id);
        } elseif ($__brand_id && !$__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.category_id', NULL);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', '!=', NULL);
            $discountBrandCategoryWiseQ->where('discounts.brand_id', $__brand_id);
        } elseif (!$__brand_id && $__category_id) {

            $discountBrandCategoryWiseQ->where('discounts.brand_id', NULL);
            $discountBrandCategoryWiseQ->where('discounts.category_id', '!=', NULL);
            $discountBrandCategoryWiseQ->where('discounts.category_id', $__category_id);
        }

        $discountBrandCategoryWise = $discountBrandCategoryWiseQ
            ->select('discounts.discount_type', 'discounts.discount_amount', 'discounts.apply_in_customer_group')
            ->orderBy('discounts.priority', 'desc')
            ->first();

        return $this->setDiscount($discountBrandCategoryWise);

        // if (!$discountBrandCategoryWise) {

        //     return $this->setDiscount(NULL);
        // }

        // if ($discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id && $discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     } 
        // } elseif (!$discountBrandCategoryWise->brand_id && $discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->category_id == $__category_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // } elseif ($discountBrandCategoryWise->brand_id && !$discountBrandCategoryWise->category_id) {

        //     if ($discountBrandCategoryWise->brand_id == $__brand_id) {

        //         return $this->setDiscount($discountBrandCategoryWise);
        //     }
        // }
    }

    public function setDiscount($discount)
    {
        $discountDetails = [];
        $discountDetails['discount_type'] = isset($discount->discount_type) ? $discount->discount_type : 1;
        $discountDetails['discount_amount'] = isset($discount->discount_amount) ? $discount->discount_amount : 0;
        //$discountDetails['apply_in_customer_group'] = isset($discount->apply_in_customer_group) ? $discount->apply_in_customer_group : 0;

        return $discountDetails;
    }
}
