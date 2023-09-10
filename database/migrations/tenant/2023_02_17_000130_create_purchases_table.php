<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->tinyInteger('pay_term')->nullable();
            $table->bigInteger('pay_term_number')->nullable();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('order_discount', 22, 2)->default(0.00);
            $table->boolean('order_discount_type')->default(1);
            $table->decimal('order_discount_amount', 22, 2)->default(0.00);
            $table->string('shipment_details')->nullable();
            $table->decimal('shipment_charge', 22, 2)->default(0.00);
            $table->mediumText('purchase_note')->nullable();
            $table->unsignedBigInteger('purchase_tax_id')->nullable();
            $table->decimal('purchase_tax_percent', 22, 2)->default(0.00);
            $table->decimal('purchase_tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_purchase_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->decimal('purchase_return_amount', 22, 2)->default(0.00);
            $table->decimal('purchase_return_due', 22, 2)->default(0.00);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->boolean('purchase_status')->default(1);
            $table->boolean('is_purchased')->default(1);
            $table->string('date')->nullable();
            $table->string('delivery_date', 20)->nullable();
            $table->string('time', 191)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_last_created')->default(0);
            $table->boolean('is_return_available')->default(0);
            $table->string('attachment')->nullable();
            $table->decimal('po_qty', 22, 2)->default(0.00);
            $table->decimal('po_pending_qty', 22, 2)->default(0.00);
            $table->decimal('po_received_qty', 22, 2)->default(0.00);
            $table->string('po_receiving_status', 20)->nullable()->comment("This field only for order, which numeric status = 3");
            $table->timestamps();
            $table->unsignedBigInteger('purchase_account_id')->nullable();
            
            $table->foreign('branch_id', 'purchases_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('purchase_account_id', 'purchases_purchase_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('supplier_id', 'purchases_supplier_id_foreign')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('warehouse_id', 'purchases_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
