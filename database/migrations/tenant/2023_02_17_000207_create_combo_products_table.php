<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('combo_product_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->boolean('delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('combo_product_id', 'combo_products_combo_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_id', 'combo_products_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'combo_products_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combo_products');
    }
}
