<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->string('pay_mode')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('date');
            $table->string('time', 50)->nullable();
            $table->string('month');
            $table->string('year');
            $table->timestamp('report_date')->useCurrent()->useCurrentOnUpdate();
            $table->string('card_no')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_transaction_no')->nullable();
            $table->string('card_month')->nullable();
            $table->string('card_year')->nullable();
            $table->string('card_secure_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('account_id', 'hrm_payroll_payments_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('admin_id', 'hrm_payroll_payments_admin_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
            $table->foreign('payment_method_id', 'hrm_payroll_payments_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('payroll_id', 'hrm_payroll_payments_payroll_id_foreign')->references('id')->on('hrm_payrolls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payroll_payments');
    }
}
