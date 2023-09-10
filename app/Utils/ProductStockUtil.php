<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;

class ProductStockUtil
{
    public function adjustMainProductAndVariantStock($product_id, $variant_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productOpeningStock = DB::table('product_opening_stocks')
                ->where('product_id', $product_id)
                ->select(DB::raw('sum(quantity) as po_stock'))
                ->groupBy('product_id')->get();

            $productPurchase = DB::table('purchase_products')
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', NULL)
                ->where('purchase_products.production_id', NULL)
                ->where('purchase_products.sale_return_product_id', NULL)
                ->where('purchase_products.transfer_branch_to_branch_product_id', NULL)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $productionQty = DB::table('productions')->where('productions.is_final', 1)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $totalPurchaseReturn = DB::table('purchase_return_products')
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_id')->get();

            $totalSaleReturn = DB::table('sale_return_products')
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $productCurrentStock = $productPurchase->sum('total_purchase')
                + $productOpeningStock->sum('po_stock')
                + $totalSaleReturn->sum('total_return')
                - $productSale->sum('total_sale')
                - $adjustment->sum('total_qty')
                - $totalPurchaseReturn->sum('total_return')
                + $productionQty->sum('total_quantity')
                - $usedProductionQty->sum('total_quantity');

            $product = Product::where('id', $product_id)->first();
            $product->quantity = $productCurrentStock;
            $product->number_of_sale = $productSale->sum('total_sale');
            $product->total_adjusted = $adjustment->sum('total_qty');
            $product->save();

            if ($variant_id) {

                $variantOpeningStock = DB::table('product_opening_stocks')
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as vo_stock'))
                    ->groupBy('product_variant_id')->get();

                $variantPurchase = DB::table('purchase_products')
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->where('purchase_products.opening_stock_id', NULL)
                    ->where('purchase_products.production_id', NULL)
                    ->where('purchase_products.sale_return_product_id', NULL)
                    ->where('purchase_products.transfer_branch_to_branch_product_id', NULL)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')
                    ->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.product_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.product_id')->get();

                $variantSale = DB::table('sale_products')
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $totalPurchaseReturn = DB::table('purchase_return_products')
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('product_variant_id')->get();

                $totalSaleReturn = DB::table('sale_return_products')
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('product_variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $variantCurrentStock = $variantPurchase->sum('total_purchase')
                    + $variantOpeningStock->sum('vo_stock')
                    + $totalSaleReturn->sum('total_return')
                    - $variantSale->sum('total_sale')
                    - $adjustment->sum('total_qty')
                    - $totalPurchaseReturn->sum('total_return')
                    + $productionQty->sum('total_quantity')
                    - $usedProductionQty->sum('total_quantity');

                $variant = ProductVariant::where('id', $variant_id)->first();
                $variant->variant_quantity = $variantCurrentStock;
                $variant->number_of_sale = $variantSale->sum('total_sale');
                $variant->total_adjusted = $adjustment->sum('total_qty');
                $variant->save();
            }
        }
    }

