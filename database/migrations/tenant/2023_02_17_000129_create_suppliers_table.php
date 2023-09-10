<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_user_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->string('contact_id')->nullable();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternative_phone', 191)->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('tax_number')->nullable();
            $table->decimal('opening_balance', 22, 2)->default(0.00);
            $table->tinyInteger('pay_term')->nullable()->comment("1=months,2=days");
            $table->integer('pay_term_number')->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('total_purchase', 22, 2)->default(0.00);
            $table->decimal('total_paid', 22, 2)->default(0.00);
            $table->decimal('total_less', 22, 2)->default(0.00);
            $table->decimal('total_purchase_due', 22, 2)->default(0.00);
            $table->decimal('total_return', 22, 2)->default(0.00);
            $table->decimal('total_purchase_return_due', 22, 2)->default(0.00);
            $table->boolean('status')->default(1);
            $table->string('prefix', 191)->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
