<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('sender_account_id')->nullable();
            $table->unsignedBigInteger('receiver_account_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('supplier_payment_id')->nullable();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('expense_payment_id')->nullable();
            $table->unsignedBigInteger('money_receipt_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->unsignedBigInteger('payroll_payment_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->decimal('debit', 22, 2)->nullable();
            $table->decimal('credit', 22, 2)->nullable();
            $table->decimal('balance', 22, 2)->default(0.00);
            $table->tinyInteger('transaction_type')->comment("1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expensePayment;7=openingBalance;8=payroll_payment;9=money_receipt;10=loan-get/pay;11=loan_ins_payment/receive;12=supplier_payment;13=customer_payment");
            $table->tinyInteger('cash_type')->nullable()->comment("1=debit;2=credit;");
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('related_cash_flow_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('loan_payment_id')->nullable();
            
            $table->foreign('account_id', 'cash_flows_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('customer_payment_id', 'cash_flows_customer_payment_id_foreign')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('expense_payment_id', 'cash_flows_expense_payment_id_foreign')->references('id')->on('expense_payments')->onDelete('cascade');
            $table->foreign('loan_id', 'cash_flows_loan_id_foreign')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('loan_payment_id', 'cash_flows_loan_payment_id_foreign')->references('id')->on('loan_payments')->onDelete('cascade');
            $table->foreign('money_receipt_id', 'cash_flows_money_receipt_id_foreign')->references('id')->on('money_receipts')->onDelete('cascade');
            $table->foreign('payroll_id', 'cash_flows_payroll_id_foreign')->references('id')->on('hrm_payrolls')->onDelete('cascade');
            $table->foreign('payroll_payment_id', 'cash_flows_payroll_payment_id_foreign')->references('id')->on('hrm_payroll_payments')->onDelete('cascade');
            $table->foreign('purchase_payment_id', 'cash_flows_purchase_payment_id_foreign')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('receiver_account_id', 'cash_flows_receiver_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('sale_payment_id', 'cash_flows_sale_payment_id_foreign')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('sender_account_id', 'cash_flows_sender_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('supplier_payment_id', 'cash_flows_supplier_payment_id_foreign')->references('id')->on('supplier_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
}
