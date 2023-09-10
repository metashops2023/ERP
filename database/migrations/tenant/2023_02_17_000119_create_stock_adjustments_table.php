<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_adjustment_account_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('recovered_amount', 22, 2)->default(0.00);
            $table->boolean('type')->default(0);
            $table->string('date')->nullable();
            $table->string('time', 50)->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('reason')->nullable();
            $table->timestamp('report_date_ts')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id', 'stock_adjustments_admin_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
            $table->foreign('branch_id', 'stock_adjustments_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('stock_adjustment_account_id', 'stock_adjustments_stock_adjustment_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('warehouse_id', 'stock_adjustments_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
}
