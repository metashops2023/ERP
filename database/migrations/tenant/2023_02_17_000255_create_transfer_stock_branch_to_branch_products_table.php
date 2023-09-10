<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockBranchToBranchProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_branch_to_branch_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('unit_price_inc_tax', 22, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00);
            $table->decimal('send_qty', 22, 2)->default(0.00);
            $table->decimal('received_qty', 22, 2)->default(0.00);
            $table->decimal('pending_qty', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'transfer_stock_branch_to_branch_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('transfer_id', 'transfer_stock_branch_to_branch_products_transfer_id_foreign')->references('id')->on('transfer_stock_branch_to_branches')->onDelete('cascade');
            $table->foreign('variant_id', 'transfer_stock_branch_to_branch_products_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_stock_branch_to_branch_products');
    }
}
