<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_payment_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->boolean('payment_type')->default(1)->comment("1=pay_loan_payment;2=get_loan_payment");
            $table->timestamps();
            
            $table->foreign('loan_id', 'loan_payment_distributions_loan_id_foreign')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('loan_payment_id', 'loan_payment_distributions_loan_payment_id_foreign')->references('id')->on('loan_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payment_distributions');
    }
}
