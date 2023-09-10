<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('pay_mode')->nullable();
            $table->decimal('paid_amount', 22, 2)->default(0.00);
            $table->tinyInteger('payment_status')->nullable()->comment("1=due;2=partial;3=paid");
            $table->string('date');
            $table->string('month');
            $table->string('year');
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
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->timestamp('report_date')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            
            $table->foreign('account_id', 'expense_payments_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('expense_id', 'expense_payments_expense_id_foreign')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('payment_method_id', 'expense_payments_payment_method_id_foreign')->references('id')->on('payment_methods')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_payments');
    }
}
