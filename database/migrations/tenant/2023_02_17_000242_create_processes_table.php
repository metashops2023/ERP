<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('total_ingredient_cost', 22, 2)->default(0.00);
            $table->decimal('wastage_percent', 8, 2)->default(0.00);
            $table->decimal('wastage_amount', 8, 2)->default(0.00);
            $table->decimal('total_output_qty', 22, 2)->default(0.00);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('production_cost', 22, 2)->default(0.00);
            $table->decimal('total_cost', 22, 2)->default(0.00);
            $table->text('process_instruction')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'processes_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id', 'processes_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('unit_id', 'processes_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('variant_id', 'processes_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
    }
}
