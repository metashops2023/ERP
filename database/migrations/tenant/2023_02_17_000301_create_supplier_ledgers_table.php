<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('supplier_payment_id')->nullable();
            $table->boolean('row_type')->default(1)->comment("1=purchase;2=purchase_payment;3=opening_balance;4=direct_payment");
            $table->decimal('amount', 22, 2)->nullable()->comment("only_for_opening");
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->string('voucher_type', 20)->nullable();
            $table->decimal('debit', 22, 2)->default(0.00);
            $table->decimal('credit', 22, 2)->default(0.00);
            $table->decimal('running_balance', 22, 2)->default(0.00);
            $table->string('amount_type', 20)->nullable()->comment("debit/credit");
            
            $table->foreign('branch_id', 'supplier_ledgers_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('purchase_id', 'supplier_ledgers_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_payment_id', 'supplier_ledgers_purchase_payment_id_foreign')->references('id')->on('purchase_payments')->onDelete('cascade');
            $table->foreign('purchase_return_id', 'supplier_ledgers_purchase_return_id_foreign')->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->foreign('supplier_id', 'supplier_ledgers_supplier_id_foreign')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('supplier_payment_id', 'supplier_ledgers_supplier_payment_id_foreign')->references('id')->on('supplier_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_ledgers');
    }
}
