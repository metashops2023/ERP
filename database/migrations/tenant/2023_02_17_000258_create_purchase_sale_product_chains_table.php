<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseSaleProductChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_sale_product_chains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_product_id')->nullable();
            $table->unsignedBigInteger('sale_product_id')->nullable();
            $table->decimal('sold_qty', 22, 2)->default(0.00);
            $table->timestamps();
            
            $table->foreign('purchase_product_id', 'purchase_sale_product_chains_purchase_product_id_foreign')->references('id')->on('purchase_products')->onDelete('cascade');
            $table->foreign('sale_product_id', 'purchase_sale_product_chains_sale_product_id_foreign')->references('id')->on('sale_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_sale_product_chains');
    }
}
