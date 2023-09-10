<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->timestamps();
            
            $table->foreign('cash_register_id', 'cash_register_transactions_cash_register_id_foreign')->references('id')->on('cash_registers')->onDelete('cascade');
            $table->foreign('sale_id', 'cash_register_transactions_sale_id_foreign')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_register_transactions');
    }
}
