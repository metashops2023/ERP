<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->comment("This column for track branch wise FIFO/LIFO method.");
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 22, 2)->default(0.00);
            $table->decimal('unit_discount', 22, 2)->default(0.00);
            $table->decimal('unit_cost_with_discount', 22, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00)->comment("Without_tax");
            $table->decimal('unit_tax_percent', 22, 2)->default(0.00);
            $table->decimal('unit_tax', 22, 2)->default(0.00);
            $table->decimal('net_unit_cost', 22, 2)->default(0.00)->comment("With_tax");
            $table->decimal('line_total', 22, 2)->default(0.00);
            $table->decimal('profit_margin', 22, 2)->default(0.00);
            $table->decimal('selling_price', 22, 2)->default(0.00);
            $table->mediumText('description')->nullable();
            $table->boolean('is_received')->default(0);
            $table->string('lot_no', 191)->nullable();
            $table->boolean('delete_in_update')->default(0);
            $table->unsignedBigInteger('product_order_product_id')->nullable()->comment("when product add from purchase_order_products table");
            $table->timestamps();
            $table->decimal('left_qty', 22, 2)->default(0.00);
            $table->unsignedBigInteger('production_id')->nullable();
            $table->unsignedBigInteger('opening_stock_id')->nullable();
            $table->unsignedBigInteger('sale_return_product_id')->nullable();
            $table->unsignedBigInteger('transfer_branch_to_branch_product_id')->nullable();
            
            $table->foreign('branch_id', 'purchase_products_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('opening_stock_id', 'purchase_products_opening_stock_id_foreign')->references('id')->on('product_opening_stocks')->onDelete('cascade');
            $table->foreign('product_id', 'purchase_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_order_product_id', 'purchase_products_product_order_product_id_foreign')->references('id')->on('purchase_order_products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'purchase_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('production_id', 'purchase_products_production_id_foreign')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('purchase_id', 'purchase_products_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('sale_return_product_id', 'purchase_products_sale_return_product_id_foreign')->references('id')->on('sale_return_products')->onDelete('cascade');
            $table->foreign('transfer_branch_to_branch_product_id', 'purchase_products_transfer_branch_to_branch_product_id_foreign')->references('id')->on('transfer_stock_branch_to_branch_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_products');
    }
}
