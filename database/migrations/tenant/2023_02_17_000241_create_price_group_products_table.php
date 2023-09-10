<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_group_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_group_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('price', 22, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('price_group_id', 'price_group_products_price_group_id_foreign')->references('id')->on('price_groups')->onDelete('cascade');
            $table->foreign('product_id', 'price_group_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id', 'price_group_products_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_group_products');
    }
}
