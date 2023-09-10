<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('loan_company_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('loan_account_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->decimal('loan_amount', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->decimal('total_paid', 22, 2)->default(0.00);
            $table->decimal('total_receive', 22, 2)->default(0.00);
            $table->timestamp('report_date')->nullable();
            $table->text('loan_reason')->nullable();
            $table->string('loan_by', 191)->nullable();
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->timestamps();
            
            $table->foreign('account_id', 'loans_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('branch_id', 'loans_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_user_id', 'loans_created_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
            $table->foreign('expense_id', 'loans_expense_id_foreign')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('loan_account_id', 'loans_loan_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('loan_company_id', 'loans_loan_company_id_foreign')->references('id')->on('loan_companies')->onDelete('cascade');
            $table->foreign('purchase_id', 'loans_purchase_id_foreign')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
