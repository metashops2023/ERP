<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_account_id')->nullable();
            $table->unsignedBigInteger('cash_counter_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->decimal('cash_in_hand', 22, 2)->default(0.00);
            $table->string('date', 20)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->decimal('closed_amount', 22, 2)->default(0.00);
            $table->boolean('status')->default(1)->comment("1=open;0=closed;");
            $table->text('closing_note')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id', 'cash_registers_admin_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('branch_id', 'cash_registers_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('cash_counter_id', 'cash_registers_cash_counter_id_foreign')->references('id')->on('cash_counters')->onDelete('set NULL');
            $table->foreign('sale_account_id', 'cash_registers_sale_account_id_foreign')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_registers');
    }
}
