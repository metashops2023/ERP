<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_to_branches', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->boolean('status')->default(1)->comment("1=pending;2=partial;3=completed");
            $table->unsignedBigInteger('warehouse_id')->comment("form_warehouse");
            $table->unsignedBigInteger('branch_id')->nullable()->comment("to_branch");
            $table->decimal('total_item', 8, 2);
            $table->decimal('total_send_qty', 22, 2)->default(0.00);
            $table->decimal('total_received_qty', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('shipping_charge', 22, 2)->default(0.00);
            $table->mediumText('additional_note')->nullable();
            $table->mediumText('receiver_note')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'transfer_stock_to_branches_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('warehouse_id', 'transfer_stock_to_branches_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_stock_to_branches');
    }
}
