<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('product_quantity', 22, 2)->default(0.00);
            $table->decimal('total_purchased', 22, 2)->default(0.00);
            $table->decimal('total_adjusted', 22, 2)->default(0.00);
            $table->decimal('total_transferred', 22, 2)->default(0.00);
            $table->decimal('total_received', 22, 2)->default(0.00);
            $table->decimal('total_sale_return', 22, 2)->default(0.00);
            $table->decimal('total_purchase_return', 22, 2)->default(0.00);
            $table->timestamps();
            
            $table->foreign('product_id', 'product_warehouses_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_id', 'product_warehouses_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_warehouses');
    }
}
