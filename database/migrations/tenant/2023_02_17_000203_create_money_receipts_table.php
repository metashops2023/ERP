<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id', 191)->nullable();
            $table->decimal('amount', 22, 2)->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->boolean('is_customer_name')->default(0);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('receiver')->nullable();
            $table->string('ac_details')->nullable();
            $table->boolean('is_date')->default(0);
            $table->boolean('is_header_less')->default(0);
            $table->bigInteger('gap_from_top')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'money_receipts_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'money_receipts_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_receipts');
    }
}
