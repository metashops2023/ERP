<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToWarehouseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_to_warehouse_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('unit_price', 22, 2);
            $table->decimal('quantity', 22, 2);
            $table->decimal('received_qty', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('subtotal', 22, 2);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'transfer_stock_to_warehouse_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'transfer_stock_to_warehouse_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('transfer_stock_id', 'transfer_stock_to_warehouse_products_transfer_stock_id_foreign')->references('id')->on('transfer_stock_to_warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_stock_to_warehouse_products');
    }
}
