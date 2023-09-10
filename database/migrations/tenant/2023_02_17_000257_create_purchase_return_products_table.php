<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_return_id');
            $table->unsignedBigInteger('purchase_product_id')->nullable()->comment("this_field_only_for_purchase_invoice_return.");
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('unit_cost', 8, 2)->default(0.00);
            $table->decimal('return_qty', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('return_subtotal', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'purchase_return_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'purchase_return_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('purchase_product_id', 'purchase_return_products_purchase_product_id_foreign')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('purchase_return_id', 'purchase_return_products_purchase_return_id_foreign')->references('id')->on('purchase_returns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return_products');
    }
}
