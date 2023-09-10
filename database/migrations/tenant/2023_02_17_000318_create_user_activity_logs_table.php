<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->tinyInteger('action')->nullable();
            $table->integer('subject_type')->nullable();
            $table->text('descriptions')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'user_activity_logs_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('user_id', 'user_activity_logs_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activity_logs');
    }
}
