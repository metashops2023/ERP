<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('sale_product_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('sold_quantity', 22, 2)->default(0.00);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('unit_price_exc_tax', 22, 2)->default(0.00);
            $table->decimal('unit_price_inc_tax', 22, 2)->default(0.00);
            $table->boolean('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22, 2)->default(0.00);
            $table->decimal('unit_discount_amount', 22, 2)->default(0.00);
            $table->boolean('tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0.00);
            $table->decimal('unit_tax_amount', 22, 2)->default(0.00);
            $table->decimal('return_qty', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('return_subtotal', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'sale_return_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'sale_return_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('sale_product_id', 'sale_return_products_sale_product_id_foreign')->references('id')->on('sale_products')->onDelete('cascade');
            $table->foreign('sale_return_id', 'sale_return_products_sale_return_id_foreign')->references('id')->on('sale_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_products');
    }
}
