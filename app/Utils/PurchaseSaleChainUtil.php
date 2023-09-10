<?php

namespace App\Utils;

use App\Utils\PurchaseUtil;
use App\Models\PurchaseProduct;

class PurchaseSaleChainUtil
{
    protected $purchaseUtil;

    public function __construct(
        PurchaseUtil $purchaseUtil,
    ) {
        $this->purchaseUtil = $purchaseUtil;
    }

    public function addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
        $tranColName,
        $transId,
        $branchId,
        $productId,
        $quantity,
        $variantId,
        $unitCostIncTax,
        $sellingPrice,
        $subTotal,
        $createdAt,
        $xMargin = 0
    ) {

        $purchaseProduct = PurchaseProduct::where($tranColName, $transId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($purchaseProduct) {

            $purchaseProduct->net_unit_cost = $unitCostIncTax;
            $purchaseProduct->quantity = $quantity;
            $purchaseProduct->line_total = $subTotal;
            $purchaseProduct->profit_margin = $xMargin;
            $purchaseProduct->selling_price = $sellingPrice;
            $purchaseProduct->created_at = $createdAt;
            $purchaseProduct->save();
            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
        } else {

            $addRowInPurchaseProductTable = new PurchaseProduct();
            $addRowInPurchaseProductTable->branch_id = $branchId;
            $addRowInPurchaseProductTable->{$tranColName} = $transId;
            $addRowInPurchaseProductTable->product_id = $productId;
            $addRowInPurchaseProductTable->product_variant_id = $variantId;
            $addRowInPurchaseProductTable->net_unit_cost = $unitCostIncTax;
            $addRowInPurchaseProductTable->quantity = $quantity;
            $addRowInPurchaseProductTable->left_qty = $quantity;
            $addRowInPurchaseProductTable->line_total = $subTotal;
            $addRowInPurchaseProductTable->profit_margin = $xMargin;
            $addRowInPurchaseProductTable->selling_price = $sellingPrice;
            $addRowInPurchaseProductTable->created_at = $createdAt;
            $addRowInPurchaseProductTable->save();
        }
    }
}
