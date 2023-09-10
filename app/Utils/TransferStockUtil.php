<?php

namespace App\Utils;

use App\Models\Expense;
use App\Models\ExpensePayment;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDescription;
use Illuminate\Support\Facades\DB;

class TransferStockUtil
{
    public $accountUtil;
    public $invoiceVoucherRefIdUtil;
    public $expenseUtil;

    public function __construct(
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        ExpenseUtil $expenseUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->expenseUtil = $expenseUtil;
    }

    public function addExpenseFromTransferStock($request, $transfer_id)
    {
        $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($settings->prefix, true)['expenses'];
        $paymentInvoicePrefix = json_decode($settings->prefix, true)['expense_payment'];

        $transferCostCategory = DB::table('expense_categories')->where('name', 'Transferring Cost')->first();

        $expense_category_id;
        if (!$transferCostCategory) {

            $addGetId = ExpenseCategory::insertGetId([
                'name' => 'Transferring Cost',
                'code' => $this->invoiceVoucherRefIdUtil->getLastId('expense_categories'),
            ]);

            $expense_category_id = $addGetId;
        }

        $__expense_category_id = $transferCostCategory ? $transferCostCategory->id : $expense_category_id;

        $voucher_no = str_pad($this->invoiceVoucherRefIdUtil->getLastId('expenses'), 5, "0", STR_PAD_LEFT);

        $addExpense = new Expense();
        $addExpense->invoice_id = ($invoicePrefix != null ? $invoicePrefix : '') . $voucher_no;
        $addExpense->branch_id = auth()->user()->branch_id;
        $addExpense->category_ids = $__expense_category_id;
        $addExpense->total_amount = $request->transfer_cost;
        $addExpense->net_total_amount = $request->transfer_cost;
        $addExpense->paid = $request->transfer_cost;
        $addExpense->date = $request->date;
        $addExpense->month = date('F');
        $addExpense->year = date('Y');
        $addExpense->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        //$addExpense->admin_id = auth()->user()->id;
        $addExpense->expense_account_id = $request->ex_account_id;
        $addExpense->transfer_branch_to_branch_id = $transfer_id;
        $addExpense->note = $request->payment_note;
        $addExpense->save();

        // Add expense Description
        $addExDescription = new ExpenseDescription();
        $addExDescription->expense_id = $addExpense->id;
        $addExDescription->expense_category_id = $__expense_category_id;
        $addExDescription->amount = $request->transfer_cost;
        $addExDescription->save();

        // Add expense account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $addExpense->id,
            amount: $request->transfer_cost,
            balance_type: 'debit'
        );

        // Add Expense Payment
        $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
            voucher_prefix: $paymentInvoicePrefix,
            expense_id: $addExpense->id,
            request: $request,
            another_amount: $request->transfer_cost,
        );

        // Add bank account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 9,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $addPaymentGetId,
            amount: $request->transfer_cost,
            balance_type: 'debit'
        );
    }

    public function updateExpenseFromTransferStock($request, $transfer)
    {
        if ($request->transfer_cost != 0) {

            if ($transfer->expense) {
                
                $transfer->expense->total_amount = $request->transfer_cost;
                $transfer->expense->net_total_amount = $request->transfer_cost;
                $transfer->expense->paid = $request->transfer_cost;
                $transfer->expense->date = $request->date;
                $transfer->expense->month = date('F');
                $transfer->expense->year = date('Y');
                $transfer->expense->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                //$addExpense->admin_id = auth()->user()->id;
                $transfer->expense->expense_account_id = $request->ex_account_id;
                $transfer->expense->transfer_branch_to_branch_id = $transfer->id;
                $transfer->expense->note = $request->payment_note;
                $transfer->expense->save();

                // Add expense account Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 5,
                    date: $request->date,
                    account_id: $request->ex_account_id,
                    trans_id: $transfer->expense->id,
                    amount: $request->transfer_cost,
                    balance_type: 'debit'
                );

                $updateExDescription = ExpenseDescription::where('expense_id', $transfer->expense->id)->first();
                $updateExDescription->amount = $request->transfer_cost;
                $updateExDescription->save();

                $updateExpensePayment = ExpensePayment::where('expense_id', $transfer->expense->id)->first();

                //Update Expense payment
                $this->expenseUtil->updatePayment(
                    expensePayment : $updateExpensePayment, 
                    request : $request, 
                    another_amount : $request->transfer_cost
                );

                // Update Bank/Cash-In-Hand account Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 9,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $updateExpensePayment->id,
                    amount: $request->transfer_cost,
                    balance_type: 'debit'
                );

            } else {

                $this->addExpenseFromTransferStock($request, $transfer->id);
            }
        } else {
            
            $this->expenseUtil->expenseDelete($transfer->expense);
        }
    }

    public function deleteTransferBranchToBranch($deleteTransfer)
    {
        if ($deleteTransfer->expense) {

            $this->expenseUtil->expenseDelete($deleteTransfer->expense);
        }

        $deleteTransfer->delete();

    }

    public function getStockLimitProducts($transfer)
    {
        $qty_limits = [];

        foreach ($transfer->Transfer_products as $transfer_product) {

            if ($transfer->sender_warehouse_id) {

                $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $transfer->sender_warehouse_id)
                    ->where('product_id', $transfer_product->product_id)->first();

                if ($transfer_product->variant_id) {

                    $productWarehouseVariant = DB::table('product_warehouse_variants')
                        ->where('product_warehouse_id', $productWarehouse->id)
                        ->where('product_id', $transfer_product->product_id)
                        ->where('product_variant_id', $transfer_product->variant_id)
                        ->first();

                    $qty_limits[] = $productWarehouseVariant->variant_quantity;
                } else {

                    $qty_limits[] = $productWarehouse->product_quantity;
                }
            } else {

                $productBranch = DB::table('product_branches')
                    ->where('branch_id', $transfer->sender_branch_id)
                    ->where('product_id', $transfer_product->product_id)->first();

                if ($transfer_product->variant_id) {

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $transfer_product->product_id)
                        ->where('product_variant_id', $transfer_product->variant_id)
                        ->first();

                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {

                    $qty_limits[] = $productBranch->product_quantity;
                }
            }
        }

        return $qty_limits;
    }
}