    public function adjustBranchStock($product_id, $variant_id, $branch_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productOpeningStock = DB::table('product_opening_stocks')
                ->where('product_opening_stocks.branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->select(DB::raw('sum(quantity) as po_stock'))
                ->groupBy('product_opening_stocks.product_id')->get();

            $productionQty = DB::table('productions')->where('is_final', 1)
                ->where('productions.branch_id', $branch_id)->where('warehouse_id', NULL)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('productions.branch_id', $branch_id)->where('productions.warehouse_id', NULL)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.stock_branch_id', $branch_id)
                ->where('sale_products.stock_warehouse_id', NULL)
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', $branch_id)
                ->where('purchases.warehouse_id', NULL)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', NULL)
                ->where('purchase_products.production_id', NULL)
                ->where('purchase_products.sale_return_product_id', NULL)
                ->where('purchase_products.transfer_branch_to_branch_product_id', NULL)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $saleReturn = DB::table('sale_return_products')
                ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->where('sale_returns.branch_id', $branch_id)
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('sale_return_products.product_id')->get();

            $purchaseReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', $branch_id)
                ->where('purchases.warehouse_id', NULL)
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $supplierReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->where('purchase_returns.purchase_id', NULL)
                ->where('purchase_returns.branch_id', $branch_id)
                ->where('purchase_returns.warehouse_id', NULL)
                ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $transferred = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.branch_id', $branch_id)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

            $transferredToAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                ->where('transfer_stock_branch_to_branches.sender_branch_id', $branch_id)
                ->where('transfer_stock_branch_to_branches.sender_warehouse_id', NULL)
                ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_branch_to_branch_products.product_id')->get();

            $received = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.branch_id', $branch_id)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_id')->get();

            $receivedFromAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                ->where('transfer_stock_branch_to_branches.receiver_branch_id', $branch_id)
                ->where('transfer_stock_branch_to_branches.receiver_warehouse_id', NULL)
                ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_branch_to_branch_products.product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->where('stock_adjustments.branch_id', $branch_id)
                ->where('stock_adjustments.warehouse_id', NULL)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $currentMbStock = $productOpeningStock->sum('po_stock')
                + $productPurchase->sum('total_purchase')
                - $productSale->sum('total_sale')
                + $saleReturn->sum('total_return')
                - $supplierReturn->sum('total_return')
                - $purchaseReturn->sum('total_return')
                - $transferred->sum('total_qty')
                - $transferredToAnotherLocation->sum('total_qty')
                - $adjustment->sum('total_qty')
                + $received->sum('total_qty')
                + $productionQty->sum('total_quantity')
                + $receivedFromAnotherLocation->sum('total_qty')
                - $usedProductionQty->sum('total_quantity');

            $totalReceived = $received->sum('total_qty') + $receivedFromAnotherLocation->sum('total_qty');
            $totalTransferred = $transferred->sum('total_qty') + $transferredToAnotherLocation->sum('total_qty');

            $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product_id)->first();
            $productBranch->product_quantity = $currentMbStock;
            $productBranch->total_sale = $productSale->sum('total_sale');
            $productBranch->total_purchased = $productPurchase->sum('total_purchase');
            $productBranch->total_adjusted = $adjustment->sum('total_qty');
            $productBranch->total_transferred = $totalTransferred;
            $productBranch->total_received = $totalReceived;
            $productBranch->total_opening_stock = $productOpeningStock->sum('po_stock');
            $productBranch->total_sale_return = $saleReturn->sum('total_return');
            $productBranch->total_purchase_return = $supplierReturn->sum('total_return') + $purchaseReturn->sum('total_return');
            $productBranch->save();

            if ($variant_id) {

                $productOpeningStock = DB::table('product_opening_stocks')
                    ->where('product_opening_stocks.branch_id', $branch_id)
                    ->where('product_opening_stocks.product_id', $product_id)
                    ->where('product_opening_stocks.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as po_stock'))
                    ->groupBy('product_opening_stocks.product_variant_id')->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('productions.branch_id', $branch_id)->where('warehouse_id', NULL)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.variant_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('productions.branch_id', $branch_id)->where('productions.warehouse_id', NULL)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.variant_id')->get();

                $productSale = DB::table('sale_products')
                    ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                    ->where('sale_products.stock_branch_id', $branch_id)
                    ->where('sale_products.stock_warehouse_id', NULL)
                    ->where('sale_products.product_id', $product_id)
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->where('sales.status', 1)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $productPurchase = DB::table('purchase_products')
                    ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                    ->where('purchases.branch_id', $branch_id)
                    ->where('purchases.warehouse_id', NULL)
                    ->where('purchase_products.product_id', $product_id)
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->where('purchase_products.opening_stock_id', NULL)
                    ->where('purchase_products.production_id', NULL)
                    ->where('purchase_products.sale_return_product_id', NULL)
                    ->where('purchase_products.transfer_branch_to_branch_product_id', NULL)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')->get();

