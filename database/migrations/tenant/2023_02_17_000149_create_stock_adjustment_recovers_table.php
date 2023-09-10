<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentRecoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_recovers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no', 191)->nullable();
            $table->unsignedBigInteger('stock_adjustment_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('recovered_amount', 22, 2)->default(0.00);
            $table->mediumText('note')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            
            $table->foreign('account_id', 'stock_adjustment_recovers_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('payment_method_id', 'stock_adjustment_recovers_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('set NULL');
            $table->foreign('stock_adjustment_id', 'stock_adjustment_recovers_stock_adjustment_id_foreign')->references('id')->on('stock_adjustments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustment_recovers');
    }
}
