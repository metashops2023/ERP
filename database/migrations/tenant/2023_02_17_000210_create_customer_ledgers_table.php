<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('money_receipt_id')->nullable();
            $table->boolean('row_type')->default(1)->comment("1=sale;2=sale_payment;3=opening_balance;4=money_receipt;5=supplier_payment");
            $table->decimal('amount', 22, 2)->nullable()->comment("only_for_opening_balance");
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_advanced')->default(0)->comment("only_for_money_receipt");
            $table->timestamps();
            $table->string('voucher_type', 20)->nullable();
            $table->decimal('debit', 22, 2)->default(0.00);
            $table->decimal('credit', 22, 2)->default(0.00);
            $table->decimal('running_balance', 22, 2)->default(0.00);
            $table->string('amount_type', 20)->nullable()->comment("debit/credit");
            
            $table->foreign('branch_id', 'customer_ledgers_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'customer_ledgers_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customer_payment_id', 'customer_ledgers_customer_payment_id_foreign')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('money_receipt_id', 'customer_ledgers_money_receipt_id_foreign')->references('id')->on('money_receipts')->onDelete('cascade');
            $table->foreign('sale_id', 'customer_ledgers_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_payment_id', 'customer_ledgers_sale_payment_id_foreign')->references('id')->on('sale_payments')->onDelete('cascade');
            $table->foreign('sale_return_id', 'customer_ledgers_sale_return_id_foreign')->references('id')->on('sale_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_ledgers');
    }
}
