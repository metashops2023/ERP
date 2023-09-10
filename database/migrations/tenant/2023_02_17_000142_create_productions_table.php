<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->tinyInteger('tax_type')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('date')->nullable();
            $table->string('time', 20)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('stock_warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('stock_branch_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('total_ingredient_cost', 22, 2)->nullable();
            $table->decimal('quantity', 22, 2)->nullable();
            $table->decimal('parameter_quantity', 22, 2)->default(0.00);
            $table->decimal('wasted_quantity', 22, 2)->nullable();
            $table->decimal('total_final_quantity', 22, 2)->default(0.00);
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0.00);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0.00);
            $table->decimal('x_margin', 22, 2)->default(0.00);
            $table->decimal('price_exc_tax', 22, 2)->default(0.00);
            $table->decimal('production_cost', 22, 2)->nullable();
            $table->decimal('total_cost', 22, 2)->nullable();
            $table->boolean('is_final')->default(0);
            $table->boolean('is_last_entry')->default(0);
            $table->boolean('is_default_price')->default(0);
            $table->unsignedBigInteger('production_account_id')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'productions_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id', 'productions_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('production_account_id', 'productions_production_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('stock_branch_id', 'productions_stock_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('stock_warehouse_id', 'productions_stock_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('tax_id', 'productions_tax_id_foreign')->references('id')->on('taxes')->onDelete('cascade');
            $table->foreign('unit_id', 'productions_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('variant_id', 'productions_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('warehouse_id', 'productions_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
