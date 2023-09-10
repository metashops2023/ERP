<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('product_quantity', 22, 2)->default(0.00);
            $table->boolean('status')->default(1);
            $table->decimal('total_sale', 22, 2)->default(0.00);
            $table->decimal('total_purchased', 22, 2)->default(0.00);
            $table->decimal('total_adjusted', 22, 2)->default(0.00);
            $table->decimal('total_transferred', 22, 2)->default(0.00);
            $table->decimal('total_received', 22, 2)->default(0.00);
            $table->decimal('total_opening_stock', 22, 2)->default(0.00);
            $table->decimal('total_sale_return', 22, 2)->default(0.00);
            $table->decimal('total_purchase_return', 22, 2)->default(0.00);
            $table->timestamps();
            
            $table->foreign('branch_id', 'product_branches_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id', 'product_branches_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_branches');
    }
}
