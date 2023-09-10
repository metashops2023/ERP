<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_opening_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->decimal('amount', 22, 2)->default(0.00);
            $table->timestamp('report_date')->nullable();
            $table->boolean('is_show_again')->default(1);
            $table->unsignedBigInteger('created_by_id');
            $table->timestamps();
            
            $table->foreign('branch_id', 'customer_opening_balances_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by_id', 'customer_opening_balances_created_by_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('customer_id', 'customer_opening_balances_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_opening_balances');
    }
}
