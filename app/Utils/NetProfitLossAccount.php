<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class NetProfitLossAccount
{
    public function netLossProfit($request = null): array
    {
        $directIncome = 0;
        $indirectIncome = 0;

        $totalSaleAmounts = $this->totalSaleAmounts($request);
        $closingStock = $this->closingStock($request);
        $openingStock = $this->openingStock($request);
        $stockAdjustments = $this->stockAdjustments($request);
        $totalPurchasesAmounts = $this->totalPurchasesAmounts($request);
        $totalSoldItemUnitCost = $this->totalSoldItemUnitCost($request);

        $individualProductSaleTax = $this->individualProductSaleTax($request);
        $directExpense = $this->directExpense($request);
        $indirectExpense  = $this->indirectExpense($request);
        $totalSaleReturn  = $this->totalSaleReturn($request);
        $totalPurchaseReturn  = $this->totalPurchaseReturn($request);
        $transferShipmentCost  = $this->transferShipmentCost($request = null);

        $grossProfit = $totalSaleAmounts->sum('total_sale')
            // + ($closingStock - $openingStock->sum('total_ops_value'))
            - $totalPurchasesAmounts->sum('total_purchase')
            - $directExpense->sum('total_di_expense')
            // - $totalSoldItemUnitCost->sum('total_unit_cost')
            - $totalSaleReturn->sum('total_sale_return')
            + $totalPurchaseReturn->sum('total_purchase_return');

        $netProfit = $grossProfit
            // + ($closingStock - $openingStock->sum('total_ops_value'))
            + $stockAdjustments->sum('total_recovered')
            // - $totalSoldItemUnitCost->sum('total_unit_cost')
            - $totalSaleAmounts->sum('total_sale_order_tax')
            - $individualProductSaleTax->sum('total_sale_pro_tax')
            - $indirectExpense->sum('total_indi_expense')
            - $stockAdjustments->sum('total_adjusted');

        $netProfitBeforeTax = $grossProfit
            // + ($closingStock - $openingStock->sum('total_ops_value'))
            + $stockAdjustments->sum('total_recovered')
            - $totalPurchasesAmounts->sum('total_purchase')
            // - $totalSoldItemUnitCost->sum('total_unit_cost')
            - $directExpense->sum('total_di_expense')
            - $indirectExpense->sum('total_indi_expense')
            - $stockAdjustments->sum('total_adjusted');

        $tax_payable = $totalSaleAmounts->sum('total_sale_order_tax')
            + $individualProductSaleTax->sum('total_sale_pro_tax');

        return [
            'total_sale' => $totalSaleAmounts->sum('total_sale'),
            'total_sale_paid' => $totalSaleAmounts->sum('total_paid'),
            'total_sale_due' => $totalSaleAmounts->sum('total_due'),
            'opening_stock' => $openingStock->sum('total_ops_value'),
            'closing_stock' => $closingStock,
            'total_purchase' => $totalPurchasesAmounts->sum('total_purchase'),
            'total_purchase_paid' => $totalPurchasesAmounts->sum('total_paid'),
            'total_purchase_due' => $totalPurchasesAmounts->sum('total_due'),
            'total_unit_cost' => $totalSoldItemUnitCost->sum('total_unit_cost'),
            'total_sale_order_tax' => $totalSaleAmounts->sum('total_sale_order_tax'),
            'individual_product_sale_tax' => $individualProductSaleTax->sum('total_sale_pro_tax'),
            'total_sale_return' => $totalSaleReturn->sum('total_sale_return'),
            'total_purchase_return' => $totalPurchaseReturn->sum('total_purchase_return'),

            'total_sale_order_tax' => $totalSaleAmounts->sum('total_sale_order_tax'),
            'total_sale_pro_tax' => $individualProductSaleTax->sum('total_sale_pro_tax'),
            'total_direct_expense' => $directExpense->sum('total_di_expense'),
            'total_indirect_expense' => $indirectExpense->sum('total_indi_expense'),
            'total_adjusted' => $stockAdjustments->sum('total_adjusted'),
            'total_adjusted_recovered' => $stockAdjustments->sum('total_recovered'),
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'net_profit_before_tax' => $netProfitBeforeTax,
            'tax_payable' => $tax_payable,
            'total_transfer_cost' => $transferShipmentCost,
        ];
    }

    public function closingStock($request = NULL)
    {
        $purchaseProduct = '';
        $saleProducts = '';

        $purchaseProductQ = DB::table('purchase_products')
            ->select(DB::raw('SUM(net_unit_cost * quantity) as total_value'));

        $saleProductsQ = DB::table('purchase_sale_product_chains')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->select(DB::raw('SUM(purchase_products.net_unit_cost * purchase_sale_product_chains.sold_qty) as total_value'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $purchaseProductQ->where('purchase_products.branch_id', NULL);
                $saleProductsQ->where('sales.branch_id', NULL);
            } else {

                $purchaseProductQ->where('purchase_products.branch_id', $request->branch_id);
                $saleProductsQ->where('sales.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $purchaseProductQ->whereBetween('purchase_products.created_at', $date_range);
            $saleProductsQ->whereBetween('sales.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $purchaseProduct = $purchaseProductQ->get();
            $saleProducts = $saleProductsQ->get();
        } else {

            $purchaseProduct = $purchaseProductQ->where('purchase_products.branch_id', auth()->user()->branch_id)->get();
            $saleProducts = $saleProductsQ->where('sales.branch_id', auth()->user()->branch_id)->get();
        }

        return $purchaseProduct->sum('total_value') - $saleProducts->sum('total_value');
    }

    public function totalPurchasesAmounts($request = null)
    {
        $purchases = '';

        $purchasesQ = DB::table('purchases')
            ->select(
                DB::raw('SUM(total_purchase_amount) as total_purchase'),
                DB::raw('SUM(paid) as total_paid'),
                DB::raw('SUM(due) as total_due'),
                DB::raw('SUM(purchase_tax_amount) as total_pur_order_tax'),
                DB::raw('SUM(purchase_tax_amount) as total_pur_order_tax'),
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $purchasesQ->where('purchases.branch_id', NULL);
            } else {

                $purchasesQ->where('purchases.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $purchasesQ->whereBetween('purchases.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $purchases = $purchasesQ->get();
        } else {

            return $purchases = $purchasesQ->where('purchases.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function totalSaleAmounts($request = null)
    {
        $sales = '';
        $salesQ = DB::table('sales')
            ->whereIn('sales.status', [1, 3])
            ->select(
                DB::raw('SUM(total_payable_amount) as total_sale'),
                DB::raw('SUM(paid) as total_paid'),
                DB::raw('SUM(due) as total_due'),
                DB::raw('SUM(order_tax_amount) as total_sale_order_tax'),
                DB::raw('SUM(shipment_charge) as total_shipment_charge'),
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $salesQ->where('sales.branch_id', NULL);
            } else {

                $salesQ->where('sales.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $salesQ->whereBetween('sales.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $sales = $salesQ->get();
        } else {

            return $sales = $salesQ->where('sales.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function openingStock($request = null)
    {
        $openingStock = '';
        $openingStockQ = DB::table('product_opening_stocks')
            ->select(DB::raw('SUM(quantity * unit_cost_inc_tax) as total_ops_value'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $openingStockQ->where('product_opening_stocks.branch_id', NULL);
            } else {

                $openingStockQ->where('product_opening_stocks.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $openingStockQ->whereBetween('product_opening_stocks.created_at', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $openingStock = $openingStockQ->get();
        } else {

            return $openingStock = $openingStockQ->where('product_opening_stocks.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function individualProductSaleTax($request = null)
    {
        $individualProductSaleTax = '';
        $individualProductSaleTaxQ = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(DB::raw('SUM(unit_tax_amount) as total_sale_pro_tax'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $individualProductSaleTaxQ->where('sales.branch_id', NULL);
            } else {

                $individualProductSaleTaxQ->where('sales.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $individualProductSaleTaxQ->whereBetween('sales.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $individualProductSaleTax = $individualProductSaleTaxQ->get();
        } else {

            return $individualProductSaleTax = $individualProductSaleTaxQ->where('sales.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function directExpense($request = null)
    {
        $directExpense = '';
        $directExpenseQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->where('accounts.account_type', 7)
            ->select(DB::raw('SUM(net_total_amount) as total_di_expense'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $directExpenseQ->where('expenses.branch_id', NULL);
            } else {

                $directExpenseQ->where('expenses.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $directExpenseQ->whereBetween('expenses.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $directExpense = $directExpenseQ->get();
        } else {

            return $directExpense = $directExpenseQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function indirectExpense($request = null)
    {
        $indirectExpense = '';

        $indirectExpenseQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->where('accounts.account_type', 8)
            ->select(DB::raw('SUM(net_total_amount) as total_indi_expense'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $indirectExpenseQ->where('expenses.branch_id', NULL);
            } else {

                $indirectExpenseQ->where('expenses.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $indirectExpenseQ->whereBetween('expenses.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $indirectExpense = $indirectExpenseQ->get();
        } else {

            return $indirectExpense = $indirectExpenseQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function stockAdjustments($request = null)
    {
        $stockAdjustments = '';

        $stockAdjustmentsQ = DB::table('stock_adjustments')
            ->select(
                DB::raw('SUM(net_total_amount) as total_adjusted'),
                DB::raw('SUM(recovered_amount) as total_recovered'),
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $stockAdjustmentsQ->where('stock_adjustments.branch_id', NULL);
            } else {

                $stockAdjustmentsQ->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $stockAdjustmentsQ->whereBetween('stock_adjustments.report_date_ts', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            return $stockAdjustments = $stockAdjustmentsQ->get();
        } else {

            return $stockAdjustments = $stockAdjustmentsQ->where('stock_adjustments.branch_id', auth()->user()->branch_id)->get();
        }
    }

    public function totalSoldItemUnitCost($request = null)
    {
        $totalSoldItemUnitCost = '';
        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(purchase_products.net_unit_cost * purchase_sale_product_chains.sold_qty) as total_unit_cost')
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $saleProductQuery->where('sales.branch_id', NULL);
            } else {

                $saleProductQuery->where('sales.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $saleProductQuery->whereBetween('sales.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $totalSoldItemUnitCost = $saleProductQuery->where('sales.status', 1)->get();
        } else {

            $totalSoldItemUnitCost = $saleProductQuery->where('sales.status', 1)
                ->where('sales.branch_id', auth()->user()->branch_id)
                ->get();
        }

        return $totalSoldItemUnitCost;
    }

    public function totalSaleReturn($request = null)
    {
        $totalSaleReturn = '';
        $totalSaleReturnQuery = DB::table('sale_returns')
            ->select(
                DB::raw('SUM(sale_returns.total_return_amount) as total_sale_return')
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $totalSaleReturnQuery->where('sale_returns.branch_id', NULL);
            } else {

                $totalSaleReturnQuery->where('sale_returns.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $totalSaleReturnQuery->whereBetween('sale_returns.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $totalSaleReturn = $totalSaleReturnQuery->get();
        } else {

            $totalSaleReturn = $totalSaleReturnQuery
                ->where('sale_returns.branch_id', auth()->user()->branch_id)
                ->get();
        }

        return $totalSaleReturn;
    }

    public function totalPurchaseReturn($request = null)
    {

        $totalPurchaseReturn = '';
        $totalPurchaseReturnQuery = DB::table('purchase_returns')
            ->select(
                DB::raw('SUM(purchase_returns.total_return_amount) as total_purchase_return')
            );

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $totalPurchaseReturnQuery->where('purchase_returns.branch_id', NULL);
            } else {

                $totalPurchaseReturnQuery->where('purchase_returns.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $totalPurchaseReturnQuery->whereBetween('purchase_returns.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $totalPurchaseReturn = $totalPurchaseReturnQuery->get();
        } else {

            $totalPurchaseReturn = $totalPurchaseReturnQuery
                ->where('purchase_returns.branch_id', auth()->user()->branch_id)
                ->get();
        }

        return $totalPurchaseReturn;
    }

    public function transferShipmentCost($request = null)
    {
        $transferStBranchQuery = DB::table('transfer_stock_to_branches')
            ->select(DB::raw('sum(shipping_charge) as b_total_shipment_charge'));

        $transferStWarehouseQuery = DB::table('transfer_stock_to_warehouses')
            ->select(DB::raw('sum(shipping_charge) as w_total_shipment_charge'));

            
        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', NULL);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', NULL);
            } else {

                $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];

            $transferStBranchQuery->whereBetween('transfer_stock_to_branches.report_date', $date_range);
            $transferStWarehouseQuery->whereBetween('transfer_stock_to_warehouses.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $transferStBranch = $transferStBranchQuery->get();
            $transferStWarehouse = $transferStWarehouseQuery->get();
        } else {

            $transferStBranch = $transferStBranchQuery->where('transfer_stock_to_branches.branch_id', auth()->user()->branch_id)->get();
            $transferStWarehouse = $transferStWarehouseQuery->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id)->get();
        }

        return $totalTransferShipmentCost = $transferStBranch->sum('b_total_shipment_charge') + $transferStWarehouse->sum('w_total_shipment_charge');
    }
}
