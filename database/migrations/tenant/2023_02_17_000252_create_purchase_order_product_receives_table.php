<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderProductReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_product_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_product_id');
            $table->string('purchase_challan')->nullable();
            $table->unsignedBigInteger('lot_number')->nullable();
            $table->string('received_date')->nullable();
            $table->decimal('qty_received', 22, 2)->default(0.00);
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('order_product_id', 'purchase_order_product_receives_order_product_id_foreign')->references('id')->on('purchase_order_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_product_receives');
    }
}
