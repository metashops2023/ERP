<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#262b26');
            $table->bigInteger('sort_order')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->mediumText('sms_template')->nullable();
            $table->mediumText('mail_subject')->nullable();
            $table->text('mail_body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sv_status');
    }
}
