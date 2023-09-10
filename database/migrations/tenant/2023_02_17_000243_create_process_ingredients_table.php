<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('wastage_percent', 22, 2)->default(0.00);
            $table->decimal('wastage_amount', 22, 2)->default(0.00);
            $table->decimal('final_qty', 22, 2)->default(0.00);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 8, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('process_id', 'process_ingredients_process_id_foreign')->references('id')->on('processes')->onDelete('cascade');
            $table->foreign('product_id', 'process_ingredients_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id', 'process_ingredients_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('variant_id', 'process_ingredients_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_ingredients');
    }
}