                $saleReturn = DB::table('sale_return_products')
                    ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                    ->where('sale_returns.branch_id', $branch_id)
                    ->where('sale_return_products.product_id', $product_id)
                    ->where('sale_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('sale_return_products.product_variant_id')->get();

                $purchaseReturn = DB::table('purchase_return_products')
                    ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                    ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                    ->where('purchases.branch_id', $branch_id)
                    ->where('purchases.warehouse_id', NULL)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_variant_id')->get();

                $supplierReturn = DB::table('purchase_return_products')
                    ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                    ->where('purchase_returns.purchase_id', NULL)
                    ->where('purchase_returns.branch_id', $branch_id)
                    ->where('purchase_returns.warehouse_id', NULL)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_variant_id')->get();

                $transferred = DB::table('transfer_stock_to_warehouse_products')
                    ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                    ->where('transfer_stock_to_warehouses.branch_id', $branch_id)
                    ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                    ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();

                $transferredToAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                    ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                    ->where('transfer_stock_branch_to_branches.sender_branch_id', $branch_id)
                    ->where('transfer_stock_branch_to_branches.sender_warehouse_id', NULL)
                    ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_branch_to_branch_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_branch_to_branch_products.variant_id')->get();

                $received = DB::table('transfer_stock_to_branch_products')
                    ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                    ->where('transfer_stock_to_branches.branch_id', $branch_id)
                    ->where('transfer_stock_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

                $receivedFromAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                    ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                    ->where('transfer_stock_branch_to_branches.receiver_branch_id', $branch_id)
                    ->where('transfer_stock_branch_to_branches.receiver_warehouse_id', NULL)
                    ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_branch_to_branch_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_branch_to_branch_products.variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                    ->where('stock_adjustments.branch_id', $branch_id)
                    ->where('stock_adjustments.warehouse_id', NULL)
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $currentMbStock = $productOpeningStock->sum('po_stock')
                    + $productPurchase->sum('total_purchase')
                    - $productSale->sum('total_sale')
                    + $saleReturn->sum('total_return')
                    - $supplierReturn->sum('total_return')
                    - $purchaseReturn->sum('total_return')
                    - $transferred->sum('total_qty')
                    - $transferredToAnotherLocation->sum('total_qty')
                    - $adjustment->sum('total_qty')
                    + $received->sum('total_qty')
                    + $productionQty->sum('total_quantity')
                    + $receivedFromAnotherLocation->sum('total_qty')
                    - $usedProductionQty->sum('total_quantity');

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                $totalReceived = $received->sum('total_qty') + $receivedFromAnotherLocation->sum('total_qty');
                $totalTransferred = $transferred->sum('total_qty') + $transferredToAnotherLocation->sum('total_qty');

                $productBranchVariant->variant_quantity = $currentMbStock;
                $productBranchVariant->total_sale = $productSale->sum('total_sale');
                $productBranchVariant->total_purchased = $productPurchase->sum('total_purchase');
                $productBranchVariant->total_adjusted = $adjustment->sum('total_qty');
                $productBranchVariant->total_transferred = $totalTransferred;
                $productBranchVariant->total_received = $totalReceived;
                $productBranchVariant->total_opening_stock = $productOpeningStock->sum('po_stock');
                $productBranchVariant->total_sale_return = $saleReturn->sum('total_return');
                $productBranchVariant->total_purchase_return = $supplierReturn->sum('total_return') + $purchaseReturn->sum('total_return');
                $productBranchVariant->save();
            }
        }
    }

    public function adjustWarehouseStock($product_id, $variant_id, $warehouse_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.warehouse_id', $warehouse_id)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.opening_stock_id', NULL)
                ->where('purchase_products.production_id', NULL)
                ->where('purchase_products.sale_return_product_id', NULL)
                ->where('purchase_products.transfer_branch_to_branch_product_id', NULL)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_id')->get();

            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sale_products.stock_warehouse_id', $warehouse_id)
                ->where('sale_products.product_id', $product_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_id')->get();

            $productionQty = DB::table('productions')->where('is_final', 1)
                ->where('productions.warehouse_id', $warehouse_id)
                ->where('productions.product_id', $product_id)
                ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                ->groupBy('productions.product_id')->get();

            $usedProductionQty = DB::table('production_ingredients')
                ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                ->where('productions.is_final', 1)
                ->where('productions.stock_warehouse_id', $warehouse_id)
                ->where('production_ingredients.product_id', $product_id)
                ->select(DB::raw('sum(input_qty) as total_quantity'))
                ->groupBy('production_ingredients.product_id')->get();

            $purchaseReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->where('purchases.warehouse_id', $warehouse_id)
                ->where('product_id', $product_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $supplierReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->where('purchase_returns.purchase_id', NULL)
                ->where('purchase_returns.warehouse_id', $warehouse_id)
                ->where('purchase_return_products.product_id', $product_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_id')->get();

            $received = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

            $receivedFromAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                ->where('transfer_stock_branch_to_branches.receiver_warehouse_id', $warehouse_id)
                ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_branch_to_branch_products.product_id')->get();

            $transferred = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_id')->get();

            $transferredToAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                ->where('transfer_stock_branch_to_branches.sender_warehouse_id', $warehouse_id)
                ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                ->select(DB::raw('sum(received_qty) as total_qty'))
                ->groupBy('transfer_stock_branch_to_branch_products.product_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->where('stock_adjustments.warehouse_id', $warehouse_id)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_id')->get();

            $currentMbStock = $productPurchase->sum('total_purchase')
                - $productSale->sum('total_sale')
                - $purchaseReturn->sum('total_return')
                - $supplierReturn->sum('total_return')
                - $transferred->sum('total_qty')
                - $transferredToAnotherLocation->sum('total_qty')
                - $adjustment->sum('total_qty')
                + $received->sum('total_qty')
                + $productionQty->sum('total_quantity')
                + $receivedFromAnotherLocation->sum('total_qty')
                - $usedProductionQty->sum('total_quantity');

            $totalReceived = $received->sum('total_qty') + $receivedFromAnotherLocation->sum('total_qty');
            $totalTransferred = $transferred->sum('total_qty') + $transferredToAnotherLocation->sum('total_qty');

            $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();
            $productWarehouse->product_quantity = $currentMbStock;
            $productWarehouse->total_purchased = $productPurchase->sum('total_purchase');
            $productWarehouse->total_adjusted = $adjustment->sum('total_qty');
            $productWarehouse->total_transferred = $totalTransferred;
            $productWarehouse->total_received = $totalReceived;
            $productWarehouse->save();

            if ($variant_id) {

                $productPurchase = DB::table('purchase_products')
                    ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                    ->where('purchases.warehouse_id', $warehouse_id)
                    ->where('purchase_products.product_id', $product_id)
                    ->where('purchase_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_purchase'))
                    ->groupBy('purchase_products.product_variant_id')->get();

                $productSale = DB::table('sale_products')
                    ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                    ->where('sale_products.stock_warehouse_id', $warehouse_id)
                    ->where('sale_products.product_id', $product_id)
                    ->where('sale_products.product_variant_id', $variant_id)
                    ->where('sales.status', 1)
                    ->select(DB::raw('sum(quantity) as total_sale'))
                    ->groupBy('sale_products.product_variant_id')->get();

                $productionQty = DB::table('productions')->where('is_final', 1)
                    ->where('productions.warehouse_id', $warehouse_id)
                    ->where('productions.product_id', $product_id)
                    ->where('productions.variant_id', $variant_id)
                    ->select(DB::raw('sum(total_final_quantity) as total_quantity'))
                    ->groupBy('productions.variant_id')->get();

                $usedProductionQty = DB::table('production_ingredients')
                    ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
                    ->where('productions.is_final', 1)
                    ->where('productions.stock_warehouse_id', $warehouse_id)
                    ->where('production_ingredients.product_id', $product_id)
                    ->where('production_ingredients.variant_id', $variant_id)
                    ->select(DB::raw('sum(input_qty) as total_quantity'))
                    ->groupBy('production_ingredients.variant_id')->get();

                $purchaseReturn = DB::table('purchase_return_products')
                    ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                    ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                    ->where('purchases.warehouse_id', $warehouse_id)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_variant_id')->get();

                $supplierReturn = DB::table('purchase_return_products')
                    ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                    ->where('purchase_returns.purchase_id', NULL)
                    ->where('purchase_returns.warehouse_id', $warehouse_id)
                    ->where('purchase_return_products.product_id', $product_id)
                    ->where('purchase_return_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(return_qty) as total_return'))
                    ->groupBy('purchase_return_products.product_variant_id')->get();

                $received = DB::table('transfer_stock_to_warehouse_products')
                    ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                    ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
                    ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                    ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();

                $receivedFromAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                    ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                    ->where('transfer_stock_branch_to_branches.receiver_warehouse_id', $warehouse_id)
                    ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_branch_to_branch_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_branch_to_branch_products.variant_id')->get();

                $transferred = DB::table('transfer_stock_to_branch_products')
                    ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                    ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
                    ->where('transfer_stock_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

                $transferredToAnotherLocation = DB::table('transfer_stock_branch_to_branch_products')
                    ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id')
                    ->where('transfer_stock_branch_to_branches.sender_warehouse_id', $warehouse_id)
                    ->where('transfer_stock_branch_to_branch_products.product_id', $product_id)
                    ->where('transfer_stock_branch_to_branch_products.variant_id', $variant_id)
                    ->select(DB::raw('sum(received_qty) as total_qty'))
                    ->groupBy('transfer_stock_branch_to_branch_products.variant_id')->get();

                $adjustment = DB::table('stock_adjustment_products')
                    ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                    ->where('stock_adjustments.warehouse_id', $warehouse_id)
                    ->where('stock_adjustment_products.product_id', $product_id)
                    ->where('stock_adjustment_products.product_variant_id', $variant_id)
                    ->select(DB::raw('sum(quantity) as total_qty'))
                    ->groupBy('stock_adjustment_products.product_variant_id')->get();

                $currentMbStock = $productPurchase->sum('total_purchase')
                    - $productSale->sum('total_sale')
                    - $purchaseReturn->sum('total_return')
                    - $supplierReturn->sum('total_return')
                    - $transferred->sum('total_qty')
                    - $transferredToAnotherLocation->sum('total_qty')
                    - $adjustment->sum('total_qty')
                    + $received->sum('total_qty')
                    + $productionQty->sum('total_quantity')
                    + $receivedFromAnotherLocation->sum('total_qty')
                    - $usedProductionQty->sum('total_quantity');

                $totalReceived = $received->sum('total_qty') + $receivedFromAnotherLocation->sum('total_qty');
                $totalTransferred = $transferred->sum('total_qty') + $transferredToAnotherLocation->sum('total_qty');

                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                $productWarehouseVariant->variant_quantity = $currentMbStock;
                $productWarehouseVariant->total_purchased = $productPurchase->sum('total_purchase');
                $productWarehouseVariant->total_adjusted = $adjustment->sum('total_qty');
                $productWarehouseVariant->total_transferred = $totalTransferred;
                $productWarehouseVariant->total_received = $totalReceived;
                $productWarehouseVariant->save();
            }
        }
    }

    public function addWarehouseProduct($product_id, $variant_id, $warehouse_id)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {
            $checkExistsProductInWarehouse = DB::table('product_warehouses')
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product_id)->first();

            if ($checkExistsProductInWarehouse) {
                if ($variant_id) {
                    $checkVariantInWarehouse = DB::table('product_warehouse_variants')
                        ->where('product_warehouse_id', $checkExistsProductInWarehouse->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)
                        ->first();
                    if (!$checkVariantInWarehouse) {
                        $productWarehouseVariant = new ProductWarehouseVariant();
                        $productWarehouseVariant->product_warehouse_id = $checkExistsProductInWarehouse->id;
                        $productWarehouseVariant->product_id = $product_id;
                        $productWarehouseVariant->product_variant_id = $variant_id;
                        $productWarehouseVariant->save();
                    }
                }
            } else {
                $productWarehouse = new ProductWarehouse();
                $productWarehouse->warehouse_id = $warehouse_id;
                $productWarehouse->product_id = $product_id;
                $productWarehouse->save();
                if ($variant_id) {
                    $productWarehouseVariant = new ProductWarehouseVariant();
                    $productWarehouseVariant->product_warehouse_id = $productWarehouse->id;
                    $productWarehouseVariant->product_id = $product_id;
                    $productWarehouseVariant->product_variant_id = $variant_id;
                    $productWarehouseVariant->save();
                }
            }
        }
    }

    public function addBranchProduct($product_id, $variant_id, $branch_id, $force_add = 0)
    {
        $product = DB::table('products')
            ->where('id', $product_id)->select('id', 'is_manage_stock')
            ->first();

        $checkExistsProductInBranch = DB::table('product_branches')
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)->first();

        if ($checkExistsProductInBranch) {

            if ($variant_id) {

                $checkVariantInBranch = DB::table('product_branch_variants')
                    ->where('product_branch_id', $checkExistsProductInBranch->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                if (!$checkVariantInBranch) {

                    $productBranchVariant = new ProductBranchVariant();
                    $productBranchVariant->product_branch_id = $checkExistsProductInBranch->id;
                    $productBranchVariant->product_id = $product_id;
                    $productBranchVariant->product_variant_id = $variant_id;
                    $productBranchVariant->save();
                }
            }
        } else {

            $productBranch = new ProductBranch();
            $productBranch->branch_id = auth()->user()->branch_id;
            $productBranch->product_id = $product_id;
            $productBranch->save();

            if ($variant_id) {

                $productBranchVariant = new ProductBranchVariant();
                $productBranchVariant->product_branch_id = $productBranch->id;
                $productBranchVariant->product_id = $product_id;
                $productBranchVariant->product_variant_id = $variant_id;
                $productBranchVariant->save();
            }
        }
    }

    public function branchWiseSingleProductStock($product_id, $branch_id)
    {
        $productBranches = '';
        $productWarehouse = '';
    
        $productBranchesQuery = DB::table('product_branches')->where('product_id', $product_id);
    
        $productWarehouseQuery = DB::table('product_warehouses')->where('product_id', $product_id)
            ->leftJoin('warehouse_branches', 'product_warehouses.warehouse_id', 'warehouse_branches.warehouse_id');
    
        if ($branch_id) {
    
            if ($branch_id == 'NULL') {
    
                $productBranchesQuery->where('product_branches.branch_id', NULL);
                $productWarehouseQuery->where('warehouse_branches.branch_id', NULL)->where('warehouse_branches.is_global', 0);
            } else {
    
                $productBranchesQuery->where('product_branches.branch_id', $branch_id);
                $productWarehouseQuery->where('warehouse_branches.branch_id', $branch_id)->where('warehouse_branches.is_global', 0);
            }
        }
    
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
    
            $productBranches = $productBranchesQuery->select(DB::raw('SUM(product_branches.product_quantity) as total_branch_stock'))
                ->groupBy('product_branches.product_id')->get();
    
            $productWarehouse = $productWarehouseQuery->select(DB::raw('SUM(product_warehouses.product_quantity) as total_warehouse_stock'))
                ->groupBy('product_warehouses.product_id')->get();
        } else {
    
            $productBranches = $productBranchesQuery->where('product_branches.branch_id', auth()->user()->branch_id)
                ->select(DB::raw('SUM(product_branches.product_quantity) as total_branch_stock'))->groupBy('product_branches.product_id')->get();
    
            $productWarehouse = $productWarehouseQuery->where('warehouse_branches.branch_id', auth()->user()->branch_id)
                ->where('warehouse_branches.is_global', 0)
                ->select(DB::raw('SUM(product_warehouses.product_quantity) as total_warehouse_stock'))
                ->groupBy('product_warehouses.product_id')->get();
        }
    
        return $productBranches->sum('total_branch_stock') + $productWarehouse->sum('total_warehouse_stock');
    }
}
