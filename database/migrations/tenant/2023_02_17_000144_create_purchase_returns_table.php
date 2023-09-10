<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->tinyInteger('return_type')->nullable()->comment("1=purchase_invoice_return;2=supplier_purchase_return");
            $table->decimal('total_return_amount', 22, 2)->default(0.00);
            $table->decimal('total_return_due', 22, 2)->default(0.00);
            $table->decimal('total_return_due_received', 22, 2)->default(0.00);
            $table->decimal('purchase_tax_percent', 22, 2)->default(0.00);
            $table->decimal('purchase_tax_amount', 22, 2)->default(0.00);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('purchase_return_account_id')->nullable();
            
            $table->foreign('branch_id', 'purchase_returns_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('purchase_id', 'purchase_returns_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_return_account_id', 'purchase_returns_purchase_return_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('supplier_id', 'purchase_returns_supplier_id_foreign')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('warehouse_id', 'purchase_returns_warehouse_id_foreign')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
}
