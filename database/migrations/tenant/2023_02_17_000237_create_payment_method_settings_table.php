<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->timestamps();
            
            $table->foreign('account_id', 'payment_method_settings_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('branch_id', 'payment_method_settings_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('payment_method_id', 'payment_method_settings_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_method_settings');
    }
}
