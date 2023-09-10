<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('account_type')->default(2);
            $table->string('name');
            $table->string('account_number')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('opening_balance', 22, 2)->default(0.00);
            $table->decimal('debit', 22, 2)->default(0.00);
            $table->decimal('credit', 22, 2)->default(0.00);
            $table->decimal('balance', 22, 2)->default(0.00);
            $table->mediumText('remark')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('branch_id')->nullable();
            
            $table->foreign('bank_id', 'accounts_bank_id_foreign')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('branch_id', 'accounts_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
