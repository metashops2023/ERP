<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payroll_allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->string('allowance_name')->nullable();
            $table->string('amount_type')->default('1');
            $table->decimal('allowance_percent', 22, 2)->default(0.00);
            $table->decimal('allowance_amount', 22, 2)->default(0.00);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date_ts')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('payroll_id', 'hrm_payroll_allowances_payroll_id_foreign')->references('id')->on('hrm_payrolls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payroll_allowances');
    }
}
