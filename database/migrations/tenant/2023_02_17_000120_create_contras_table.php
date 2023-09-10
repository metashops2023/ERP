<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contras', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('receiver_account_id')->nullable();
            $table->unsignedBigInteger('sender_account_id')->nullable();
            $table->decimal('amount', 22, 2)->default(0.00);
            $table->string('attachment')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'contras_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('receiver_account_id', 'contras_receiver_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('sender_account_id', 'contras_sender_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
            $table->foreign('user_id', 'contras_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contras');
    }
}
