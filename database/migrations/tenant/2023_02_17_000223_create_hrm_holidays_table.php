<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('holiday_name');
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('is_all')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'hrm_holidays_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_holidays');
    }
}
