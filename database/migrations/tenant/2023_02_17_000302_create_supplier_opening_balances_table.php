<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_opening_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('amount', 22, 2)->default(0.00);
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->boolean('is_show_again')->default(1);
            $table->timestamps();
            
            $table->foreign('branch_id', 'supplier_opening_balances_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by_id', 'supplier_opening_balances_created_by_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('supplier_id', 'supplier_opening_balances_supplier_id_foreign')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_opening_balances');
    }
}
