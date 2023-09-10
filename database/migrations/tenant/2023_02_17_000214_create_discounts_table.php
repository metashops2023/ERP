<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->bigInteger('priority')->default(0);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('discount_type')->default(0);
            $table->decimal('discount_amount', 22, 2)->default(0.00);
            $table->unsignedBigInteger('price_group_id')->nullable();
            $table->boolean('apply_in_customer_group')->default(0);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            
            $table->foreign('branch_id', 'discounts_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('brand_id', 'discounts_brand_id_foreign')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id', 'discounts_category_id_foreign')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('price_group_id', 'discounts_price_group_id_foreign')->references('id')->on('price_groups')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
