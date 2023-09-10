<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->decimal('less_amount', 22, 2)->default(0.00);
            $table->timestamp('report_date')->nullable();
            $table->boolean('type')->default(1);
            $table->string('pay_mode')->nullable();
            $table->string('date');
            $table->string('time');
            $table->string('month');
            $table->string('year');
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
            $table->text('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            
            $table->foreign('account_id', 'customer_payments_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('admin_id', 'customer_payments_admin_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
            $table->foreign('branch_id', 'customer_payments_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'customer_payments_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('payment_method_id', 'customer_payments_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payments');
    }
}
