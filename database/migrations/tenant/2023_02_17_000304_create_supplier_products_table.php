<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->bigInteger('label_qty')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'supplier_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'supplier_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('supplier_id', 'supplier_products_supplier_id_foreign')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_products');
    }
}
