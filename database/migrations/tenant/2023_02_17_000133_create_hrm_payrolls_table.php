<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('reference_no')->nullable();
            $table->decimal('duration_time', 22, 2)->default(0.00);
            $table->string('duration_unit')->nullable();
            $table->decimal('amount_per_unit', 22, 2)->default(0.00);
            $table->decimal('total_amount', 22, 2)->default(0.00);
            $table->decimal('total_allowance_amount', 22, 2)->default(0.00);
            $table->decimal('total_deduction_amount', 22, 2)->default(0.00);
            $table->decimal('gross_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->timestamp('report_date_ts')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id', 'hrm_payrolls_admin_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
            $table->foreign('user_id', 'hrm_payrolls_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payrolls');
    }
}
