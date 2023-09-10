<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('parameter_quantity', 22, 2)->default(0.00);
            $table->decimal('input_qty', 22, 2)->default(0.00);
            $table->decimal('wastage_percent', 22, 2)->default(0.00);
            $table->decimal('final_qty', 22, 2)->default(0.00);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('subtotal', 22, 2)->default(0.00);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('product_id', 'production_ingredients_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('production_id', 'production_ingredients_production_id_foreign')->references('id')->on('productions')->onDelete('cascade');
            $table->foreign('unit_id', 'production_ingredients_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('variant_id', 'production_ingredients_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_ingredients');
    }
}
