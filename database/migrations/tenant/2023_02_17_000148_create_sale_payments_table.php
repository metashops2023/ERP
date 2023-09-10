<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->boolean('payment_on')->default(1)->comment("1=sale_invoice_due;2=customer_due");
            $table->boolean('payment_type')->default(1)->comment("1=sale_due;2=return_due");
            $table->tinyInteger('payment_status')->nullable()->comment("1=due;2=partial;3=paid");
            $table->string('date');
            $table->string('time');
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date')->useCurrent()->useCurrentOnUpdate();
            $table->string('card_no')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_transaction_no')->nullable();
            $table->string('card_month')->nullable();
            $table->string('card_year')->nullable();
            $table->string('card_secure_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('account_id', 'sale_payments_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('branch_id', 'sale_payments_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'sale_payments_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customer_payment_id', 'sale_payments_customer_payment_id_foreign')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('payment_method_id', 'sale_payments_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('set NULL');
            $table->foreign('sale_id', 'sale_payments_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_return_id', 'sale_payments_sale_return_id_foreign')->references('id')->on('sale_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_payments');
    }
}
