<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('payment_type')->default(1)->comment("1=pay_loan_payment;2=get_loan_payment");
            $table->timestamps();
            
            $table->foreign('account_id', 'loan_payments_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('branch_id', 'loan_payments_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('company_id', 'loan_payments_company_id_foreign')->references('id')->on('loan_companies')->onDelete('cascade');
            $table->foreign('payment_method_id', 'loan_payments_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('user_id', 'loan_payments_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payments');
    }
}
