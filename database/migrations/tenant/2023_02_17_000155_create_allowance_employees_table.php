<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowanceEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowance_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowance_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('allowance_id', 'allowance_employees_allowance_id_foreign')->references('id')->on('hrm_allowance')->onDelete('cascade');
            $table->foreign('user_id', 'allowance_employees_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowance_employees');
    }
}
