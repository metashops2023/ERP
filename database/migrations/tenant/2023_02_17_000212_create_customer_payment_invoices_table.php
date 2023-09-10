<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_payment_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->tinyInteger('type')->nullable()->comment("1=sale_payment;2=sale_return_payment");
            $table->timestamps();
            
            $table->foreign('customer_payment_id', 'customer_payment_invoices_customer_payment_id_foreign')->references('id')->on('customer_payments')->onDelete('cascade');
            $table->foreign('sale_id', 'customer_payment_invoices_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_return_id', 'customer_payment_invoices_sale_return_id_foreign')->references('id')->on('sale_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payment_invoices');
    }
}
