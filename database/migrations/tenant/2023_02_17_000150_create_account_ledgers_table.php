<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('voucher_type', 50)->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('expense_payment_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('supplier_payment_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('adjustment_id')->nullable();
            $table->unsignedBigInteger('stock_adjustment_recover_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->unsignedBigInteger('payroll_payment_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('loan_payment_id')->nullable();
            $table->unsignedBigInteger('contra_credit_id')->nullable();
            $table->unsignedBigInteger('contra_debit_id')->nullable();
            $table->decimal('debit', 22, 2)->default(0.00);
            $table->decimal('credit', 22, 2)->default(0.00);
            $table->decimal('running_balance', 22, 2)->default(0.00);
            $table->string('amount_type', 20)->nullable()->comment("debit/credit");
            $table->timestamps();
            
            $table->foreign('account_id', 'account_ledgers_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('adjustment_id', 'account_ledgers_adjustment_id_foreign')->references('id')->on('stock_adjustments')->onDelete('cascade');
            $table->foreign('branch_id', 'account_ledgers_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('contra_credit_id', 'account_ledgers_contra_credit_id_foreign')->references('id')->on('contras')->onDelete('cascade');
            $table->foreign('contra_debit_id', 'account_ledgers_contra_debit_id_foreign')->references('id')->on('contras')->onDelete('cascade');
            $table->foreign('customer_payment_id', 'account_ledgers_customer_payment_id_foreign')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('expense_id', 'account_ledgers_expense_id_foreign')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('expense_payment_id', 'account_ledgers_expense_payment_id_foreign')->references('id')->on('expense_payments')->onDelete('cascade');
            $table->foreign('loan_id', 'account_ledgers_loan_id_foreign')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('loan_payment_id', 'account_ledgers_loan_payment_id_foreign')->references('id')->on('loan_payments')->onDelete('cascade');
            $table->foreign('payroll_id', 'account_ledgers_payroll_id_foreign')->references('id')->on('hrm_payrolls')->onDelete('cascade');
            $table->foreign('payroll_payment_id', 'account_ledgers_payroll_payment_id_foreign')->references('id')->on('hrm_payroll_payments')->onDelete('cascade');
            $table->foreign('production_id', 'account_ledgers_production_id_foreign')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('purchase_id', 'account_ledgers_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_payment_id', 'account_ledgers_purchase_payment_id_foreign')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('purchase_return_id', 'account_ledgers_purchase_return_id_foreign')->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->foreign('sale_id', 'account_ledgers_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_payment_id', 'account_ledgers_sale_payment_id_foreign')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('sale_return_id', 'account_ledgers_sale_return_id_foreign')->references('id')->on('sale_returns')->onDelete('cascade');
            $table->foreign('stock_adjustment_recover_id', 'account_ledgers_stock_adjustment_recover_id_foreign')->references('id')->on('stock_adjustment_recovers')->onDelete('cascade');
            $table->foreign('supplier_payment_id', 'account_ledgers_supplier_payment_id_foreign')->references('id')->on('supplier_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
}
