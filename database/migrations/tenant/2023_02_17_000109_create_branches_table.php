<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('branch_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->string('country', 191)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo', 191)->default('default.png');
            $table->unsignedBigInteger('invoice_schema_id')->nullable();
            $table->unsignedBigInteger('add_sale_invoice_layout_id')->nullable();
            $table->unsignedBigInteger('pos_sale_invoice_layout_id')->nullable();
            $table->unsignedBigInteger('default_account_id')->nullable();
            $table->boolean('purchase_permission')->default(0);
            $table->tinyInteger('after_purchase_store')->nullable()->comment("1=branch;2=warehouse");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
