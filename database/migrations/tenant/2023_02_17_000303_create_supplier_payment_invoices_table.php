<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('supplier_return_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->timestamps();
            $table->boolean('type')->default(1)->comment("1=purchase_due;2=purchase_return_due");
            
            $table->foreign('purchase_id', 'supplier_payment_invoices_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('supplier_payment_id', 'supplier_payment_invoices_supplier_payment_id_foreign')->references('id')->on('supplier_payments')->onDelete('cascade');
            $table->foreign('supplier_return_id', 'supplier_payment_invoices_supplier_return_id_foreign')->references('id')->on('purchase_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payment_invoices');
    }
}
