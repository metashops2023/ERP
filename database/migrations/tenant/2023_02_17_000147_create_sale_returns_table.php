<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22, 2)->default(0.00);
            $table->string('invoice_id');
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sale_return_account_id')->nullable();
            $table->boolean('return_discount_type')->default(1);
            $table->decimal('return_discount', 22, 2)->default(0.00);
            $table->decimal('return_discount_amount', 22, 2)->default(0.00);
            $table->decimal('return_tax', 22, 2)->default(0.00);
            $table->decimal('return_tax_amount', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('total_return_amount', 22, 2)->default(0.00);
            $table->decimal('total_return_due', 22, 2)->default(0.00);
            $table->decimal('total_return_due_pay', 22, 2)->default(0.00);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->text('return_note')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'sale_returns_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'sale_returns_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('sale_id', 'sale_returns_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_return_account_id', 'sale_returns_sale_return_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('warehouse_id', 'sale_returns_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_returns');
    }
}
