<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('order_quantity', 22, 2)->default(0.00);
            $table->decimal('received_quantity', 22, 2)->default(0.00);
            $table->decimal('pending_quantity', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 22, 2)->default(0.00);
            $table->decimal('unit_discount', 22, 2)->default(0.00);
            $table->decimal('unit_cost_with_discount', 10, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00)->comment("Without_tax");
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->decimal('unit_tax_percent', 22, 2)->default(0.00);
            $table->decimal('unit_tax', 22, 2)->default(0.00);
            $table->decimal('net_unit_cost', 22, 2)->default(0.00)->comment("inc_tax");
            $table->decimal('ordered_unit_cost', 22, 2)->default(0.00)->comment("inc_tax");
            $table->decimal('line_total', 22, 2)->default(0.00);
            $table->decimal('profit_margin', 22, 2)->default(0.00);
            $table->decimal('selling_price', 22, 2)->default(0.00);
            $table->mediumText('description')->nullable();
            $table->string('lot_no')->nullable();
            $table->boolean('delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'purchase_order_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'purchase_order_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('purchase_id', 'purchase_order_products_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_products');
    }
}
