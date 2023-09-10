<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->mediumText('category_ids')->nullable();
            $table->decimal('tax_percent', 22, 2)->default(0.00);
            $table->decimal('tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_amount', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('expense_account_id')->nullable();
            $table->unsignedBigInteger('transfer_branch_to_branch_id')->nullable();
            
            $table->foreign('branch_id', 'expenses_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('expense_account_id', 'expenses_expense_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('transfer_branch_to_branch_id', 'expenses_transfer_branch_to_branch_id_foreign')->references('id')->on('transfer_stock_branch_to_branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
