<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCreditLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_credit_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->tinyInteger('customer_type')->nullable();
            $table->decimal('credit_limit', 22, 2)->nullable();
            $table->tinyInteger('pay_term')->nullable()->comment("1=months,2=days");
            $table->bigInteger('pay_term_number')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'customer_credit_limits_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by_id', 'customer_credit_limits_created_by_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('customer_id', 'customer_credit_limits_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_credit_limits');
    }
}
