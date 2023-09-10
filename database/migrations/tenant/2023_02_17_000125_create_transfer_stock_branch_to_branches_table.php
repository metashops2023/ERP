<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockBranchToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id', 191)->nullable();
            $table->unsignedBigInteger('sender_branch_id')->nullable();
            $table->unsignedBigInteger('sender_warehouse_id')->nullable();
            $table->unsignedBigInteger('receiver_branch_id')->nullable();
            $table->unsignedBigInteger('receiver_warehouse_id')->nullable();
            $table->decimal('total_item', 22, 2)->default(0.00);
            $table->decimal('total_stock_value', 22, 2)->default(0.00);
            $table->unsignedBigInteger('expense_account_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('payment_note')->nullable();
            $table->decimal('transfer_cost', 22, 2)->default(0.00);
            $table->decimal('total_send_qty', 22, 2)->default(0.00);
            $table->decimal('total_received_qty', 22, 2)->default(0.00);
            $table->decimal('total_pending_qty', 22, 2)->default(0.00);
            $table->boolean('receive_status')->default(1);
            $table->string('date')->nullable();
            $table->mediumText('transfer_note')->nullable();
            $table->mediumText('receiver_note')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            
            $table->foreign('bank_account_id', 'transfer_stock_branch_to_branches_bank_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('expense_account_id', 'transfer_stock_branch_to_branches_expense_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('payment_method_id', 'transfer_stock_branch_to_branches_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('set NULL');
            $table->foreign('receiver_branch_id', 'transfer_stock_branch_to_branches_receiver_branch_id_foreign')->references('id')->on('branches')->onDelete('set NULL');
            $table->foreign('receiver_warehouse_id', 'transfer_stock_branch_to_branches_receiver_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('set NULL');
            $table->foreign('sender_branch_id', 'transfer_stock_branch_to_branches_sender_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('sender_warehouse_id', 'transfer_stock_branch_to_branches_sender_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_stock_branch_to_branches');
    }
}
