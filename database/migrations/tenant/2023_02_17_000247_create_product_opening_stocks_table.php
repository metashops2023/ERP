<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOpeningStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00);
            $table->string('lot_no')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'product_opening_stocks_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id', 'product_opening_stocks_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'product_opening_stocks_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('warehouse_id', 'product_opening_stocks_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_opening_stocks');
    }
}
