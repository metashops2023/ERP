<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->tinyInteger('type')->comment("1=general,2=combo,3=digital");
            $table->string('name');
            $table->string('product_code');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parent_category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->boolean('tax_type')->default(1);
            $table->unsignedBigInteger('warranty_id')->nullable();
            $table->decimal('product_cost', 22, 2)->default(0.00);
            $table->decimal('product_cost_with_tax', 22, 2)->default(0.00);
            $table->decimal('profit', 22, 2)->default(0.00);
            $table->decimal('product_price', 22, 2)->default(0.00);
            $table->decimal('offer_price', 22, 2)->default(0.00);
            $table->boolean('is_manage_stock')->default(1);
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->decimal('combo_price', 22, 2)->default(0.00);
            $table->bigInteger('alert_quantity')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_combo')->default(0);
            $table->boolean('is_variant')->default(0);
            $table->boolean('is_show_in_ecom')->default(0);
            $table->boolean('is_show_emi_on_pos')->default(0);
            $table->boolean('is_for_sale')->default(1);
            $table->string('attachment')->nullable();
            $table->string('thumbnail_photo')->default('default.png');
            $table->string('expire_date')->nullable();
            $table->text('product_details')->nullable();
            $table->string('is_purchased')->default('0');
            $table->string('barcode_type')->nullable();
            $table->string('weight', 191)->nullable();
            $table->string('product_condition', 191)->nullable();
            $table->boolean('status')->default(1);
            $table->decimal('number_of_sale', 22, 2)->default(0.00);
            $table->decimal('total_transfered', 22, 2)->default(0.00);
            $table->decimal('total_adjusted', 22, 2)->default(0.00);
            $table->string('custom_field_1', 191)->nullable();
            $table->string('custom_field_2', 191)->nullable();
            $table->string('custom_field_3', 191)->nullable();
            $table->timestamps();
            
            $table->foreign('brand_id', 'products_brand_id_foreign')->references('id')->on('brands')->onDelete('set NULL');
            $table->foreign('category_id', 'products_category_id_foreign')->references('id')->on('categories')->onDelete('set NULL');
            $table->foreign('parent_category_id', 'products_parent_category_id_foreign')->references('id')->on('categories')->onDelete('set NULL');
            $table->foreign('tax_id', 'products_tax_id_foreign')->references('id')->on('taxes')->onDelete('set NULL');
            $table->foreign('unit_id', 'products_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('warranty_id', 'products_warranty_id_foreign')->references('id')->on('warranties')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
