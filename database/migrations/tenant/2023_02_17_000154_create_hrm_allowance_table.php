<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmAllowanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_allowance', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('type');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->boolean('amount_type')->default(1)->comment("1=fixed;2=percentage");
            $table->decimal('amount', 22, 2)->default(0.00);
            $table->string('applicable_date')->nullable();
            $table->timestamps();
            
            $table->foreign('employee_id', 'hrm_allowance_employee_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_allowance');
    }
}
